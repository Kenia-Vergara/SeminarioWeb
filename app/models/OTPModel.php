<?php
// ─── MODELO: OTP ─────────────────────────────────────
class OTPModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function generate(int $uid, string $purpose = 'verification'): string {
        $this->db->prepare("UPDATE otp_codes SET is_used=1 WHERE user_id=? AND purpose=? AND is_used=0")
            ->execute([$uid, $purpose]);
        $code = implode('', array_map(fn() => random_int(0, 9), range(1, OTP_LENGTH)));
        $exp = date('Y-m-d H:i:s', time() + OTP_EXPIRY_SEC);
        $this->db->prepare("INSERT INTO otp_codes (user_id,code,purpose,expires_at) VALUES (?,?,?,?)")
            ->execute([$uid, $code, $purpose, $exp]);
        return $code;
    }

    public function verify(int $uid, string $code, string $purpose = 'verification'): array {
        $s = $this->db->prepare(
            "SELECT * FROM otp_codes 
             WHERE user_id=? AND purpose=? AND is_used=0 AND expires_at>NOW() 
             ORDER BY created_at DESC LIMIT 1"
        );
        $s->execute([$uid, $purpose]);
        $row = $s->fetch();
        if (!$row) {
            return ['ok' => false, 'err' => 'No hay código activo. Solicite uno nuevo.'];
        }
        // Verificar el código PRIMERO antes de contar intentos
        if (!hash_equals($row['code'], $code)) {
            // Incrementar intentos solo cuando el código es incorrecto
            $this->db->prepare("UPDATE otp_codes SET attempts=attempts+1 WHERE id=?")->execute([$row['id']]);
            $newAttempts = (int)$row['attempts'] + 1;
            if ($newAttempts >= OTP_MAX_ATTEMPTS) {
                $this->db->prepare("UPDATE otp_codes SET is_used=1 WHERE id=?")->execute([$row['id']]);
                return ['ok' => false, 'err' => 'Máximo de intentos alcanzado. Solicite un nuevo código.'];
            }
            $rem = OTP_MAX_ATTEMPTS - $newAttempts;
            return ['ok' => false, 'err' => "Código incorrecto. {$rem} intento(s) restante(s)."];
        }
        // Código correcto: marcar como usado
        $this->db->prepare("UPDATE otp_codes SET is_used=1 WHERE id=?")->execute([$row['id']]);
        return ['ok' => true, 'err' => null];
    }

    public function canResend(int $uid, string $purpose): bool {
        $s = $this->db->prepare(
            "SELECT created_at FROM otp_codes 
             WHERE user_id=? AND purpose=? 
             ORDER BY created_at DESC LIMIT 1"
        );
        $s->execute([$uid, $purpose]);
        $r = $s->fetch();
        return !$r || (time() - strtotime($r['created_at'])) >= 60;
    }

    public function cooldownSec(int $uid, string $purpose): int {
        $s = $this->db->prepare(
            "SELECT created_at FROM otp_codes 
             WHERE user_id=? AND purpose=? 
             ORDER BY created_at DESC LIMIT 1"
        );
        $s->execute([$uid, $purpose]);
        $r = $s->fetch();
        return $r ? max(0, 60 - (time() - strtotime($r['created_at']))) : 0;
    }
}
