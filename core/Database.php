<?php
/**
 * Singleton PDO — conexión a MySQL.
 */
class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]);
        }
        return self::$instance;
    }

    public static function tableExists(string $table): bool {
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

    public static function allTablesExist(): bool {
        return self::tableExists('users')
            && self::tableExists('otp_codes')
            && self::tableExists('audit_logs')
            && self::tableExists('user_sessions');
    }
}