<?php
class UserSessionModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $userId, string $token, int $lifetime = SESSION_LIFETIME): int {
        $expires = date('Y-m-d H:i:s', time() + $lifetime);
        $this->db->prepare(
            "INSERT INTO user_sessions (user_id, session_token, ip_address, user_agent, expires_at)
             VALUES (?, ?, ?, ?, ?)"
        )->execute([$userId, $token, Security::clientIP(), Security::userAgent(), $expires]);
        return (int) $this->db->lastInsertId();
    }

    public function validate(string $token): ?array {
        $s = $this->db->prepare(
            "SELECT us.*, u.full_name, u.email, u.role, u.company_name, u.department
             FROM user_sessions us
             JOIN users u ON us.user_id = u.id
             WHERE us.session_token = ? AND us.is_active = 1 AND us.expires_at > NOW()
             LIMIT 1"
        );
        $s->execute([$token]);
        return $s->fetch() ?: null;
    }

    public function deactivate(string $token): void {
        $this->db->prepare("UPDATE user_sessions SET is_active = 0 WHERE session_token = ?")
            ->execute([$token]);
    }

    public function deactivateAllByUser(int $userId): void {
        $this->db->prepare("UPDATE user_sessions SET is_active = 0 WHERE user_id = ?")
            ->execute([$userId]);
    }

    public function activeCount(): int {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM user_sessions WHERE is_active = 1 AND expires_at > NOW()"
        )->fetchColumn();
    }
}