<?php
define('DB_HOST',    'localhost');
define('DB_NAME',    'codecraft_auth');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

define('APP_NAME',           'CodeCraft');
define('OTP_LENGTH',         6);
define('OTP_EXPIRY_SEC',     300);
define('OTP_MAX_ATTEMPTS',   3);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_MINUTES',    15);
define('SESSION_LIFETIME',   3600);
define('BCRYPT_COST',        12);

// 'debug' = muestra OTP en pantalla (desarrollo)
// 'smtp'  = envía correo real con PHPMailer
define('EMAIL_MODE', 'smtp');

define('SMTP_HOST',      'smtp.gmail.com');
define('SMTP_PORT',      587);
define('SMTP_USER',      'burguillosdylan@gmail.com');
define('SMTP_PASS',      'TU_CONTRASENA_DE_APLICACION_GMAIL');
define('SMTP_FROM',      'burguillosdylan@gmail.com');
define('SMTP_FROM_NAME', 'CodeCraft');