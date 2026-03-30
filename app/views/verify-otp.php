<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Verificación</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
        .card { max-width: 450px; margin: 100px auto; padding: 40px; text-align: center; }
        .otp-input { text-align: center; font-size: 28px; letter-spacing: 12px; font-weight: bold; width: 100%; height: 60px; background: #000; border: 2px dashed #f97316; color: #f97316; margin: 20px 0; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="bg-layer" aria-hidden="true">
        <div class="grid-pattern"></div>
    </div>
    <div class="toast-container" id="toastBox"></div>

    <div class="card">
        <h2 style="font-family:'Space Grotesk',sans-serif;"><?= $mode === '2fa' ? 'Código de Acceso' : 'Verifica tu Cuenta' ?></h2>
        <p style="color:var(--fg-muted); font-size: 14px; margin: 10px 0;">Hola <?= htmlspecialchars($name) ?>, hemos enviado un código a <strong><?= htmlspecialchars($email) ?></strong></p>

        <form method="POST" action="?action=verify-otp">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="hidden" name="mode" value="<?= $mode ?>">
            <input type="text" name="otp_code" class="otp-input" placeholder="000000" maxlength="6" autofocus required>
            <button type="submit" class="btn btn-primary btn-full">Verificar Código</button>
        </form>

        <p style="margin-top: 20px; font-size: 13px;">
            <a href="?action=login" class="link">Volver al inicio</a>
        </p>
    </div>

    <script>
    function toast(t,ti,m){const ic={success:'fa-check',error:'fa-xmark'},e=document.createElement('div');e.className=`toast toast-${t}`;e.innerHTML=`<div class="toast-icon"><i class="fa-solid ${ic[t]}"></i></div><div class="toast-content"><div class="toast-title">${ti}</div><div class="toast-message">${m}</div></div><button class="toast-close"><i class="fa-solid fa-xmark"></i></button>`;const rm=()=>{e.classList.add('removing');setTimeout(()=>e.remove(),300)};e.querySelector('.toast-close').onclick=rm;setTimeout(rm,6000);document.getElementById('toastBox').appendChild(e)}
    <?php $error = AppSession::getFlash('error'); if($error): ?>toast('error','Error','<?= addslashes($error) ?>');<?php endif; ?>
    <?php $success = AppSession::getFlash('success'); if($success): ?>toast('success','Éxito','<?= addslashes($success) ?>');<?php endif; ?>
    </script>
</body>
</html>
