<?php
// ─── CONTROLADOR: DASHBOARD ──────────────────────────
class DashboardController {
    private UserModel $um;
    private AuditLogModel $alm;
    private UserSessionModel $sm;

    public function __construct() {
        $this->um  = new UserModel();
        $this->alm = new AuditLogModel();
        $this->sm  = new UserSessionModel();
    }

    public function index(): void {
        $token = AppSession::get('session_token');
        $user  = $token ? $this->sm->validate($token) : null;
        if (!$user) {
            header('Location:?action=login');
            exit;
        }

        // Paginación
        $perPage = 15;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * $perPage;

        // Filtros
        $filterSev    = $_GET['severity'] ?? '';
        $filterAction = $_GET['action_filter'] ?? '';

        $totalLogs = $this->alm->countFiltered($filterSev, $filterAction);
        $totalPages = max(1, (int)ceil($totalLogs / $perPage));
        $page       = min($page, $totalPages);
        $offset     = ($page - 1) * $perPage;

        $data = [
            'user'              => $user,
            // Métricas de usuarios
            'totalUsers'        => $this->um->countAll(),
            'verifiedUsers'     => $this->um->countVerified(),
            'activeToday'       => $this->um->countActiveToday(),
            'activeSessions'    => $this->sm->activeCount(),
            // Métricas de auditoría
            'critical24h'       => $this->alm->criticalCount24h(),
            'warning24h'        => $this->alm->warningCount24h(),
            'loginSuccess24h'   => $this->alm->loginSuccessCount24h(),
            'loginFailed24h'    => $this->alm->loginFailedCount24h(),
            // Tabla paginada
            'logs'              => $this->alm->getFiltered($perPage, $offset, $filterSev, $filterAction),
            'totalLogs'         => $totalLogs,
            'currentPage'       => $page,
            'totalPages'        => $totalPages,
            'perPage'           => $perPage,
            // Filtros activos
            'filterSev'         => $filterSev,
            'filterAction'      => $filterAction,
            'distinctActions'   => $this->alm->distinctActions(),
        ];

        viewDashboard($data);
    }
}
