<?php
// ─── CONFIGURACIÓN ────────────────────────────────────
const DB_HOST = 'localhost';
const DB_NAME = 'enterprise_auth';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';
const APP_NAME = 'SecureAuth Enterprise';
const OTP_LENGTH = 6;
const OTP_EXPIRY_SEC = 300;
const OTP_MAX_ATTEMPTS = 3;
const MAX_LOGIN_ATTEMPTS = 5;
const LOCKOUT_MINUTES = 15;
const SESSION_LIFETIME = 3600;
const BCRYPT_COST = 12;
// 'debug' muestra OTP en pantalla | 'smtp' usa PHPMailer | 'mail' usa mail()
const EMAIL_MODE = 'smtp';
const SMTP_HOST = 'smtp.gmail.com';
const SMTP_PORT = 587;
const SMTP_USER = 'keniatafur2005@gmail.com';
const SMTP_PASS = 'cqcd zopx oxaf frix';
const SMTP_FROM = 'keniatafur2005@gmail.com';
const SMTP_FROM_NAME = 'SecureAuth Enterprise';

// ─── CORE: BASE DE DATOS (PDO) ───────────────────────
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ]);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    public static function tableExists(string $table): bool
    {
        try {
            $s = self::getConnection()->prepare(
                "SELECT 1 FROM information_schema.tables WHERE table_schema=? AND table_name=? LIMIT 1"
            );
            $s->execute([DB_NAME, $table]);
            return (bool) $s->fetch();
        } catch (PDOException) {
            return false;
        }
    }

    public static function checkAllTables(): bool
    {
        return self::tableExists('users') && self::tableExists('otp_codes')
            && self::tableExists('audit_logs') && self::tableExists('user_sessions');
    }
}
