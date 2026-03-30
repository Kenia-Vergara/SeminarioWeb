<?php
class DashboardController {
    private UserModel         $um;
    private AuditLogModel     $alm;
    private UserSessionModel  $sm;

    public function __construct() {
        $this->um  = new UserModel();
        $this->alm = new AuditLogModel();
        $this->sm  = new UserSessionModel();
    }

    /**
     * Retorna todos los datos que necesita acceso.php para renderizar.
     * Ya no llama ninguna función de vista — la página se encarga del HTML.
     */
    public function getDashboardData(): array {
        $token = AppSession::get('session_token');

        if (!$token) {
            return ['user' => null];
        }

        $session = $this->sm->validate($token);

        if (!$session) {
            return ['user' => null];
        }

        return [
            'user'           => $session,
            'totalUsers'     => $this->um->countAll(),
            'verifiedUsers'  => $this->um->countVerified(),
            'activeToday'    => $this->um->countActiveToday(),
            'activeSessions' => $this->sm->activeCount(),
            'critical24h'    => $this->alm->criticalCount24h(),
            'logs'           => $this->alm->recent(10),
        ];
    }
}