<?php
/**
 * Utilidades de seguridad: CSRF, hashing, rate limiting, validaciones, headers.
 */
class Security {

    /* ── CSRF ── */
    public static function generateCSRF(): string {
        $token = bin2hex(random_bytes(32));
        AppSession::set('csrf_token', $token);
        AppSession::set('csrf_time', time());
        return $token;
    }

    public static function validateCSRF(string $token): bool {
        $stored = AppSession::get('csrf_token');
        return $stored !== null
            && hash_equals($stored, $token)
            && (time() - AppSession::get('csrf_time', 0)) < 3600;
    }

    public static function csrfField(): string {
        return '<input type="hidden" name="csrf_token" value="' . self::generateCSRF() . '">';
    }

    /* ── Sanitización ── */
    public static function sanitize(string $s): string {
        return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
    }

    /* ── Contraseñas ── */
    public static function hashPassword(string $p): string {
        return password_hash($p, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    }

    public static function verifyPassword(string $p, string $h): bool {
        return password_verify($p, $h);
    }

    public static function passwordStrength(string $p): array {
        $errors = [];
        if (strlen($p) < 8)              $errors[] = 'Mínimo 8 caracteres';
        if (!preg_match('/[A-Z]/', $p))   $errors[] = 'Al menos una mayúscula';
        if (!preg_match('/[a-z]/', $p))   $errors[] = 'Al menos una minúscula';
        if (!preg_match('/[0-9]/', $p))   $errors[] = 'Al menos un número';
        if (!preg_match('/[^A-Za-z0-9]/', $p)) $errors[] = 'Al menos un carácter especial';
        return $errors;
    }

    /* ── Validaciones ── */
    public static function validEmail(string $e): bool {
        return filter_var($e, FILTER_VALIDATE_EMAIL) !== false;
    }

    /* ── Tokens ── */
    public static function generateToken(int $len = 32): string {
        return bin2hex(random_bytes($len));
    }

    /* ── Cliente — sin warnings de claves inexistentes ── */
    public static function clientIP(): string {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $candidate = trim($parts[0]);
            if ($candidate !== '' && filter_var($candidate, FILTER_VALIDATE_IP)) {
                return $candidate;
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function userAgent(): string {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
    }

    /* ── Rate Limiting ── */
    public static function rateLimit(string $action, int $max = 5, int $window = 300): bool {
        $key = 'rl_' . $action . '_' . md5(self::clientIP());
        $data = AppSession::get($key, ['c' => 0, 't' => 0]);
        if (time() - $data['t'] > $window) $data = ['c' => 0, 't' => time()];
        if ($data['c'] >= $max) return false;
        $data['c']++;
        $data['t'] = time();
        AppSession::set($key, $data);
        return true;
    }

    public static function rateLimitRemaining(string $action, int $max = 5, int $window = 300): int {
        $key = 'rl_' . $action . '_' . md5(self::clientIP());
        $data = AppSession::get($key, ['c' => 0, 't' => 0]);
        if (time() - $data['t'] > $window) return $max;
        return max(0, $max - $data['c']);
    }

    /* ── Security Headers ── */
    public static function headers(): void {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}