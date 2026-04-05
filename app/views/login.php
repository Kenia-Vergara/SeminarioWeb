<!DOCTYPE html>
<html lang="es" class="cosmic-body">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Apex Solutions</title>
    <meta name="description" content="Accede de forma segura a tu cuenta Apex Solutions con autenticación de doble factor.">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
    </style>
</head>

<body class="cosmic-body">
    <!-- Fondo cósmico -->
    <div class="cosmic-bg-wrap" aria-hidden="true">
        <div class="planet"><div class="planet-glow"></div></div>
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 50% 50%,transparent 40%,rgba(3,3,5,0.92) 100%);"></div>
    </div>

    <div class="toast-container" id="toastBox"></div>

    <main class="glass-card animate-in" role="main">
        <header style="margin-bottom:32px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                <div style="width:36px;height:36px;background:linear-gradient(135deg,#A855F7,#7C3AED);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-shield-halved" style="font-size:16px;color:#fff;"></i>
                </div>
                <span style="font-size:13px;font-weight:700;color:var(--fg-muted);letter-spacing:0.5px;">APEX SOLUTIONS</span>
            </div>
            <h1 style="font-size:28px;font-weight:700;margin-bottom:8px;">Bienvenido de vuelta</h1>
            <p style="color:var(--fg-muted);font-size:13.5px;">Ingresa tus credenciales para continuar</p>
        </header>

        <form method="POST" action="?action=login" novalidate id="loginForm">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

            <div class="field-group">
                <label class="field-label" for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" class="field-input"
                    placeholder="tu@empresa.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autocomplete="email">
            </div>

            <div class="field-group" style="position:relative;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;">
                    <label class="field-label" for="password" style="margin-bottom:0;">Contraseña</label>
                    <button type="button" id="togglePass"
                        style="background:none;border:none;color:var(--fg-muted);font-size:12px;cursor:pointer;padding:0;font-family:inherit;">
                        <i class="fa-solid fa-eye" id="toggleIcon"></i> Mostrar
                    </button>
                </div>
                <input type="password" id="password" name="password" class="field-input"
                    placeholder="••••••••" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">
                <span id="btnText">Iniciar Sesión</span>
            </button>

            <p style="text-align:center;font-size:13px;color:var(--fg-muted);margin-top:20px;">
                ¿No tienes cuenta? <a href="?action=register" class="link" style="font-weight:600;">Regístrate gratis</a>
            </p>
        </form>
    </main>

    <script>
        // Toast
        function toast(t, ti, m) {
            const ic = { success: 'fa-circle-check', error: 'fa-circle-xmark' };
            const col = { success: 'var(--color-success)', error: 'var(--color-danger)' };
            const e = document.createElement('div');
            e.className = 'toast';
            e.innerHTML = `<div class="toast-icon" style="color:${col[t]}"><i class="fa-solid ${ic[t]}"></i></div><div class="toast-content"><div class="toast-title" style="color:${col[t]}">${ti}</div><div class="toast-message">${m}</div></div><button class="toast-close" onclick="this.closest('.toast').remove()"><i class="fa-solid fa-xmark"></i></button>`;
            document.getElementById('toastBox').appendChild(e);
            setTimeout(() => { e.classList.add('removing'); setTimeout(() => e.remove(), 300); }, 6000);
        }
        <?php $error = AppSession::getFlash('error'); if ($error): ?>toast('error','Error','<?= addslashes($error) ?>');<?php endif; ?>
        <?php $success = AppSession::getFlash('success'); if ($success): ?>toast('success','Éxito','<?= addslashes($success) ?>');<?php endif; ?>

        // Toggle contraseña
        document.getElementById('togglePass').addEventListener('click', function () {
            const inp = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
                this.childNodes[1].textContent = ' Ocultar';
            } else {
                inp.type = 'password';
                icon.className = 'fa-solid fa-eye';
                this.childNodes[1].textContent = ' Mostrar';
            }
        });

        // Estrellas de fondo
        for (let i = 0; i < 90; i++) {
            const s = document.createElement('div');
            const size = Math.random() * 2 + 0.5;
            Object.assign(s.style, {
                position: 'fixed', left: Math.random() * 100 + 'vw',
                top: Math.random() * 100 + 'vh', width: size + 'px',
                height: size + 'px', background: '#fff', borderRadius: '50%',
                opacity: Math.random() * 0.7, zIndex: '1', pointerEvents: 'none'
            });
            document.body.appendChild(s);
        }
    </script>
</body>
</html>