<?php
// ─── MODELO: AUDIT LOG ───────────────────────────────
class AuditLogModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function log(?int $uid, string $action, string $desc = '', string $sev = 'info'): void {
        $this->db->prepare(
            "INSERT INTO audit_logs (user_id,action,description,ip_address,user_agent,severity) 
             VALUES (?,?,?,?,?,?)"
        )->execute([$uid, $action, $desc, Security::clientIP(), Security::userAgent(), $sev]);
    }

    public function recent(int $n = 15): array {
        $s = $this->db->prepare(
            "SELECT al.*, u.full_name, u.email 
             FROM audit_logs al 
             LEFT JOIN users u ON al.user_id=u.id 
             ORDER BY al.created_at DESC LIMIT ?"
        );
        $s->execute([$n]);
        return $s->fetchAll();
    }

    public function byUser(int $uid, int $n = 5): array {
        $s = $this->db->prepare("SELECT * FROM audit_logs WHERE user_id=? ORDER BY created_at DESC LIMIT ?");
        $s->execute([$uid, $n]);
        return $s->fetchAll();
    }

    public function criticalCount24h(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM audit_logs 
             WHERE severity='critical' AND created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR)"
        )->fetchColumn();
    }
}
