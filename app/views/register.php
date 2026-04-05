<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Registro</title>
    <meta name="description" content="Crea tu cuenta en Apex Solutions y gestiona tu acceso empresarial de forma segura.">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
        html { min-height: 100%; }
        .register-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            position: relative;
            z-index: 10;
        }
        .card { max-width: 620px; width: 100%; }
        @media (max-width: 560px) { .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <!-- Fondo -->
    <div class="bg-layer" aria-hidden="true">
        <div class="grid-pattern"></div>
    </div>
    <div class="toast-container" id="toastBox"></div>

    <div class="register-wrap">
        <div class="card animate-in">
            <!-- Header -->
            <header style="margin-bottom:28px;text-align:center;">
                <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background:linear-gradient(135deg,#A855F7,#7C3AED);border-radius:12px;margin-bottom:16px;">
                    <i class="fa-solid fa-shield-halved" style="font-size:22px;color:#fff;"></i>
                </div>
                <h1 style="font-size:24px;font-weight:700;color:var(--fg);margin-bottom:6px;">Crear cuenta</h1>
                <p style="font-size:13.5px;color:var(--fg-muted);">Únete a <?= APP_NAME ?> y gestiona tu acceso empresarial</p>
            </header>

            <?php $formData = AppSession::get('form_data', []); AppSession::remove('form_data'); ?>

            <form method="POST" action="?action=register" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

                <div class="form-row">
                    <div class="field-group">
                        <label class="field-label" for="company_name">Empresa</label>
                        <input type="text" id="company_name" name="company_name" class="field-input"
                            placeholder="Nombre de la empresa"
                            value="<?= htmlspecialchars($formData['company_name'] ?? '') ?>" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="company_nit">NIT / ID Fiscal</label>
                        <input type="text" id="company_nit" name="company_nit" class="field-input"
                            placeholder="900123456-1"
                            value="<?= htmlspecialchars($formData['company_nit'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="full_name">Nombre Completo</label>
                    <input type="text" id="full_name" name="full_name" class="field-input"
                        placeholder="Tu nombre completo"
                        value="<?= htmlspecialchars($formData['full_name'] ?? '') ?>" required>
                </div>

                <div class="form-row">
                    <div class="field-group">
                        <label class="field-label" for="email">Correo Corporativo</label>
                        <input type="email" id="email" name="email" class="field-input"
                            placeholder="tu@empresa.com"
                            value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required autocomplete="email">
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="department">Departamento</label>
                        <select id="department" name="department" class="field-input" style="appearance:none;cursor:pointer;">
                            <?php foreach (['Tecnología','Operaciones','Recursos Humanos','Finanzas','Ventas','Legal'] as $dep): ?>
                            <option value="<?= $dep ?>" <?= ($formData['department'] ?? '') === $dep ? 'selected' : '' ?>><?= $dep ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="field-group">
                        <label class="field-label" for="password">Contraseña</label>
                        <input type="password" id="password" name="password" class="field-input"
                            placeholder="Mín. 8 caracteres" required autocomplete="new-password">
                        <div style="margin-top:6px;min-height:4px;border-radius:2px;background:var(--dash-border);overflow:hidden;">
                            <div id="pwStrengthBar" style="height:4px;width:0;border-radius:2px;transition:all 0.3s;"></div>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="confirm_password">Confirmar Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="field-input"
                            placeholder="Repite tu contraseña" required autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn-primary btn-full" style="height:50px;margin-top:8px;">
                    Crear cuenta
                </button>
            </form>

            <div class="divider"><span>O</span></div>
            <p style="text-align:center;font-size:13px;color:var(--fg-muted);">
                ¿Ya tienes cuenta? <a href="?action=login" class="link" style="font-weight:600;">Iniciar sesión</a>
            </p>
        </div>
    </div>

    <script>
    function toast(t,ti,m){
        const ic={success:'fa-circle-check',error:'fa-circle-xmark'};
        const col={success:'var(--color-success)',error:'var(--color-danger)'};
        const e=document.createElement('div');
        e.className='toast';
        e.innerHTML=`<div class="toast-icon" style="color:${col[t]}"><i class="fa-solid ${ic[t]}"></i></div><div class="toast-content"><div class="toast-title" style="color:${col[t]}">${ti}</div><div class="toast-message">${m}</div></div><button class="toast-close" onclick="this.closest('.toast').remove()"><i class="fa-solid fa-xmark"></i></button>`;
        document.getElementById('toastBox').appendChild(e);
        setTimeout(()=>{e.classList.add('removing');setTimeout(()=>e.remove(),300)},6000);
    }
    <?php $errs = AppSession::get('form_errors'); if($errs): foreach($errs as $e): ?>toast('error','Error','<?= addslashes($e) ?>');<?php endforeach; AppSession::remove('form_errors'); endif; ?>

    // Barra de fortaleza de contraseña
    document.getElementById('password').addEventListener('input', function() {
        const v = this.value, bar = document.getElementById('pwStrengthBar');
        let score = 0;
        if (v.length >= 8) score++;
        if (/[A-Z]/.test(v)) score++;
        if (/[0-9]/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;
        const colors = ['','#EF4444','#F59E0B','#22C55E','#A855F7'];
        bar.style.width = (score * 25) + '%';
        bar.style.background = colors[score] || '';
    });
    </script>
</body>
</html>
