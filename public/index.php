<?php
/**
 * =====================================================
 * SecureAuth Enterprise — Bootstrap
 * =====================================================
 */

// Autoloading manual y Composer
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/core/Session.php';
require_once __DIR__ . '/../app/core/Security.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/OTPModel.php';
require_once __DIR__ . '/../app/models/AuditLogModel.php';
require_once __DIR__ . '/../app/models/UserSessionModel.php';
require_once __DIR__ . '/../app/services/EmailService.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

// Iniciar sesión y headers de seguridad
AppSession::start();
Security::headers();

// Helpers para vistas (usados por los controladores)
function viewLogin(string $csrf): void {
    require_once __DIR__ . '/../app/views/login.php';
}

function viewRegister(string $csrf): void {
    require_once __DIR__ . '/../app/views/register.php';
}

function viewVerifyOTP(
    string $csrf, 
    string $email, 
    string $name, 
    string $mode, 
    ?string $dbgOTP, 
    ?string $dbgEmail, 
    bool $canResend, 
    int $cooldown
): void {
    require_once __DIR__ . '/../app/views/verify-otp.php';
}

function viewDashboard(array $data): void {
    extract($data);
    require_once __DIR__ . '/../app/views/dashboard.php';
}

// Ejecutar el Router
Router::handle();
