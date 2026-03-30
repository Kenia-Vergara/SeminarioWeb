<?php
class OTPModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function generate(int $userId, string $purpose = 'verification'): string {
        /* Invalidar códigos anteriores del mismo propósito */
        $this->db->prepare("UPDATE otp_codes SET is_used = 1 WHERE user_id = ? AND purpose = ? AND is_used = 0")
            ->execute([$userId, $purpose]);

        $code = implode('', array_map(fn() => random_int(0, 9), range(1, OTP_LENGTH)));
        $expires = date('Y-m-d H:i:s', time() + OTP_EXPIRY_SEC);

        $this->db->prepare("INSERT INTO otp_codes (user_id, code, purpose, expires_at) VALUES (?, ?, ?, ?)")
            ->execute([$userId, $code, $purpose, $expires]);

        return $code;
    }

    public function verify(int $userId, string $code, string $purpose = 'verification'): array {
        $s = $this->db->prepare(
            "SELECT * FROM otp_codes WHERE user_id = ? AND purpose = ? AND is_used = 0 AND expires_at > NOW()
             ORDER BY created_at DESC LIMIT 1"
        );
        $s->execute([$userId, $purpose]);
        $row = $s->fetch();

        if (!$row) {
            return ['ok' => false, 'err' => 'No hay código activo. Solicita uno nuevo.'];
        }

        /* Incrementar intentos */
        $this->db->prepare("UPDATE otp_codes SET attempts = attempts + 1 WHERE id = ?")
            ->execute([$row['id']]);

        if ((int) $row['attempts'] + 1 >= OTP_MAX_ATTEMPTS) {
            $this->db->prepare("UPDATE otp_codes SET is_used = 1 WHERE id = ?")->execute([$row['id']]);
            return ['ok' => false, 'err' => 'Máximo de intentos alcanzado. Solicita un nuevo código.'];
        }

        if (!hash_equals($row['code'], $code)) {
            return ['ok' => false, 'err' => 'Código incorrecto.'];
        }

        /* Código válido — marcar como usado */
        $this->db->prepare("UPDATE otp_codes SET is_used = 1 WHERE id = ?")->execute([$row['id']]);
        return ['ok' => true, 'err' => null];
    }

    public function canResend(int $userId, string $purpose): bool {
        $s = $this->db->prepare(
            "SELECT created_at FROM otp_codes WHERE user_id = ? AND purpose = ? ORDER BY created_at DESC LIMIT 1"
        );
        $s->execute([$userId, $purpose]);
        $row = $s->fetch();
        return !$row || (time() - strtotime($row['created_at'])) >= 60;
    }

    public function cooldownSec(int $userId, string $purpose): int {
        $s = $this->db->prepare(
            "SELECT created_at FROM otp_codes WHERE user_id = ? AND purpose = ? ORDER BY created_at DESC LIMIT 1"
        );
        $s->execute([$userId, $purpose]);
        $row = $s->fetch();
        return $row ? max(0, 60 - (time() - strtotime($row['created_at']))) : 0;
    }
}