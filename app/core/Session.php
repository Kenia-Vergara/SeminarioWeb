<?php
// ─── CORE: SESIÓN ────────────────────────────────────
class AppSession {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Strict');
            session_name('SA_ENT_SID');
            session_start();
        }
    }

    public static function regenerate(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function set(string $k, mixed $v): void {
        $_SESSION[$k] = $v;
    }

    public static function get(string $k, mixed $d = null): mixed {
        return $_SESSION[$k] ?? $d;
    }

    public static function remove(string $k): void {
        unset($_SESSION[$k]);
    }

    public static function destroy(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $p['path'],
                $p['domain'],
                $p['secure'],
                $p['httponly']
            );
        }
        session_destroy();
    }

    public static function setFlash(string $t, string $m): void {
        $_SESSION['flash'][$t] = $m;
    }

    public static function getFlash(string $t): ?string {
        $m = $_SESSION['flash'][$t] ?? null;
        if ($m !== null) {
            unset($_SESSION['flash'][$t]);
        }
        return $m;
    }
}
