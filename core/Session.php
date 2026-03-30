<?php
/**
 * Gestión de sesión segura con flags httponly, samesite, regeneración.
 */
class AppSession {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Strict');
            session_name('CC_ENT_SID');
            session_start();
        }
    }

    public static function regenerate(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function set(string $k, mixed $v): void    { $_SESSION[$k] = $v; }
    public static function get(string $k, mixed $d = null): mixed { return $_SESSION[$k] ?? $d; }
    public static function remove(string $k): void            { unset($_SESSION[$k]); }

    public static function destroy(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    /* Flash messages: se leen una sola vez */
    public static function setFlash(string $type, string $msg): void {
        $_SESSION['flash'][$type] = $msg;
    }

    public static function getFlash(string $type): ?string {
        $msg = $_SESSION['flash'][$type] ?? null;
        if ($msg !== null) unset($_SESSION['flash'][$type]);
        return $msg;
    }

    public static function hasFlash(string $type): bool {
        return isset($_SESSION['flash'][$type]);
    }
}