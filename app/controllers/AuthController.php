<?php
// ─── CONTROLADOR: AUTH ───────────────────────────────
class AuthController {
    private UserModel $um;
    private OTPModel $om;
    private AuditLogModel $alm;
    private UserSessionModel $sm;
    private EmailService $es;

    public function __construct() {
        $this->um = new UserModel();
        $this->om = new OTPModel();
        $this->alm = new AuditLogModel();
        $this->sm = new UserSessionModel();
        $this->es = new EmailService();
    }

    public function authenticated(): bool {
        $t = AppSession::get('session_token');
        return $t && $this->sm->validate($t) !== null;
    }

    public function currentUser(): ?array {
        $t = AppSession::get('session_token');
        return $t ? $this->sm->validate($t) : null;
    }

    public function showLogin(): void {
        if ($this->authenticated()) {
            header('Location:?action=dashboard');
            exit;
        }
        $csrf = Security::generateCSRF();
        viewLogin($csrf);
    }

    public function processLogin(): void {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
            AppSession::setFlash('error', 'Token inválido.');
            header('Location:?action=login');
            exit;
        }
        if (!Security::rateLimit('login', MAX_LOGIN_ATTEMPTS, 300)) {
            AppSession::setFlash('error', 'Demasiados intentos. Espere 5 minutos.');
            header('Location:?action=login');
            exit;
        }
        $email = Security::sanitize($_POST['email'] ?? '');
        $pass = $_POST['password'] ?? '';
        if (!$email || !$pass) {
            AppSession::setFlash('error', 'Todos los campos son obligatorios.');
            header('Location:?action=login');
            exit;
        }
        $user = $this->um->findByEmail($email);
        if (!$user) {
            $this->alm->log(null, 'LOGIN_FAILED', "Email: {$email}", 'warning');
            AppSession::setFlash('error', 'Credenciales incorrectas.');
            header('Location:?action=login');
            exit;
        }
        if (!(int)$user['is_active']) {
            $this->alm->log((int)$user['id'], 'LOGIN_BLOCKED', 'Cuenta desactivada', 'critical');
            AppSession::setFlash('error', 'Cuenta desactivada. Contacte al administrador.');
            header('Location:?action=login');
            exit;
        }
        if ($this->um->isLocked((int)$user['id'])) {
            $m = $this->um->lockMinutesLeft((int)$user['id']);
            AppSession::setFlash("error", "Cuenta bloqueada. Intente en {$m} minuto(s).");
            header('Location:?action=login');
            exit;
        }
        if (!Security::verifyPassword($pass, $user['password'])) {
            $this->um->incFailed((int)$user['id']);
            // Recargar para obtener el conteo actualizado de intentos
            $user = $this->um->findById((int)$user['id']);
            $rem = MAX_LOGIN_ATTEMPTS - (int)$user['failed_login_attempts'];
            AppSession::setFlash('error', "Credenciales incorrectas. {$rem} intento(s) restante(s).");
            header('Location:?action=login');
            exit;
        }
        $this->um->resetFailed((int)$user['id']);
        if (!(int)$user['is_verified']) {
            AppSession::set('pending_verify_id', (int)$user['id']);
            $this->sendOTP((int)$user['id'], $user['full_name'], $user['email'], 'verification');
            header('Location:?action=verify-otp');
            exit;
        }
        AppSession::set('pending_2fa_id', (int)$user['id']);
        $this->sendOTP((int)$user['id'], $user['full_name'], $user['email'], 'login');
        header('Location:?action=verify-otp&mode=2fa');
        exit;
    }

    public function showRegister(): void {
        if ($this->authenticated()) {
            header('Location:?action=dashboard');
            exit;
        }
        $csrf = Security::generateCSRF();
        viewRegister($csrf);
    }

    public function processRegister(): void {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
            AppSession::setFlash('error', 'Token inválido.');
            header('Location:?action=register');
            exit;
        }
        if (!Security::rateLimit('register', 3, 300)) {
            AppSession::setFlash('error', 'Demasiados intentos. Espere.');
            header('Location:?action=register');
            exit;
        }
        $d = [
            'company_name' => $_POST['company_name'] ?? '',
            'company_nit' => $_POST['company_nit'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm' => $_POST['confirm_password'] ?? '',
            'department' => $_POST['department'] ?? '',
        ];
        $errs = [];
        foreach (['company_name', 'company_nit', 'full_name', 'email', 'password', 'department'] as $f) {
            if (empty($d[$f])) {
                $errs[] = 'Todos los campos son obligatorios.';
                break;
            }
        }
        if (!Security::validEmail($d['email'])) $errs[] = 'Correo inválido.';
        if ($d['password'] !== $d['confirm']) $errs[] = 'Las contraseñas no coinciden.';
        $errs = array_merge($errs, Security::passwordStrength($d['password']));
        if ($this->um->findByEmail($d['email'])) $errs[] = 'Este correo ya está registrado.';
        if ($errs) {
            AppSession::set('form_errors', $errs);
            AppSession::set('form_data', $d);
            header('Location:?action=register');
            exit;
        }
        try {
            $uid = $this->um->create($d);
            $this->alm->log($uid, 'USER_REGISTERED', "Nuevo registro: {$d['email']}", 'info');
            AppSession::set('pending_verify_id', $uid);
            $this->sendOTP($uid, $d['full_name'], $d['email'], 'verification');
            AppSession::setFlash('success', 'Cuenta creada. Verifique su correo.');
            header('Location:?action=verify-otp');
            exit;
        } catch (PDOException $e) {
            error_log("Register error: " . $e->getMessage());
            AppSession::setFlash('error', 'Error al crear la cuenta.');
            header('Location:?action=register');
            exit;
        }
    }

    public function showVerifyOTP(): void {
        $mode = $_GET['mode'] ?? 'verification';
        $key = $mode === '2fa' ? 'pending_2fa_id' : 'pending_verify_id';
        $uid = AppSession::get($key);
        if (!$uid) {
            header('Location:?action=login');
            exit;
        }
        $user = $this->um->findById((int)$uid);
        if (!$user) {
            header('Location:?action=login');
            exit;
        }
        $csrf = Security::generateCSRF();
        $dbgOTP = AppSession::get('debug_otp');
        $dbgEmail = AppSession::get('debug_email');
        $purpose = $mode === '2fa' ? 'login' : 'verification';
        $canResend = $this->om->canResend((int)$uid, $purpose);
        $cooldown = $this->om->cooldownSec((int)$uid, $purpose);
        viewVerifyOTP($csrf, $user['email'], $user['full_name'], $mode, $dbgOTP, $dbgEmail, $canResend, $cooldown);
    }

    public function processVerifyOTP(): void {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
            AppSession::setFlash('error', 'Token inválido.');
            header('Location:?action=verify-otp');
            exit;
        }
        $code = preg_replace('/\D/', '', $_POST['otp_code'] ?? '');
        $mode = $_POST['mode'] ?? 'verification';
        $purpose = $mode === '2fa' ? 'login' : 'verification';
        $key = $mode === '2fa' ? 'pending_2fa_id' : 'pending_verify_id';
        $uid = AppSession::get($key);
        if (!$uid || strlen($code) !== OTP_LENGTH) {
            AppSession::setFlash('error', 'Código inválido.');
            header('Location:?action=verify-otp' . ($mode === '2fa' ? '&mode=2fa' : ''));
            exit;
        }
        $r = $this->om->verify((int)$uid, $code, $purpose);
        if (!$r['ok']) {
            $this->alm->log((int)$uid, 'OTP_FAILED', $r['err'], 'warning');
            AppSession::setFlash('error', $r['err']);
            header('Location:?action=verify-otp' . ($mode === '2fa' ? '&mode=2fa' : ''));
            exit;
        }
        if ($mode === 'verification') {
            $this->um->setVerified((int)$uid, true);
            $this->alm->log((int)$uid, 'ACCOUNT_VERIFIED', 'Cuenta verificada exitosamente', 'info');
            AppSession::remove('pending_verify_id');
            AppSession::setFlash('success', 'Cuenta verificada. Ya puede iniciar sesión.');
            header('Location:?action=login');
            exit;
        }
        // Login 2FA exitoso — registrar en auditoría ANTES de crear sesión
        $this->alm->log((int)$uid, 'LOGIN_SUCCESS', 'Inicio de sesión exitoso vía OTP 2FA', 'info');
        $this->um->updateLastLogin((int)$uid);
        $token = Security::generateToken();
        $this->sm->create((int)$uid, $token);
        AppSession::set('session_token', $token);
        AppSession::remove('pending_2fa_id');
        AppSession::regenerate();
        header('Location:?action=dashboard');
        exit;
    }

    public function logout(): void {
        $token = AppSession::get('session_token');
        if ($token) {
            $user = $this->sm->validate($token);
            if ($user) {
                $this->alm->log((int)$user['user_id'], 'LOGOUT', 'Sesión cerrada correctamente', 'info');
            }
            $this->sm->deactivate($token);
        }
        AppSession::destroy();
        header('Location:?action=login');
        exit;
    }

    private function sendOTP(int $uid, string $name, string $email, string $purpose): void {
        $code = $this->om->generate($uid, $purpose);
        $this->es->sendOTP($email, $name, $code, $purpose);
    }
}
