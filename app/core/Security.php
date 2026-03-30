<?php
// ─── CORE: SEGURIDAD ─────────────────────────────────
class Security {
    public static function generateCSRF(): string {
        $t = bin2hex(random_bytes(32));
        AppSession::set('csrf_token', $t);
        AppSession::set('csrf_time', time());
        return $t;
    }

    public static function validateCSRF(string $t): bool {
        $s = AppSession::get('csrf_token');
        return $s && hash_equals($s, $t) && (time() - AppSession::get('csrf_time', 0)) < 3600;
    }

    public static function sanitize(string $s): string {
        return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
    }

    public static function hashPassword(string $p): string {
        return password_hash($p, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    }

    public static function verifyPassword(string $p, string $h): bool {
        return password_verify($p, $h);
    }

    public static function generateToken(int $len = 32): string {
        return bin2hex(random_bytes($len));
    }

    public static function clientIP(): string {
        // CORRECCIÓN: Evitar "Notice: Undefined index" si el campo no existe en $_SERVER
        $forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        return $forwarded
            ? trim(explode(',', $forwarded)[0])
            : ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }

    public static function userAgent(): string {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

    public static function passwordStrength(string $p): array {
        $e = [];
        if (strlen($p) < 8) $e[] = 'Mínimo 8 caracteres';
        if (!preg_match('/[A-Z]/', $p)) $e[] = 'Al menos una mayúscula';
        if (!preg_match('/[a-z]/', $p)) $e[] = 'Al menos una minúscula';
        if (!preg_match('/[0-9]/', $p)) $e[] = 'Al menos un número';
        if (!preg_match('/[^A-Za-z0-9]/', $p)) $e[] = 'Al menos un carácter especial';
        return $e;
    }

    public static function validEmail(string $e): bool {
        return filter_var($e, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function headers(): void {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }

    public static function rateLimit(string $act, int $max = 5, int $win = 300): bool {
        $k = 'rl_' . $act . '_' . md5(self::clientIP());
        $a = AppSession::get($k, ['c' => 0, 't' => 0]);
        if (time() - $a['t'] > $win) $a = ['c' => 0, 't' => time()];
        if ($a['c'] >= $max) return false;
        $a['c']++;
        $a['t'] = time();
        AppSession::set($k, $a);
        return true;
    }
}
