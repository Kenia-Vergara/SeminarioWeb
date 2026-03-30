<?php
/** @var array $user */
/** @var array $stats */
/** @var array $logs */
 $iniciales = strtoupper(substr($user['full_name'], 0, 1)) . strtoupper(substr(trim(explode(' ', $user['full_name'])[1] ?? ''), 0, 1));
 $sevColors = ['info' => '#f97316', 'warning' => '#f59e0b', 'critical' => '#ef4444'];
?>
<div style="position:fixed;inset:0;display:flex;background:var(--bg);animation:fadeIn .5s ease">
    <nav style="width:260px;background:var(--bg-surface);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:24px 0;flex-shrink:0">
        <div style="display:flex;align-items:center;gap:12px;padding:0 24px;margin-bottom:36px">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:15px;color:#fff"><i class="fa-solid fa-code"></i></div>
            <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;color:var(--fg)">CodeCraft</div>
        </div>
        <div style="flex:1;display:flex;flex-direction:column;gap:2px;padding:0 12px">
            <button class="nav-item" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;color:var(--accent);font-size:13.5px;font-weight:500;cursor:pointer;border:none;background:rgba(249,115,22,.07);width:100%;text-align:left;font-family:inherit"><i class="fa-solid fa-grid-2" style="width:20px;text-align:center;font-size:14px"></i> Dashboard</button>
            <button class="nav-item" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;color:var(--fg-muted);font-size:13.5px;font-weight:500;cursor:pointer;border:none;background:none;width:100%;text-align:left;font-family:inherit;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.03)';this.style.color='var(--fg)'" onmouseout="this.style.background='none';this.style.color='var(--fg-muted)'"><i class="fa-solid fa-shield-halved" style="width:20px;text-align:center;font-size:14px"></i> Seguridad</button>
        </div>
        <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;align-items:center;gap:12px">
            <div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#2a1f0e,#1a1208);display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--accent);font-weight:700;flex-shrink:0"><?= htmlspecialchars($iniciales) ?></div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;font-weight:600;color:var(--fg);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($user['full_name']) ?></div>
                <div style="font-size:11px;color:var(--fg-dim)"><?= htmlspecialchars(ucfirst($user['role'])) ?> — <?= htmlspecialchars($user['department'] ?? 'Sin depto.') ?></div>
            </div>
            <a href="login.php?action=logout" style="background:none;border:none;color:var(--fg-dim);cursor:pointer;font-size:14px;padding:6px;border-radius:6px;transition:all .2s" onmouseover="this.style.color='var(--error)';this.style.background='var(--error-glow)'" onmouseout="this.style.color='var(--fg-dim)';this.style.background='none'" aria-label="Cerrar sesión"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
    </nav>

    <div style="flex:1;display:flex;flex-direction:column;overflow-y:auto;padding:32px 40px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:32px">
            <div>
                <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--fg)">Bienvenido, <?= htmlspecialchars(explode(' ', $user['full_name'])[0]) ?></h1>
                <p style="font-size:13px;color:var(--fg-muted);margin-top:4px"><?= htmlspecialchars($user['company_name']) ?> — Panel de control</p>
            </div>
            <div style="display:flex;gap:10px">
                <button style="width:40px;height:40px;border-radius:10px;border:1px solid var(--border);background:var(--bg-card);color:var(--fg-muted);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px"><i class="fa-solid fa-magnifying-glass"></i></button>
                <button style="width:40px;height:40px;border-radius:10px;border:1px solid var(--border);background:var(--bg-card);color:var(--fg-muted);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;position:relative"><i class="fa-solid fa-bell"></i><?php if ($stats['critical24h'] > 0): ?><span style="position:absolute;top:8px;right:8px;width:7px;height:7px;background:var(--error);border-radius:50%;border:1.5px solid var(--bg-card)"></span><?php endif; ?></button>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:32px">
            <?php
            $cards = [
                ['icon' => 'fa-users',          'color' => '#f97316', 'bg' => 'rgba(249,115,22,.1)',  'val' => $stats['totalUsers'],    'label' => 'Usuarios registrados'],
                ['icon' => 'fa-circle-check',   'color' => '#22c55e', 'bg' => 'rgba(34,197,94,.1)',   'val' => $stats['verifiedUsers'], 'label' => 'Cuentas verificadas'],
                ['icon' => 'fa-clock',          'color' => '#6366f1', 'bg' => 'rgba(99,102,241,.1)',  'val' => $stats['activeToday'],   'label' => 'Activos hoy'],
                ['icon' => 'fa-signal',         'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.1)',  'val' => $stats['activeSessions'],'label' => 'Sesiones activas'],
            ];
            foreach ($cards as $i => $c): ?>
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:22px 24px;animation:statIn .6s cubic-bezier(.16,1,.3,1) <?= $i*0.1 ?>s both;transition:all .3s" onmouseover="this.style.borderColor='rgba(249,115,22,.18)';this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border)';this.style.transform='none'">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
                    <div style="width:40px;height:40px;border-radius:10px;background:<?= $c['bg'] ?>;color:<?= $c['color'] ?>;display:flex;align-items:center;justify-content:center;font-size:16px"><i class="fa-solid <?= $c['icon'] ?>"></i></div>
                </div>
                <div style="font-family:'Space Grotesk',sans-serif;font-size:28px;font-weight:700;color:var(--fg);margin-bottom:4px"><?= $c['val'] ?></div>
                <div style="font-size:13px;color:var(--fg-muted)"><?= $c['label'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;animation:statIn .6s cubic-bezier(.16,1,.3,1) .4s both">
            <div style="font-family:'Space Grotesk',sans-serif;font-size:16px;font-weight:600;color:var(--fg);margin-bottom:20px;display:flex;align-items:center;justify-content:space-between">
                Bitácora de auditoría
                <?php if ($stats['critical24h'] > 0): ?><span style="font-size:12px;color:var(--error);font-weight:600;background:var(--error-glow);padding:3px 10px;border-radius:6px"><?= $stats['critical24h'] ?> críticos (24h)</span><?php endif; ?>
            </div>
            <?php if (empty($logs)): ?>
            <p style="color:var(--fg-dim);font-size:13px;text-align:center;padding:20px 0">Sin registros de auditoría aún.</p>
            <?php else: ?>
            <?php foreach ($logs as $log): ?>
            <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border)">
                <div style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:<?= $sevColors[$log['severity']] ?? '#f97316' ?>"></div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;color:var(--fg);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        <span style="font-weight:600;color:<?= $sevColors[$log['severity']] ?? 'var(--fg)' ?>"><?= htmlspecialchars($log['action']) ?></span>
                        — <?= htmlspecialchars($log['description'] ?: 'Sin descripción') ?>
                    </div>
                    <div style="font-size:11px;color:var(--fg-dim);margin-top:2px">
                        <?= htmlspecialchars($log['full_name'] ?? 'Sistema') ?>
                        &middot; <?= htmlspecialchars($log['ip_address'] ?? '') ?>
                        &middot; <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>