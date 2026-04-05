<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Verificación OTP</title>
    <meta name="description" content="Ingresa el código de seguridad enviado a tu correo electrónico.">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
        .otp-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            position: relative;
            z-index: 10;
        }
        .card { max-width: 420px; width: 100%; text-align: center; }

        .otp-input {
            text-align: center;
            font-size: 30px;
            letter-spacing: 14px;
            font-weight: 700;
            width: 100%;
            height: 68px;
            background: rgba(168,85,247,0.05);
            border: 2px dashed rgba(168,85,247,0.4);
            color: #A855F7;
            margin: 20px 0;
            border-radius: 10px;
            outline: none;
            transition: all 0.25s;
            font-family: 'Courier New', monospace;
        }
        .otp-input:focus {
            border-color: #A855F7;
            box-shadow: 0 0 0 4px rgba(168,85,247,0.15);
            background: rgba(168,85,247,0.08);
        }

        .countdown-ring {
            position: relative;
            width: 60px;
            height: 60px;
            margin: 0 auto 16px;
        }
        .countdown-ring svg { transform: rotate(-90deg); }
        .countdown-ring circle {
            fill: none;
            stroke-width: 3;
            stroke-linecap: round;
        }
        #timerText {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="bg-layer" aria-hidden="true">
        <div class="grid-pattern"></div>
    </div>
    <div class="toast-container" id="toastBox"></div>

    <div class="otp-wrap">
        <div class="card animate-in">
            <!-- Ícono -->
            <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:linear-gradient(135deg,rgba(168,85,247,0.2),rgba(124,58,237,0.2));border:1px solid rgba(168,85,247,0.3);border-radius:14px;margin-bottom:20px;">
                <i class="fa-solid <?= $mode === '2fa' ? 'fa-key' : 'fa-envelope-open-text' ?>"
                   style="font-size:22px;color:#A855F7;"></i>
            </div>

            <h1 style="font-size:22px;font-weight:700;margin-bottom:8px;">
                <?= $mode === '2fa' ? 'Código de Acceso' : 'Verifica tu Cuenta' ?>
            </h1>
            <p style="color:var(--fg-muted);font-size:13.5px;line-height:1.6;margin-bottom:4px;">
                Hola <strong style="color:var(--fg);"><?= htmlspecialchars($name) ?></strong>, enviamos un código de 6 dígitos a
            </p>
            <p style="font-size:13px;color:#A855F7;font-weight:600;margin-bottom:24px;">
                <?= htmlspecialchars($email) ?>
            </p>

            <!-- Timer -->
            <div class="countdown-ring">
                <svg width="60" height="60" viewBox="0 0 60 60">
                    <circle cx="30" cy="30" r="26" stroke="var(--dash-border)" />
                    <circle cx="30" cy="30" r="26" stroke="#A855F7"
                        stroke-dasharray="163.36"
                        stroke-dashoffset="0"
                        id="timerCircle" />
                </svg>
                <div id="timerText" style="color:var(--fg-muted);">5:00</div>
            </div>
            <p style="font-size:12px;color:var(--fg-dim);margin-bottom:8px;">El código expira en</p>

            <?php if (EMAIL_MODE === 'debug' && $dbgOTP): ?>
            <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:8px;padding:10px 16px;margin-bottom:16px;font-size:12px;color:#FBBF24;">
                <i class="fa-solid fa-bug"></i> <strong>DEBUG:</strong> OTP → <code style="font-size:14px;letter-spacing:4px;"><?= $dbgOTP ?></code>
            </div>
            <?php endif; ?>

            <form method="POST" action="?action=verify-otp" id="otpForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <input type="hidden" name="mode" value="<?= $mode ?>">
                <input type="text" name="otp_code" id="otpInput" class="otp-input"
                    placeholder="······" maxlength="6" autofocus required
                    inputmode="numeric" autocomplete="one-time-code">
                <button type="submit" class="btn-primary" id="verifyBtn">
                    <i class="fa-solid fa-check-circle" style="margin-right:6px;"></i>
                    Verificar Código
                </button>
            </form>

            <!-- Reenviar -->
            <div style="margin-top:20px;">
                <?php if ($canResend): ?>
                <a href="?action=verify-otp<?= $mode === '2fa' ? '&mode=2fa' : '' ?>"
                   style="font-size:13px;color:var(--fg-muted);text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-rotate-right"></i> Reenviar código
                </a>
                <?php else: ?>
                <p style="font-size:13px;color:var(--fg-dim);" id="resendMsg">
                    <i class="fa-solid fa-clock"></i>
                    Podrás reenviar en <strong id="resendCountdown"><?= $cooldown ?></strong>s
                </p>
                <?php endif; ?>
            </div>

            <div style="margin-top:16px;">
                <a href="?action=login" class="link" style="font-size:12.5px;color:var(--fg-dim);">
                    <i class="fa-solid fa-arrow-left" style="margin-right:4px;"></i>Volver al inicio
                </a>
            </div>
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
    <?php $error = AppSession::getFlash('error'); if($error): ?>toast('error','Error','<?= addslashes($error) ?>');<?php endif; ?>
    <?php $success = AppSession::getFlash('success'); if($success): ?>toast('success','Éxito','<?= addslashes($success) ?>');<?php endif; ?>

    // Solo permitir dígitos en el input OTP
    document.getElementById('otpInput').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 6);
        if (this.value.length === 6) {
            document.getElementById('otpForm').submit();
        }
    });

    // ── Timer de 5 minutos ──
    const TOTAL = 300;
    const circumference = 2 * Math.PI * 26; // 163.36
    let remaining = TOTAL;
    const circle = document.getElementById('timerCircle');
    const timerText = document.getElementById('timerText');

    circle.style.strokeDasharray = circumference;
    circle.style.strokeDashoffset = 0;

    const timer = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
            clearInterval(timer);
            timerText.textContent = '0:00';
            timerText.style.color = 'var(--color-danger)';
            circle.style.stroke = 'var(--color-danger)';
            circle.style.strokeDashoffset = circumference;
            toast('error', 'Código expirado', 'Solicita un nuevo código de verificación.');
            document.getElementById('verifyBtn').disabled = true;
            document.getElementById('verifyBtn').style.opacity = '0.5';
            return;
        }
        const mins = Math.floor(remaining / 60);
        const secs = remaining % 60;
        timerText.textContent = `${mins}:${secs.toString().padStart(2,'0')}`;
        const offset = circumference * (1 - remaining / TOTAL);
        circle.style.strokeDashoffset = offset;
        if (remaining <= 60) {
            circle.style.stroke = 'var(--color-danger)';
            timerText.style.color = 'var(--color-danger)';
        }
    }, 1000);

    // ── Cooldown de reenvío ──
    const resendEl = document.getElementById('resendCountdown');
    if (resendEl) {
        let cd = parseInt(resendEl.textContent);
        const cdTimer = setInterval(() => {
            cd--;
            resendEl.textContent = cd;
            if (cd <= 0) {
                clearInterval(cdTimer);
                document.getElementById('resendMsg').innerHTML =
                    `<a href="?action=verify-otp<?= $mode === '2fa' ? '&mode=2fa' : '' ?>" style="font-size:13px;color:var(--fg-muted);text-decoration:none;display:inline-flex;align-items:center;gap:6px;"><i class="fa-solid fa-rotate-right"></i> Reenviar código</a>`;
            }
        }, 1000);
    }
    </script>
</body>
</html>
