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
        .card { max-width: 460px; width: 100%; }

        /* ── Requisitos de contraseña ── */
        .pw-rules {
            margin-top: 10px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 12px;
        }
        .pw-rule {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            color: var(--fg-dim);
            transition: color 0.25s;
        }
        .pw-rule i {
            font-size: 11px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--dash-border);
            color: var(--fg-dim);
            flex-shrink: 0;
            transition: all 0.25s;
        }
        .pw-rule.ok { color: #4ADE80; }
        .pw-rule.ok i {
            background: rgba(34,197,94,0.15);
            border-color: rgba(34,197,94,0.4);
            color: #4ADE80;
        }

        /* Barra fortaleza */
        .strength-track {
            height: 4px;
            background: var(--dash-border);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }
        .strength-fill {
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: width 0.35s ease, background 0.35s ease;
        }
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

                <!-- Nombre completo -->
                <div class="field-group">
                    <label class="field-label" for="full_name">Nombre Completo</label>
                    <input type="text" id="full_name" name="full_name" class="field-input"
                        placeholder="Tu nombre completo"
                        value="<?= htmlspecialchars($formData['full_name'] ?? '') ?>" required autocomplete="name">
                </div>

                <!-- Email -->
                <div class="field-group">
                    <label class="field-label" for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="field-input"
                        placeholder="tu@correo.com"
                        value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required autocomplete="email">
                </div>

                <!-- Contraseña + requisitos -->
                <div class="field-group" style="margin-bottom:8px;">
                    <label class="field-label" for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                        class="field-input" placeholder="Crea tu contraseña"
                        required autocomplete="new-password">

                    <!-- Barra de fortaleza -->
                    <div class="strength-track">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>

                    <!-- Checklist de requisitos -->
                    <div class="pw-rules" id="pwRules">
                        <div class="pw-rule" id="rule-length">
                            <i class="fa-solid fa-check"></i>
                            <span>Mínimo 8 caracteres</span>
                        </div>
                        <div class="pw-rule" id="rule-upper">
                            <i class="fa-solid fa-check"></i>
                            <span>Una mayúscula</span>
                        </div>
                        <div class="pw-rule" id="rule-number">
                            <i class="fa-solid fa-check"></i>
                            <span>Un número</span>
                        </div>
                        <div class="pw-rule" id="rule-special">
                            <i class="fa-solid fa-check"></i>
                            <span>Un carácter especial</span>
                        </div>
                    </div>
                </div>

                <!-- Confirmar contraseña -->
                <div class="field-group">
                    <label class="field-label" for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="field-input" placeholder="Repite tu contraseña"
                        required autocomplete="new-password">
                    <div id="matchMsg" style="font-size:11.5px;margin-top:6px;min-height:16px;"></div>
                </div>

                <button type="submit" class="btn-primary btn-full" style="height:50px;margin-top:4px;">
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
    /* ── Toasts ── */
    function toast(t,ti,m){
        const ic={success:'fa-circle-check',error:'fa-circle-xmark'};
        const col={success:'var(--color-success)',error:'var(--color-danger)'};
        const e=document.createElement('div');
        e.className='toast';
        e.innerHTML=`<div class="toast-icon" style="color:${col[t]}"><i class="fa-solid ${ic[t]}"></i></div><div class="toast-content"><div class="toast-title" style="color:${col[t]}">${ti}</div><div class="toast-message">${m}</div></div><button class="toast-close" onclick="this.closest('.toast').remove()"><i class="fa-solid fa-xmark"></i></button>`;
        document.getElementById('toastBox').appendChild(e);
        setTimeout(()=>{e.classList.add('removing');setTimeout(()=>e.remove(),300)},6000);
    }
    <?php $errs = AppSession::get('form_errors'); if($errs): foreach($errs as $e): ?>
    toast('error','Error','<?= addslashes($e) ?>');
    <?php endforeach; AppSession::remove('form_errors'); endif; ?>

    /* ── Validación de contraseña en tiempo real ── */
    const pwInput   = document.getElementById('password');
    const confInput = document.getElementById('confirm_password');
    const fill      = document.getElementById('strengthFill');
    const matchMsg  = document.getElementById('matchMsg');

    const rules = {
        length:  { el: document.getElementById('rule-length'),  test: v => v.length >= 8 },
        upper:   { el: document.getElementById('rule-upper'),   test: v => /[A-Z]/.test(v) },
        number:  { el: document.getElementById('rule-number'),  test: v => /[0-9]/.test(v) },
        special: { el: document.getElementById('rule-special'), test: v => /[^A-Za-z0-9]/.test(v) },
    };

    // Colores de la barra según fortaleza
    const strengthColors = ['', '#EF4444', '#F59E0B', '#F59E0B', '#22C55E'];

    pwInput.addEventListener('input', function () {
        const v = this.value;
        let score = 0;

        for (const [, rule] of Object.entries(rules)) {
            const ok = rule.test(v);
            rule.el.classList.toggle('ok', ok);
            if (ok) score++;
        }

        // Barra de fortaleza
        fill.style.width  = (score * 25) + '%';
        fill.style.background = strengthColors[score] || '';

        checkMatch();
    });

    confInput.addEventListener('input', checkMatch);

    function checkMatch() {
        const pw   = pwInput.value;
        const conf = confInput.value;
        if (!conf) { matchMsg.textContent = ''; return; }
        if (pw === conf) {
            matchMsg.innerHTML = '<span style="color:var(--color-success);"><i class="fa-solid fa-check"></i> Las contraseñas coinciden</span>';
        } else {
            matchMsg.innerHTML = '<span style="color:var(--color-danger);"><i class="fa-solid fa-xmark"></i> Las contraseñas no coinciden</span>';
        }
    }
    </script>
</body>
</html>
