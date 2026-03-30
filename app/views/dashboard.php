<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codecraft — Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
        /* Estilos específicos para el dashboard tipo Codecraft */
        .sidebar {
            justify-content: space-between;
            border-right: 1px solid #1E1E22;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1px;
            color: #fff;
            margin-bottom: 40px;
        }

        .sidebar-brand i {
            background: #fff;
            color: #000;
            padding: 4px;
            border-radius: 4px;
            font-size: 12px;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: #3F3F46;
            letter-spacing: 1px;
            margin: 20px 0 10px;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .stat-change {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .change-up {
            background: rgba(79, 209, 197, 0.1);
            color: #4FD1C5;
        }

        .change-down {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .chart-placeholder {
            height: 100px;
            display: flex;
            align-items: flex-end;
            gap: 4px;
            margin-top: 10px;
        }

        .bar {
            width: 100%;
            border-radius: 2px 2px 0 0;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--dash-border);
        }

        .transaction-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .transaction-icon {
            width: 32px;
            height: 32px;
            background: #18181B;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
    </style>
</head>

<body class="dash-layout">
    <aside class="sidebar">
        <div>
            <div class="sidebar-brand">
                <i class="fa-solid fa-wallet"></i> Codecraft
            </div>

            <div class="nav-label">GENERAL</div>
            <a href="#" class="nav-item active"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
        </div>

        <div>
            <div style="background: #18181B; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
                <div style="font-size: 10px; color: #52525B; margin-bottom: 4px;">MONTHLY CASHBACK</div>
                <div style="font-size: 16px; font-weight: 700;">$215.50</div>
            </div>
            <a href="?action=logout" class="nav-item" style="color: #EF4444;"><i
                    class="fa-solid fa-right-from-bracket"></i> Log out</a>
        </div>
    </aside>

    <main class="content-area">
        <div class="grid-row">
            <div class="stat-card">
                <div class="stat-header">
                    <span style="font-size: 12px; color: #52525B;">TOTAL SPENDINGS</span>
                    <span style="font-size: 10px; color: #52525B;">THIS WEEK <i
                            class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="stat-value">$832.80 <span class="stat-change change-up">+2%</span></div>
                <div class="chart-placeholder">
                    <div class="bar" style="height: 35%; background: #6366F1;"></div>
                    <div class="bar" style="height: 12%; background: #FBBF24;"></div>
                    <div class="bar" style="height: 20%; background: #4AD991;"></div>
                    <div class="bar" style="height: 9%; background: #EC4899;"></div>
                    <div class="bar" style="height: 24%; background: #EF4444;"></div>
                    <div class="bar" style="height: 15%; background: #8B5CF6;"></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span style="font-size: 12px; color: #52525B;">SAVINGS</span>
                    <span style="font-size: 10px; color: #52525B;">THIS YEAR <i
                            class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="stat-value">$2,512.40 <span class="stat-change change-down">-2%</span></div>
                <div style="height: 100px; display: flex; align-items: flex-end; padding-top: 20px;">
                    <svg viewBox="0 0 100 20" style="width: 100%; height: 60px;">
                        <path d="M0,20 L10,15 L20,18 L30,12 L40,14 L50,8 L60,10 L70,5 L80,7 L90,2 L100,20 Z"
                            fill="rgba(239, 68, 68, 0.1)" stroke="#EF4444" stroke-width="0.5" />
                    </svg>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span style="font-size: 12px; color: #52525B;">INVESTMENTS</span>
                    <span style="font-size: 10px; color: #52525B;">THIS YEAR <i
                            class="fa-solid fa-chevron-down"></i></span>
                </div>
                <div class="stat-value">$1,215.25 <span class="stat-change change-up">+4%</span></div>
                <div style="height: 100px; display: flex; align-items: flex-end; padding-top: 20px;">
                    <svg viewBox="0 0 100 20" style="width: 100%; height: 60px;">
                        <path d="M0,20 L10,18 L20,16 L30,20 L40,15 L50,12 L60,14 L70,10 L80,8 L90,5 L100,4 Z"
                            fill="rgba(79, 209, 197, 0.1)" stroke="#4FD1C5" stroke-width="0.5" />
                    </svg>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 350px 1fr; gap: 24px;">
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <span style="font-size: 14px; font-weight: 700;">Transactions</span>
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 12px; color: #52525B;"></i>
                </div>

                <div style="font-size: 10px; font-weight: 700; color: #3F3F46; margin-bottom: 15px;">TODAY</div>
                <div class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-icon"><i class="fa-solid fa-user" style="color: #4AD991;"></i></div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600;">Simon Pegg</div>
                            <div style="font-size: 10px; color: #52525B;">Jul 20, 6:22 PM</div>
                        </div>
                    </div>
                    <div style="font-size: 13px; font-weight: 700; color: #4AD991;">+$44.00</div>
                </div>

                <div class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-icon"><i class="fa-brands fa-apple"></i></div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600;">Apple Music</div>
                            <div style="font-size: 10px; color: #52525B;">Jul 20, 12:30 PM</div>
                        </div>
                    </div>
                    <div style="font-size: 13px; font-weight: 700;">-$9.99</div>
                </div>

                <div style="font-size: 10px; font-weight: 700; color: #3F3F46; margin: 25px 0 15px;">YESTERDAY</div>
                <div class="transaction-item">
                    <div class="transaction-info">
                        <div class="transaction-icon"><i class="fa-solid fa-cart-shopping" style="color: #6366F1;"></i>
                        </div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600;">7-Eleven</div>
                            <div style="font-size: 10px; color: #52525B;">Jul 19, 2:56 PM</div>
                        </div>
                    </div>
                    <div style="font-size: 13px; font-weight: 700;">-$5.18</div>
                </div>
            </div>

            <div class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                    <span style="font-size: 16px; font-weight: 700;">$9,340.80 Spent</span>
                    <div style="display: flex; gap: 8px; background: #18181B; padding: 4px; border-radius: 8px;">
                        <span style="font-size: 10px; padding: 4px 8px; border-radius: 4px; color: #52525B;">Day</span>
                        <span style="font-size: 10px; padding: 4px 8px; border-radius: 4px; color: #52525B;">Week</span>
                        <span
                            style="font-size: 10px; padding: 4px 8px; border-radius: 4px; color: #52525B;">Month</span>
                        <span
                            style="font-size: 10px; padding: 4px 8px; border-radius: 4px; background: #000; color: #fff;">Year</span>
                    </div>
                </div>

                <div style="height: 300px; position: relative; border-bottom: 1px dashed #1E1E22; margin-top: 60px;">
                    <!-- Gráfico de barras apiladas (Estilo manual) -->
                    <div
                        style="display: flex; align-items: flex-end; justify-content: space-around; height: 100%; padding: 0 40px;">
                        <?php for ($i = 0; $i < 7; $i++):
                            $h = rand(40, 95); ?>
                            <div style="width: 12px; height: <?= $h ?>%; display: flex; flex-direction: column; gap: 2px;">
                                <div style="flex: 1; min-height: 10px; background: #8B5CF6; border-radius: 2px;"></div>
                                <div style="flex: 0.5; min-height: 5px; background: #EC4899; border-radius: 2px;"></div>
                                <div style="flex: 0.3; min-height: 3px; background: #FBBF24; border-radius: 2px;"></div>
                                <div style="flex: 0.8; min-height: 8px; background: #4AD991; border-radius: 2px;"></div>
                                <div style="flex: 0.4; min-height: 4px; background: #6366F1; border-radius: 2px;"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <!-- Línea de promedio -->
                    <div style="position: absolute; top: 150px; width: 100%; border-top: 1px dashed #52525B;"></div>
                    <div
                        style="position: absolute; top: 150px; left: 0; background: #000; padding: 2px 6px; font-size: 10px; transform: translateY(-50%); border: 1px solid #1E1E22;">
                        $183.15</div>
                </div>
                <div
                    style="display: flex; justify-content: space-around; padding-top: 10px; font-size: 10px; color: #52525B;">
                    <span>JAN</span><span>FEB</span><span>MAR</span><span>APR</span><span>MAY</span><span>JUN</span><span>JUL</span>
                </div>
            </div>
        </div>
    </main>
</body>

</html>