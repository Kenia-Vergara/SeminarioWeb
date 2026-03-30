<?php
/**
 * Controlador de autenticación — SIN funciones de vista.
 * Cada página (login.php, register.php, otp.php) renderiza su propio HTML.
 */
class AuthController {
    private UserModel         $um;
    private OTPModel          $om;
    private AuditLogModel     $alm;
    private UserSessionModel  $sm;
    private EmailService      $es;

    public function __construct() {
        $this->um  = new UserModel();
        $this->om  = new OTPModel();
        $this->alm = new AuditLogModel();
        $this->sm  = new UserSessionModel();
        $this->es  = new EmailService();
    }

    /* ── ¿Autenticado? ── */
    public function authenticated(): bool {
        $token = AppSession::get('session_token');
        return $token !== null && $this->sm->validate($token) !== null;
    }

    public function currentUser(): ?array {
        $token = AppSession::get('session_token');
        return $token ? $this->sm->validate($token) : null;
    }

    /* ── LOGIN ── */
    public function showLogin(): void {
        if ($this->authenticated()) {
            header('Location: acceso.php');
            exit;
        }
    }

    public function processLogin(): void {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
            AppSession::setFlash('error', 'Token de seguridad inválido. Recarga la página.');
            header('Location: login.php');
            exit;
        }

        if (!Security::rateLimit('login', MAX_LOGIN_ATTEMPTS, 300)) {
            AppSession::setFlash('error', 'Demasiados intentos. Espera 5 minutos.');
            header('Location: login.php');
            exit;
        }

        $email = Security::sanitize($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';

        if (!$email || !$pass) {
            AppSession::setFlash('error', 'Todos los campos son obligatorios.');
            header('Location: login.php');
            exit;
        }

        $user = $this->um->findByEmail($email);

        if (!$user) {
            $this->alm->log(null, 'LOGIN_FAILED', "Email no encontrado: {$email}", 'warning');
            AppSession::setFlash('error', 'Credenciales incorrectas.');
            header('Location: login.php');
            exit;
        }

        if (!(int) $user['is_active']) {
            $this->alm->log((int) $user['id'], 'LOGIN_BLOCKED', 'Cuenta desactivada', 'critical');
            AppSession::setFlash('error', 'Cuenta desactivada. Contacta al administrador.');
            header('Location: login.php');
            exit;
        }

        if ($this->um->isLocked((int) $user['id'])) {
            $mins = $this->um->lockMinutesLeft((int) $user['id']);
            AppSession::setFlash('error', "Cuenta bloqueada. Intenta en {$mins} minuto(s).");
            header('Location: login.php');
            exit;
        }

        if (!Security::verifyPassword($pass, $user['password'])) {
            $this->um->incFailed((int) $user['id']);
            $remaining = MAX_LOGIN_ATTEMPTS - ((int) $user['failed_login_attempts'] + 1);
            $this->alm->log((int) $user['id'], 'LOGIN_FAILED', "Contraseña incorrecta ({$remaining} restantes)", 'warning');
            AppSession::setFlash('error', "Credenciales incorrectas. {$remaining} intento(s) restante(s).");
            header('Location: login.php');
            exit;
        }

        /* Credenciales válidas */
        $this->um->resetFailed((int) $user['id']);

        /* Cuenta no verificada → enviar OTP de verificación */
        if (!(int) $user['is_verified']) {
            AppSession::set('pending_verify_id', (int) $user['id']);
            AppSession::set('verify_mode', 'verification');
            $this->sendOTP((int) $user['id'], $user['full_name'], $user['email'], 'verification');
            $this->alm->log((int) $user['id'], 'OTP_SENT', 'Verificación de cuenta', 'info');
            header('Location: otp.php');
            exit;
        }

        /* Cuenta verificada → 2FA con OTP */
        AppSession::set('pending_2fa_id', (int) $user['id']);
        AppSession::set('verify_mode', 'login');
        $this->sendOTP((int) $user['id'], $user['full_name'], $user['email'], 'login');
        $this->alm->log((int) $user['id'], 'OTP_SENT', '2FA login', 'info');
        header('Location: otp.php');
        exit;
    }

    /* ── REGISTRO ── */
    public function showRegister(): void {
        if ($this->authenticated()) {
            header('Location: acceso.php');
            exit;
        }
    }

    public function processRegister(): void {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
            AppSession::setFlash('error', 'Token de seguridad inválido.');
            header('Location: register.php');
            exit;
        }

        if (!Security::rateLimit('register', 3, 300)) {
            AppSession::setFlash('error', 'Demasiados intentos de registro. Espera.');
            header('Location: register.php');
            exit;
        }

        $d = [
            'company_name' => $_POST['company_name'] ?? '',
            'company_nit'  => $_POST['company_nit'] ?? '',
            'full_name'    => $_POST['full_name'] ?? '',
            'email'        => $_POST['email'] ?? '',
            'password'     => $_POST['password'] ?? '',
            'confirm'      => $_POST['confirm_password'] ?? '',
            'department'   => $_POST['department'] ?? '',
        ];

        $errors = [];

        foreach (['company_name', 'company_nit', 'full_name', 'email', 'password', 'department'] as $f) {
            if (empty($d[$f])) {
                $errors[] = 'Todos los campos son obligatorios.';
                break;
            }
        }

        if (!Security::validEmail($d['email']))           $errors[] = 'Correo electrónico inválido.';
        if ($d['password'] !== $d['confirm'])              $errors[] = 'Las contraseñas no coinciden.';
        if (strlen($d['password']) < 8)                    $errors[] = 'La contraseña debe tener al menos 8 caracteres.';

        $errors = array_merge($errors, Security::passwordStrength($d['password']));

        if ($this->um->findByEmail($d['email']))             $errors[] = 'Este correo ya está registrado.';

        if ($errors) {
            AppSession::set('form_errors', $errors);
            AppSession::set('form_data', $d);
            header('Location: register.php');
            exit;
        }

        try {
            $uid = $this->um->create($d);
            $this->alm->log($uid, 'USER_REGISTERED', "Nuevo registro: {$d['email']}", 'info');
            AppSession::set('pending_verify_id', $uid);
            AppSession::set('verify_mode', 'verification');
            $this->sendOTP($uid, $d['full_name'], $d['email'], 'verification');
            AppSession::setFlash('success', 'Cuenta creada. Verifica tu correo con el código enviado.');
            header('Location: otp.php');
            exit;
        } catch (PDOException $e) {
            error_log("Register error: " . $e->getMessage());
            AppSession::setFlash('error', 'Error al crear la cuenta. Inténtalo de nuevo.');
            header('Location: register.php');
            exit;
        }
    }

    /* ── OTP: mostrar datos para la vista ── */
    public function getOTPViewData(): ?array {
        $mode = AppSession::get('verify_mode', 'verification');
        $key  = $mode === 'login' ? 'pending_2fa_id' : 'pending_verify_id';
        $uid  = AppSession::get($key);

        if (!$uid) return null;

        $user = $this->um->findById((int) $uid);
        if (!$user) return null;

        $purpose   = $mode === 'login' ? 'login' : 'verification';
        $canResend = $this->om->canResend((int) $uid, $purpose);
        $cooldown  = $this->om->cooldownSec((int) $uid, $purpose);
        $debugOTP  = EMAIL_MODE === 'debug' ? AppSession::get('debug_otp') : null;
        $debugEmail = EMAIL_MODE === 'debug' ? AppSession::get('debug_email') : null;

        return [
            'user'       => $user,
            'mode'       => $mode,
            'canResend'  => $canResend,
            'cooldown'   => $cooldown,
            'debugOTP'   => $debugOTP,
            'debugEmail' => $debugEmail,
        ];
    }

    /* ── OTP: verificar código ── */
    public function processVerifyOTP(): void {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
            AppSession::setFlash('error', 'Token de seguridad inválido.');
            header('Location: otp.php');
            exit;
        }

        $code    = preg_replace('/\D/', '', $_POST['otp_code'] ?? '');
        $mode    = AppSession::get('verify_mode', 'verification');
        $purpose = $mode === 'login' ? 'login' : 'verification';
        $key     = $mode === 'login' ? 'pending_2fa_id' : 'pending_verify_id';
        $uid     = AppSession::get($key);

        if (!$uid || strlen($code) !== OTP_LENGTH) {
            AppSession::setFlash('error', 'Código inválido.');
            header('Location: otp.php');
            exit;
        }

        $result = $this->om->verify((int) $uid, $code, $purpose);

        if (!$result['ok']) {
            $this->alm->log((int) $uid, 'OTP_FAILED', $result['err'], 'warning');
            AppSession::setFlash('error', $result['err']);
            header('Location: otp.php');
            exit;
        }

        $user = $this->um->findById((int) $uid);
        if (!$user) {
            header('Location: login.php');
            exit;
        }

        /* Verificación de cuenta */
        if ($mode === 'verification') {
            $this->um->setVerified((int) $uid, true);
            $this->alm->log((int) $uid, 'ACCOUNT_VERIFIED', 'Cuenta verificada exitosamente', 'info');
            AppSession::remove('pending_verify_id');
            AppSession::remove('verify_mode');
            AppSession::setFlash('success', 'Cuenta verificada. Ya puedes iniciar sesión.');
            header('Location: login.php');
            exit;
        }

        /* 2FA exitoso → crear sesión */
        $this->um->updateLastLogin((int) $uid);
        AppSession::remove('pending_2fa_id');
        AppSession::remove('verify_mode');

        $token = Security::generateToken();
        $this->sm->create((int) $uid, $token);
        AppSession::set('session_token', $token);
        AppSession::regenerate();

        $this->alm->log((int) $uid, 'LOGIN_SUCCESS', 'Inicio de sesión exitoso (2FA)', 'info');
        header('Location: acceso.php');
        exit;
    }

    /* ── Reenviar OTP ── */
    public function processResendOTP(): void {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
            AppSession::setFlash('error', 'Token inválido.');
            header('Location: otp.php');
            exit;
        }

        $mode    = AppSession::get('verify_mode', 'verification');
        $key     = $mode === 'login' ? 'pending_2fa_id' : 'pending_verify_id';
        $uid     = AppSession::get($key);
        $purpose = $mode === 'login' ? 'login' : 'verification';

        if (!$uid) {
            header('Location: login.php');
            exit;
        }

        if (!$this->om->canResend((int) $uid, $purpose)) {
            $cd = $this->om->cooldownSec((int) $uid, $purpose);
            AppSession::setFlash('error', "Espera {$cd} segundos antes de reenviar.");
            header('Location: otp.php');
            exit;
        }

        $user = $this->um->findById((int) $uid);
        if (!$user) {
            header('Location: login.php');
            exit;
        }

        $this->sendOTP((int) $uid, $user['full_name'], $user['email'], $purpose);
        $this->alm->log((int) $uid, 'OTP_RESENT', "Reenvío OTP ({$purpose})", 'info');
        AppSession::setFlash('success', 'Nuevo código enviado a tu correo.');
        header('Location: otp.php');
        exit;
    }

    /* ── Logout ── */
    public function processLogout(): void {
        $token = AppSession::get('session_token');
        if ($token) {
            $session = $this->sm->validate($token);
            if ($session) {
                $this->sm->deactivate($token);
                $this->alm->log((int) $session['user_id'], 'LOGOUT', 'Cierre de sesión', 'info');
            }
        }
        AppSession::destroy();
        header('Location: login.php');
        exit;
    }

    /* ── Dashboard data ── */
    public function getDashboardData(): array {
        $user = $this->currentUser();
        if (!$user) {
            header('Location: login.php');
            exit;
        }
        return [
            'user'         => $user,
            'totalUsers'   => $this->um->countAll(),
            'verifiedUsers'=> $this->um->countVerified(),
            'activeToday'  => $this->um->countActiveToday(),
            'activeSessions'=> $this->sm->activeCount(),
            'critical24h'  => $this->alm->criticalCount24h(),
            'logs'         => $this->alm->recent(10),
        ];
    }

    /* ── Helper: generar y enviar OTP ── */
    private function sendOTP(int $uid, string $name, string $email, string $purpose): void {
        $code = $this->om->generate($uid, $purpose);
        $this->es->sendOTP($email, $name, $code, $purpose);
    }
}