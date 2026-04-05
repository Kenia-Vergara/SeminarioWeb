<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Solutions — Panel de Auditoría</title>
    <meta name="description" content="Centro de monitoreo de accesos y eventos de seguridad de Apex Solutions.">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>

        /* ── Sidebar brand ── */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 14px;
            color: var(--fg);
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--dash-border);
        }
        .brand-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #A855F7, #7C3AED);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Nav label ── */
        .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--fg-dim);
            letter-spacing: 1.2px;
            margin: 18px 0 8px 14px;
        }

        /* ── Métrica cards ── */
        .metric-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--fg);
            line-height: 1;
            margin-bottom: 4px;
        }
        .metric-label {
            font-size: 12px;
            color: var(--fg-muted);
            margin-bottom: 12px;
        }
        .metric-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            margin-bottom: 14px;
        }

        /* ── Tabla de auditoría ── */
        .audit-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .audit-table th {
            text-align: left;
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 600;
            color: var(--fg-dim);
            letter-spacing: 0.8px;
            border-bottom: 1px solid var(--dash-border);
            white-space: nowrap;
        }
        .audit-table td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(39,39,42,0.6);
            vertical-align: middle;
        }
        .audit-table tr:last-child td { border-bottom: none; }
        .audit-table tbody tr:hover { background: rgba(255,255,255,0.02); }

        /* ── Acción badge ── */
        .action-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            white-space: nowrap;
        }

        /* ── Paginación ── */
        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .page-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            color: var(--fg-muted);
            border: 1px solid var(--dash-border);
            background: var(--dash-surface);
            transition: var(--transition);
        }
        .page-btn:hover { border-color: var(--dash-accent); color: var(--dash-accent); }
        .page-btn.active { background: var(--dash-accent); color: #fff; border-color: var(--dash-accent); }
        .page-btn.disabled { opacity: 0.3; pointer-events: none; }

        /* ── Filtros ── */
        .filter-select {
            background: var(--dash-surface-2);
            border: 1px solid var(--dash-border);
            border-radius: 7px;
            padding: 7px 12px;
            color: var(--fg);
            font-size: 12.5px;
            font-family: inherit;
            outline: none;
            cursor: pointer;
            transition: var(--transition);
        }
        .filter-select:focus { border-color: var(--dash-accent); }

        /* ── Avatar usuario ── */
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #A855F7, #7C3AED);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        /* ── IP tag ── */
        .ip-tag {
            font-family: 'Courier New', monospace;
            font-size: 11.5px;
            color: var(--fg-dim);
            background: var(--dash-surface-2);
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* ── Empty state ── */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--fg-dim);
        }

        /* Colores de acción por tipo */
        .ac-login-success { background:rgba(34,197,94,0.08); color:#4ADE80; }
        .ac-login-failed  { background:rgba(239,68,68,0.09); color:#F87171; }
        .ac-otp           { background:rgba(245,158,11,0.08); color:#FBBF24; }
        .ac-logout        { background:rgba(99,102,241,0.09); color:#818CF8; }
        .ac-register      { background:rgba(79,209,197,0.08); color:#4FD1C5; }
        .ac-default       { background:rgba(161,161,170,0.08); color:#A1A1AA; }
    </style>
</head>

<body class="dash-layout">

    <!-- ════ SIDEBAR ════ -->
    <aside class="sidebar" role="navigation" aria-label="Menú principal">
        <div>
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-shield-halved" style="color:#fff;font-size:15px;"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;letter-spacing:0.3px;">Apex Solutions</div>
                    <div style="font-size:10px;color:var(--fg-dim);font-weight:400;">Security Portal</div>
                </div>
            </div>

            <div class="nav-label">GENERAL</div>
            <a href="?action=dashboard" class="nav-item active" id="nav-dashboard">
                <i class="fa-solid fa-shield-halved"></i> Auditoría
            </a>

            <div class="nav-label">CUENTA</div>
            <div class="nav-item" style="cursor:default;">
                <i class="fa-solid fa-user-circle"></i>
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--fg);"><?= htmlspecialchars($user['full_name']) ?></div>
                    <div style="font-size:11px;color:var(--fg-dim);"><?= htmlspecialchars($user['role']) ?></div>
                </div>
            </div>
        </div>

        <div>
            <!-- Info del usuario logueado -->
            <div style="background:var(--dash-surface-2);border:1px solid var(--dash-border);border-radius:10px;padding:14px;margin-bottom:16px;">
                <div style="font-size:10px;color:var(--fg-dim);margin-bottom:4px;font-weight:600;letter-spacing:0.5px;">SESIÓN ACTIVA</div>
                <div style="font-size:12px;color:var(--fg-muted);word-break:break-all;"><?= htmlspecialchars($user['email']) ?></div>
                <div style="font-size:11px;color:var(--fg-dim);margin-top:4px;"><?= htmlspecialchars($user['department'] ?? '—') ?></div>
            </div>
            <a href="?action=logout" class="nav-item" style="color:#F87171;" id="nav-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
            </a>
        </div>
    </aside>

    <!-- ════ CONTENIDO ════ -->
    <main class="content-area" role="main">

        <!-- Header -->
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
            <div>
                <h1 style="font-size:22px;font-weight:700;margin-bottom:4px;">Panel de Auditoría</h1>
                <p style="font-size:13px;color:var(--fg-muted);">
                    Monitoreo de accesos y eventos de seguridad &mdash;
                    <span style="color:var(--fg-dim);"><?= date('d M Y, H:i') ?></span>
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:8px;height:8px;background:var(--color-success);border-radius:50%;" class="pulse"></div>
                <span style="font-size:12px;color:var(--fg-muted);"><?= $activeSessions ?> sesión<?= $activeSessions !== 1 ? 'es' : '' ?> activa<?= $activeSessions !== 1 ? 's' : '' ?></span>
            </div>
        </div>

        <!-- ── Tarjetas de métricas ── -->
        <div class="grid-row" style="grid-template-columns:repeat(4,1fr);">

            <div class="stat-card">
                <div class="metric-icon" style="background:rgba(168,85,247,0.1);">
                    <i class="fa-solid fa-users" style="color:#A855F7;"></i>
                </div>
                <div class="metric-value"><?= number_format($totalUsers) ?></div>
                <div class="metric-label">Usuarios Registrados</div>
                <div style="font-size:11.5px;color:var(--fg-dim);">
                    <i class="fa-solid fa-circle-check" style="color:var(--color-success);"></i>
                    <?= $verifiedUsers ?> verificados
                </div>
            </div>

            <div class="stat-card">
                <div class="metric-icon" style="background:rgba(79,209,197,0.1);">
                    <i class="fa-solid fa-right-to-bracket" style="color:#4FD1C5;"></i>
                </div>
                <div class="metric-value"><?= $loginSuccess24h ?></div>
                <div class="metric-label">Logins Exitosos (24h)</div>
                <div style="font-size:11.5px;color:var(--fg-dim);">
                    <i class="fa-solid fa-calendar-day" style="color:#4FD1C5;"></i>
                    <?= $activeToday ?> usuario<?= $activeToday !== 1 ? 's' : '' ?> hoy
                </div>
            </div>

            <div class="stat-card">
                <div class="metric-icon" style="background:rgba(245,158,11,0.1);">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#F59E0B;"></i>
                </div>
                <div class="metric-value"><?= $loginFailed24h ?></div>
                <div class="metric-label">Intentos Fallidos (24h)</div>
                <div style="font-size:11.5px;color:var(--fg-dim);">
                    <i class="fa-solid fa-shield" style="color:#F59E0B;"></i>
                    <?= $warning24h ?> warning<?= $warning24h !== 1 ? 's' : '' ?> registrado<?= $warning24h !== 1 ? 's' : '' ?>
                </div>
            </div>

            <div class="stat-card">
                <div class="metric-icon" style="background:rgba(239,68,68,0.1);">
                    <i class="fa-solid fa-circle-exclamation" style="color:#EF4444;"></i>
                </div>
                <div class="metric-value"><?= $critical24h ?></div>
                <div class="metric-label">Eventos Críticos (24h)</div>
                <div style="font-size:11.5px;color:var(--fg-dim);">
                    <?php if ($critical24h > 0): ?>
                    <i class="fa-solid fa-bell" style="color:#EF4444;" class="pulse"></i>
                    <span style="color:#F87171;">Requiere atención</span>
                    <?php else: ?>
                    <i class="fa-solid fa-check" style="color:var(--color-success);"></i>
                    Sin incidentes
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ── Tabla de auditoría ── -->
        <div class="stat-card">
            <!-- Header de la tabla -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
                <div>
                    <div style="font-size:15px;font-weight:700;">Registro de Eventos</div>
                    <div style="font-size:12px;color:var(--fg-dim);margin-top:2px;">
                        <?= number_format($totalLogs) ?> evento<?= $totalLogs !== 1 ? 's' : '' ?> en total
                    </div>
                </div>

                <!-- Filtros -->
                <form method="GET" action="" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;" id="filterForm">
                    <input type="hidden" name="action" value="dashboard">
                    <select name="severity" class="filter-select" onchange="this.form.submit()" id="filter-severity">
                        <option value="">Todas las severidades</option>
                        <option value="info"     <?= $filterSev === 'info'     ? 'selected' : '' ?>>Info</option>
                        <option value="warning"  <?= $filterSev === 'warning'  ? 'selected' : '' ?>>Warning</option>
                        <option value="critical" <?= $filterSev === 'critical' ? 'selected' : '' ?>>Critical</option>
                    </select>

                    <select name="action_filter" class="filter-select" onchange="this.form.submit()" id="filter-action">
                        <option value="">Todas las acciones</option>
                        <?php foreach ($distinctActions as $act): ?>
                        <option value="<?= htmlspecialchars($act) ?>" <?= $filterAction === $act ? 'selected' : '' ?>>
                            <?= htmlspecialchars($act) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <?php if ($filterSev || $filterAction): ?>
                    <a href="?action=dashboard" style="font-size:12px;color:var(--fg-muted);text-decoration:none;padding:7px 10px;border:1px solid var(--dash-border);border-radius:7px;transition:var(--transition);"
                       onmouseover="this.style.borderColor='var(--dash-accent)'" onmouseout="this.style.borderColor='var(--dash-border)'">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Tabla -->
            <?php if (empty($logs)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-inbox" style="font-size:40px;margin-bottom:12px;"></i>
                <div style="font-size:15px;font-weight:600;margin-bottom:6px;">Sin registros</div>
                <div style="font-size:13px;">No hay eventos que coincidan con los filtros aplicados.</div>
            </div>
            <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="audit-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Descripción</th>
                            <th>IP</th>
                            <th>Severidad</th>
                            <th>Fecha / Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $i => $log):
                            // Determinar clase de acción
                            $action = $log['action'] ?? '';
                            $actionClass = match(true) {
                                str_contains($action, 'LOGIN_SUCCESS')   => 'ac-login-success',
                                str_contains($action, 'LOGIN_FAILED') || str_contains($action, 'LOGIN_BLOCKED') => 'ac-login-failed',
                                str_contains($action, 'OTP')             => 'ac-otp',
                                str_contains($action, 'LOGOUT')          => 'ac-logout',
                                str_contains($action, 'REGISTER') || str_contains($action, 'VERIFIED') => 'ac-register',
                                default                                  => 'ac-default',
                            };

                            // Ícono de acción
                            $actionIcon = match(true) {
                                str_contains($action, 'LOGIN_SUCCESS')   => 'fa-right-to-bracket',
                                str_contains($action, 'LOGIN_FAILED') || str_contains($action, 'LOGIN_BLOCKED') => 'fa-ban',
                                str_contains($action, 'OTP')             => 'fa-key',
                                str_contains($action, 'LOGOUT')          => 'fa-right-from-bracket',
                                str_contains($action, 'REGISTER')        => 'fa-user-plus',
                                str_contains($action, 'VERIFIED')        => 'fa-circle-check',
                                default                                  => 'fa-circle-dot',
                            };

                            // Badge de severidad
                            $sevClass = match($log['severity']) {
                                'critical' => 'badge-critical',
                                'warning'  => 'badge-warning',
                                default    => 'badge-info',
                            };
                            $sevIcon = match($log['severity']) {
                                'critical' => 'fa-circle-exclamation',
                                'warning'  => 'fa-triangle-exclamation',
                                default    => 'fa-circle-info',
                            };

                            $rowNum = ($currentPage - 1) * $perPage + $i + 1;
                            $initials = $log['full_name']
                                ? strtoupper(substr($log['full_name'], 0, 1))
                                : '?';
                            $ts = strtotime($log['created_at']);
                        ?>
                        <tr>
                            <td style="color:var(--fg-dim);font-size:12px;width:40px;"><?= $rowNum ?></td>

                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="user-avatar"><?= $initials ?></div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600;color:var(--fg);">
                                            <?= htmlspecialchars($log['full_name'] ?? 'Desconocido') ?>
                                        </div>
                                        <div style="font-size:11px;color:var(--fg-dim);">
                                            <?= htmlspecialchars($log['email'] ?? '—') ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="action-badge <?= $actionClass ?>">
                                    <i class="fa-solid <?= $actionIcon ?>"></i>
                                    <?= htmlspecialchars($action) ?>
                                </span>
                            </td>

                            <td style="max-width:220px;">
                                <span style="font-size:12.5px;color:var(--fg-muted);" title="<?= htmlspecialchars($log['description'] ?? '') ?>">
                                    <?= htmlspecialchars(mb_strimwidth($log['description'] ?? '—', 0, 55, '…')) ?>
                                </span>
                            </td>

                            <td>
                                <span class="ip-tag"><?= htmlspecialchars($log['ip_address'] ?? '—') ?></span>
                            </td>

                            <td>
                                <span class="badge <?= $sevClass ?>">
                                    <i class="fa-solid <?= $sevIcon ?>"></i>
                                    <?= ucfirst($log['severity']) ?>
                                </span>
                            </td>

                            <td style="white-space:nowrap;">
                                <div style="font-size:13px;color:var(--fg);"><?= date('d/m/Y', $ts) ?></div>
                                <div style="font-size:11px;color:var(--fg-dim);"><?= date('H:i:s', $ts) ?></div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($totalPages > 1): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;padding-top:16px;border-top:1px solid var(--dash-border);">
                <span style="font-size:12px;color:var(--fg-dim);">
                    Mostrando <?= (($currentPage-1)*$perPage)+1 ?>–<?= min($currentPage*$perPage,$totalLogs) ?> de <?= number_format($totalLogs) ?>
                </span>

                <div class="pagination">
                    <?php
                    $baseUrl = '?action=dashboard'
                        . ($filterSev    ? '&severity='     . urlencode($filterSev)    : '')
                        . ($filterAction ? '&action_filter='. urlencode($filterAction) : '');
                    ?>

                    <a href="<?= $baseUrl ?>&page=<?= max(1,$currentPage-1) ?>"
                       class="page-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <i class="fa-solid fa-chevron-left" style="font-size:11px;"></i>
                    </a>

                    <?php
                    $start = max(1, $currentPage - 2);
                    $end   = min($totalPages, $currentPage + 2);
                    if ($start > 1): ?>
                        <a href="<?= $baseUrl ?>&page=1" class="page-btn">1</a>
                        <?php if ($start > 2): ?><span style="color:var(--fg-dim);padding:0 4px;">…</span><?php endif; ?>
                    <?php endif; ?>

                    <?php for ($p = $start; $p <= $end; $p++): ?>
                    <a href="<?= $baseUrl ?>&page=<?= $p ?>"
                       class="page-btn <?= $p === $currentPage ? 'active' : '' ?>"><?= $p ?></a>
                    <?php endfor; ?>

                    <?php if ($end < $totalPages): ?>
                        <?php if ($end < $totalPages - 1): ?><span style="color:var(--fg-dim);padding:0 4px;">…</span><?php endif; ?>
                        <a href="<?= $baseUrl ?>&page=<?= $totalPages ?>" class="page-btn"><?= $totalPages ?></a>
                    <?php endif; ?>

                    <a href="<?= $baseUrl ?>&page=<?= min($totalPages,$currentPage+1) ?>"
                       class="page-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <i class="fa-solid fa-chevron-right" style="font-size:11px;"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>