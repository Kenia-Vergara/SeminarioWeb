<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
        html, body { min-height: 100%; }
        .card { max-width: 650px; margin: 40px auto; padding: 40px; }
    </style>
</head>
<body>
    <div class="bg-layer" aria-hidden="true">
        <div class="grid-pattern"></div>
        <canvas id="particleCanvas"></canvas>
    </div>
    <div class="toast-container" id="toastBox"></div>

    <div class="card">
        <header style="margin-bottom:32px; text-align: center;">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:26px;font-weight:700;color:var(--fg);margin-bottom:8px">Crear cuenta</h1>
            <p style="font-size:14px;color:var(--fg-muted)">Únete a <?= APP_NAME ?> y comienza a gestionar tu empresa</p>
        </header>

        <form method="POST" action="?action=register" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

            <div class="form-row">
                <div class="field-group">
                    <label class="field-label">Empresa</label>
                    <input type="text" name="company_name" class="field-input no-icon" required>
                </div>
                <div class="field-group">
                    <label class="field-label">NIT / ID Fiscal</label>
                    <input type="text" name="company_nit" class="field-input no-icon" required>
                </div>
            </div>

            <div class="field-group">
                <label class="field-label">Nombre Completo</label>
                <input type="text" name="full_name" class="field-input no-icon" required>
            </div>

            <div class="form-row">
                <div class="field-group">
                    <label class="field-label">Email Corporativo</label>
                    <input type="email" name="email" class="field-input no-icon" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Departamento</label>
                    <select name="department" class="field-input no-icon" style="appearance: none; padding-right: 30px;">
                        <option value="Tecnología">Tecnología</option>
                        <option value="Operaciones">Operaciones</option>
                        <option value="RRHH">Recursos Humanos</option>
                        <option value="Finanzas">Finanzas</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="field-group">
                    <label class="field-label">Contraseña</label>
                    <input type="password" name="password" class="field-input no-icon" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Confirmar Contraseña</label>
                    <input type="password" name="confirm_password" class="field-input no-icon" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="height:52px; margin-top: 10px;">Crear cuenta</button>
        </form>

        <div class="divider"><span>O</span></div>
        <p style="text-align:center;font-size:13.5px;color:var(--fg-muted)">¿Ya tienes una cuenta? <a href="?action=login" class="link" style="font-weight:600">Iniciar sesión</a></p>
    </div>

    <script>
    function toast(t,ti,m){const ic={success:'fa-check',error:'fa-xmark'},e=document.createElement('div');e.className=`toast toast-${t}`;e.innerHTML=`<div class="toast-icon"><i class="fa-solid ${ic[t]}"></i></div><div class="toast-content"><div class="toast-title">${ti}</div><div class="toast-message">${m}</div></div><button class="toast-close"><i class="fa-solid fa-xmark"></i></button>`;const rm=()=>{e.classList.add('removing');setTimeout(()=>e.remove(),300)};e.querySelector('.toast-close').onclick=rm;setTimeout(rm,6000);document.getElementById('toastBox').appendChild(e)}
    <?php $errs = AppSession::get('form_errors'); if($errs): foreach($errs as $e): ?>toast('error','Error','<?= addslashes($e) ?>');<?php endforeach; AppSession::remove('form_errors'); endif; ?>
    </script>
</body>
</html>
