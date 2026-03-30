<?php
// ─── CONTROLADOR: DASHBOARD ──────────────────────────
class DashboardController {
    private UserModel $um;
    private AuditLogModel $alm;
    private UserSessionModel $sm;

    public function __construct() {
        $this->um = new UserModel();
        $this->alm = new AuditLogModel();
        $this->sm = new UserSessionModel();
    }

    public function index(): void {
        $token = AppSession::get('session_token');
        $user = $token ? $this->sm->validate($token) : null;
        if (!$user) {
            header('Location:?action=login');
            exit;
        }

        $data = [
            'user' => $user,
            'totalUsers' => $this->um->countAll(),
            'verifiedUsers' => $this->um->countVerified(),
            'activeToday' => $this->um->countActiveToday(),
            'activeSessions' => $this->sm->activeCount(),
            'critical24h' => $this->alm->criticalCount24h(),
            'logs' => $this->alm->recent(10),
        ];

        viewDashboard($data);
    }
}
