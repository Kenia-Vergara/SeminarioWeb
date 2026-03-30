<?php
// ─── MODELO: USER ────────────────────────────────────
class UserModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array {
        $s = $this->db->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $s->execute([$email]);
        return $s->fetch() ?: null;
    }

    public function create(array $d): int {
        $s = $this->db->prepare(
            "INSERT INTO users (company_name,company_nit,full_name,email,password,role,department) 
             VALUES (?,?,?,?,?,?,?)"
        );
        $s->execute([
            $d['company_name'], $d['company_nit'], $d['full_name'], $d['email'],
            Security::hashPassword($d['password']),
            $d['role'] ?? 'employee', $d['department'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function setVerified(int $id, bool $v): bool {
        return $this->db->prepare("UPDATE users SET is_verified=? WHERE id=?")->execute([$v ? 1 : 0, $id]);
    }

    public function incFailed(int $id): void {
        $this->db->prepare("UPDATE users SET failed_login_attempts=failed_login_attempts+1 WHERE id=?")->execute([$id]);
        $u = $this->findById($id);
        if ($u && $u['failed_login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            $this->lock($id);
        }
    }

    public function resetFailed(int $id): void {
        $this->db->prepare("UPDATE users SET failed_login_attempts=0, locked_until=NULL WHERE id=?")->execute([$id]);
    }

    public function lock(int $id): void {
        $this->db->prepare("UPDATE users SET locked_until=DATE_ADD(NOW(), INTERVAL ? MINUTE) WHERE id=?")
            ->execute([LOCKOUT_MINUTES, $id]);
    }

    public function isLocked(int $id): bool {
        $u = $this->findById($id);
        return $u && $u['locked_until'] && strtotime($u['locked_until']) > time();
    }

    public function lockMinutesLeft(int $id): int {
        $u = $this->findById($id);
        if (!$u || !$u['locked_until']) return 0;
        return max(0, (int)ceil((strtotime($u['locked_until']) - time()) / 60));
    }

    public function updateLastLogin(int $id): void {
        $this->db->prepare("UPDATE users SET last_login=NOW() WHERE id=?")->execute([$id]);
    }

    public function countAll(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function countVerified(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users WHERE is_verified=1")->fetchColumn();
    }

    public function countActiveToday(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users WHERE last_login >= CURDATE()")->fetchColumn();
    }
}
