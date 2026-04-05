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

    /** Últimos N registros (con JOIN a usuarios) */
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

    /** Registros paginados con filtros opcionales */
    public function getFiltered(int $limit, int $offset, string $severity = '', string $action = ''): array {
        $where = [];
        $params = [];

        if ($severity !== '') {
            $where[] = 'al.severity = ?';
            $params[] = $severity;
        }
        if ($action !== '') {
            $where[] = 'al.action = ?';
            $params[] = $action;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $params[] = $limit;
        $params[] = $offset;

        $s = $this->db->prepare(
            "SELECT al.*, u.full_name, u.email 
             FROM audit_logs al 
             LEFT JOIN users u ON al.user_id=u.id 
             {$whereClause}
             ORDER BY al.created_at DESC 
             LIMIT ? OFFSET ?"
        );
        $s->execute($params);
        return $s->fetchAll();
    }

    /** Total de registros con filtros (para paginación) */
    public function countFiltered(string $severity = '', string $action = ''): int {
        $where = [];
        $params = [];

        if ($severity !== '') {
            $where[] = 'severity = ?';
            $params[] = $severity;
        }
        if ($action !== '') {
            $where[] = 'action = ?';
            $params[] = $action;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $s = $this->db->prepare("SELECT COUNT(*) FROM audit_logs {$whereClause}");
        $s->execute($params);
        return (int)$s->fetchColumn();
    }

    /** Registros de un usuario específico */
    public function byUser(int $uid, int $n = 5): array {
        $s = $this->db->prepare("SELECT * FROM audit_logs WHERE user_id=? ORDER BY created_at DESC LIMIT ?");
        $s->execute([$uid, $n]);
        return $s->fetchAll();
    }

    /** Eventos críticos en las últimas 24h */
    public function criticalCount24h(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM audit_logs 
             WHERE severity='critical' AND created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR)"
        )->fetchColumn();
    }

    /** Warnings en las últimas 24h */
    public function warningCount24h(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM audit_logs 
             WHERE severity='warning' AND created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR)"
        )->fetchColumn();
    }

    /** Logins exitosos en las últimas 24h */
    public function loginSuccessCount24h(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM audit_logs 
             WHERE action='LOGIN_SUCCESS' AND created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR)"
        )->fetchColumn();
    }

    /** Logins fallidos en las últimas 24h */
    public function loginFailedCount24h(): int {
        return (int)$this->db->query(
            "SELECT COUNT(*) FROM audit_logs 
             WHERE action='LOGIN_FAILED' AND created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR)"
        )->fetchColumn();
    }

    /** Lista de acciones distintas para el filtro */
    public function distinctActions(): array {
        return $this->db->query(
            "SELECT DISTINCT action FROM audit_logs ORDER BY action ASC"
        )->fetchAll(PDO::FETCH_COLUMN);
    }
}
