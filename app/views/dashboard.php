<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
        .page-header { background: #121212; border-bottom: 1px solid var(--border); padding: 50px 0; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: -40px 0 40px; }
        .stat-card { background: #1a1a1a; padding: 25px; border-radius: 12px; border: 1px solid var(--border); position: relative; z-index: 2; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .log-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .log-table th { text-align: left; background: #222; padding: 12px; font-size: 12px; color: var(--fg-dim); text-transform: uppercase; }
        .log-table td { padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px; }
        .severity-info { color: #f97316; }
        .severity-warning { color: #fcc419; }
        .severity-critical { color: #ff5252; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="bg-layer" aria-hidden="true">
        <div class="grid-pattern"></div>
    </div>

    <header class="page-header">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 40px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-family:'Space Grotesk',sans-serif;"><?= APP_NAME ?></h1>
                <p style="color:var(--fg-muted); font-size: 14px;">Bienvenido, <?= htmlspecialchars($user['full_name']) ?></p>
            </div>
            <a href="?action=logout" class="btn btn-ghost" style="height: 40px; padding: 0 20px;">Cerrar Sesión</a>
        </div>
    </header>

    <main style="max-width: 1200px; margin: 0 auto; padding: 40px;">
        <div class="stats-grid">
            <div class="stat-card">
                <div style="font-size: 12px; color: var(--fg-muted); margin-bottom: 5px;">USUARIOS TOTALES</div>
                <div style="font-size: 32px; font-weight: bold; font-family:'Space Grotesk'; color:var(--accent);"><?= $totalUsers ?></div>
            </div>
            <div class="stat-card">
                <div style="font-size: 12px; color: var(--fg-muted); margin-bottom: 5px;">VERIFICADOS</div>
                <div style="font-size: 32px; font-weight: bold; font-family:'Space Grotesk'; color:var(--accent);"><?= $verifiedUsers ?></div>
            </div>
            <div class="stat-card">
                <div style="font-size: 12px; color: var(--fg-muted); margin-bottom: 5px;">SESIONES ACTIVAS</div>
                <div style="font-size: 32px; font-weight: bold; font-family:'Space Grotesk'; color:var(--accent);"><?= $activeSessions ?></div>
            </div>
            <div class="stat-card">
                <div style="font-size: 12px; color: var(--fg-muted); margin-bottom: 5px;">ALERTAS CRÍTICAS (24h)</div>
                <div style="font-size: 32px; font-weight: bold; font-family:'Space Grotesk'; color:<?= $critical24h > 0 ? 'var(--error)' : 'var(--accent)' ?>;"><?= $critical24h ?></div>
            </div>
        </div>

        <div class="card" style="padding: 30px;">
            <h3 style="font-family:'Space Grotesk',sans-serif; margin-bottom: 20px;">Bitácora de Auditoría Reciente</h3>
            <div style="overflow-x: auto;">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Acción</th>
                            <th>Usuario</th>
                            <th>IP</th>
                            <th>Gravedad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($logs as $log): ?>
                            <tr>
                                <td><?= date('d/m H:i', strtotime($log['created_at'])) ?></td>
                                <td><strong><?= htmlspecialchars($log['action']) ?></strong></td>
                                <td><?= htmlspecialchars($log['full_name'] ?? 'Guest') ?></td>
                                <td style="font-family: monospace; font-size: 11px;"><?= htmlspecialchars($log['ip_address']) ?></td>
                                <td class="severity-<?= $log['severity'] ?>"><?= strtoupper($log['severity']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
