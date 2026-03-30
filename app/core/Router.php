<?php
// ─── CORE: ROUTER ────────────────────────────────────
class Router {
    public static function handle(): void {
        $action = $_GET['action'] ?? 'login';
        $method = $_SERVER['REQUEST_METHOD'];

        $auth = new AuthController();
        $dash = new DashboardController();

        switch ($action) {
            case 'login':
                if ($method === 'POST') $auth->processLogin();
                else $auth->showLogin();
                break;

            case 'register':
                if ($method === 'POST') $auth->processRegister();
                else $auth->showRegister();
                break;

            case 'verify-otp':
                if ($method === 'POST') $auth->processVerifyOTP();
                else $auth->showVerifyOTP();
                break;

            case 'dashboard':
                $dash->index();
                break;

            case 'logout':
                $auth->logout();
                break;

            default:
                header('Location:?action=login');
                break;
        }
    }
}
