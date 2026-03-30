<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Security.php';
require_once __DIR__ . '/model/UserModel.php';
require_once __DIR__ . '/model/OTPModel.php';
require_once __DIR__ . '/model/AuditLogModel.php';
require_once __DIR__ . '/model/UserSessionModel.php';
require_once __DIR__ . '/service/EmailService.php';
require_once __DIR__ . '/controller/AuthController.php';

Security::headers();
AppSession::start();
 $auth = new AuthController();

/* Reenvío */
if (($_GET['action'] ?? '') === 'resend' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->processResendOTP();
    exit;
}

/* Verificar código */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->processVerifyOTP();
    exit;
}

/* Obtener datos para la vista */
 $data = $auth->getOTPViewData();
if (!$data) {
    header('Location: login.php');
    exit;
}

 $user        = $data['user'];
 $mode        = $data['mode'];
 $canResend   = $data['canResend'];
 $cooldown    = $data['cooldown'];
 $debugOTP    = $data['debugOTP'];
 $debugEmail  = $data['debugEmail'];
 $csrf        = Security::generateCSRF();
 $flashError  = AppSession::getFlash('error');
 $flashSuccess = AppSession::getFlash('success');

 $maskedEmail = substr($user['email'], 0, 1)
    . str_repeat('*', max(1, strlen(explode('@', $user['email'])[0]) - 1))
    . '@' . explode('@', $user['email'])[1];

 $title = $mode === 'verification' ? 'Verificar cuenta' : 'Verificación en dos pasos';
 $desc  = $mode === 'verification'
    ? 'Confirma tu correo electrónico para activar tu cuenta.'
    : 'Ingresa el código que enviamos a tu correo para completar el acceso.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeCraft — <?= htmlspecialchars($title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style><?php include __DIR__ . '/views/partials/styles.php'; ?>
    html,body{height:100%}#particleCanvas{pointer-events:none}
    .otp-digit{width:52px;height:62px;text-align:center;font-family:'Space Grotesk',monospace;font-size:24px;font-weight:700;color:var(--fg);background:var(--bg-input);border:1.5px solid var(--border);border-radius:10px;outline:none;transition:all .2s;caret-color:var(--accent)}
    .otp-digit:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);background:rgba(249,115,22,.02)}
    .otp-digit.filled{border-color:rgba(249,115,22,.25)}
    .otp-digit.error{border-color:var(--error);box-shadow:0 0 0 3px var(--error-glow);animation:otpShake .4s ease}
    @media(max-width:420px){.otp-digit{width:42px;height:52px;font-size:20px}}
    </style>
</head>
<body>
    <div class="bg-layer" aria-hidden="true"><div class="grid-pattern"></div><canvas id="particleCanvas"></canvas></div>
    <div class="toast-container" id="toastBox"></div>

    <div style="position:relative;z-index:10;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:24px">
        <div class="card" style="max-width:480px;width:100%;padding:48px 44px;text-align:center">
            <div style="width:68px;height:68px;border-radius:18px;background:linear-gradient(135deg,rgba(249,115,22,.12),rgba(249,115,22,.03));border:1px solid rgba(249,115,22,.12);display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--accent);margin:0 auto 24px"><i class="fa-solid fa-shield-halved"></i></div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--fg);margin-bottom:10px"><?= htmlspecialchars($title) ?></h1>
            <p style="font-size:14px;color:var(--fg-muted);line-height:1.7;margin-bottom:8px"><?= htmlspecialchars($desc) ?></p>
            <p style="font-size:14px;color:var(--fg-muted);margin-bottom:32px">Enviado a: <strong style="color:var(--accent)"><?= htmlspecialchars($maskedEmail) ?></strong></p>

            <?php if ($debugOTP): ?>
            <div style="background:rgba(249,115,22,.06);border:1px solid rgba(249,115,22,.15);border-radius:8px;padding:14px;margin-bottom:24px">
                <p style="font-size:11px;color:var(--fg-dim);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px">Modo debug — Código OTP</p>
                <p style="font-family:'Space Grotesk',monospace;font-size:28px;font-weight:700;color:var(--accent);letter-spacing:6px;margin:0"><?= htmlspecialchars($debugOTP) ?></p>
                <p style="font-size:11px;color:var(--fg-dim);margin-top:6px">Para: <?= htmlspecialchars($debugEmail) ?></p>
            </div>
            <?php endif; ?>

            <form method="POST" action="otp.php" id="otpForm">
                <?= Security::csrfField() ?>
                <input type="hidden" name="otp_code" id="otpCodeInput">

                <div style="display:flex;gap:10px;justify-content:center;margin-bottom:8px" id="otpInputs">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" aria-label="Dígito <?= $i + 1 ?>" autocomplete="one-time-code">
                    <?php endfor; ?>
                </div>
                <p style="font-size:12px;color:var(--fg-dim);margin-bottom:24px;min-height:18px" id="otpHint">&nbsp;</p>
                <button type="submit" class="btn btn-primary btn-full" id="btnVerify" disabled style="margin-bottom:16px"><span class="btn-text">Verificar código</span><div class="spinner"></div></button>
            </form>

            <div style="display:flex;align-items:center;justify-content:space-between">
                <?php if ($canResend): ?>
                <form method="POST" action="otp.php?action=resend" style="margin:0">
                    <?= Security::csrfField() ?>
                    <button type="submit" class="link" style="font-size:13px">Reenviar código</button>
                </form>
                <?php else: ?>
                <span style="font-size:13px;color:var(--fg-dim)">Reenviar en <span style="color:var(--accent);font-weight:600" id="cdTimer"><?= $cooldown ?>s</span></span>
                <?php endif; ?>
                <a href="login.php" class="link" style="font-size:13px">Volver al login</a>
            </div>
        </div>
    </div>

    <script>
    !function(){const c=document.getElementById('particleCanvas'),x=c.getContext('2d');let p=[];function r(){c.width=innerWidth;c.height=innerHeight}r();addEventListener('resize',r);for(let i=0;i<35;i++)p.push({x:Math.random()*innerWidth,y:Math.random()*innerHeight,vx:(Math.random()-.5)*.2,vy:(Math.random()-.5)*.2,s:Math.random()*1.2+.4,o:Math.random()*.15+.04});!function d(){x.clearRect(0,0,c.width,c.height);for(let i=0;i<p.length;i++){const q=p[i];q.x+=q.vx;q.y+=q.vy;if(q.x<0||q.x>c.width)q.vx*=-1;if(q.y<0||q.y>c.height)q.vy*=-1;x.beginPath();x.arc(q.x,q.y,Math.max(.1,q.s),0,6.28);x.fillStyle=`rgba(249,115,22,${q.o})`;x.fill()}requestAnimationFrame(d)}()}();
    function toast(t,ti,m){const ic={success:'fa-check',error:'fa-xmark',warning:'fa-exclamation',info:'fa-info'},e=document.createElement('div');e.className=`toast toast-${t}`;e.innerHTML=`<div class="toast-icon"><i class="fa-solid ${ic[t]}"></i></div><div class="toast-content"><div class="toast-title">${ti}</div><div class="toast-message">${m}</div></div><button class="toast-close"><i class="fa-solid fa-xmark"></i></button>`;const rm=()=>{e.classList.add('removing');setTimeout(()=>e.remove(),300)};e.querySelector('.toast-close').onclick=rm;setTimeout(rm,6000);document.getElementById('toastBox').appendChild(e)}
    <?php if($flashError): ?>toast('error','Error','<?= addslashes($flashError) ?>');<?php endif; ?>
    <?php if($flashSuccess): ?>toast('success','Éxito','<?= addslashes($flashSuccess) ?>');<?php endif; ?>

    const inputs=Array.from(document.querySelectorAll('.otp-digit')),hidden=document.getElementById('otpCodeInput'),btn=document.getElementById('btnVerify'),hint=document.getElementById('otpHint');
    setTimeout(()=>inputs[0].focus(),100);
    inputs.forEach((inp,i)=>{
        inp.addEventListener('input',e=>{const v=e.target.value.replace(/[^0-9]/g,'');e.target.value=v.slice(0,1);inp.classList.remove('error');hint.innerHTML='&nbsp;';if(v&&i<inputs.length-1)inputs[i+1].focus();inputs.forEach(d=>d.classList.toggle('filled',d.value.length>0));btn.disabled=!inputs.every(d=>d.value.length===1)});
        inp.addEventListener('keydown',e=>{if(e.key==='Backspace'&&!inp.value&&i>0){inputs[i-1].focus();inputs[i-1].value='';inputs[i-1].classList.remove('filled')}if(e.key==='ArrowLeft'&&i>0)inputs[i-1].focus();if(e.key==='ArrowRight'&&i<inputs.length-1)inputs[i+1].focus()});
        inp.addEventListener('paste',e=>{e.preventDefault();const t=(e.clipboardData||window.clipboardData).getData('text').replace(/[^0-9]/g,'').slice(0,6);if(t.length){t.split('').forEach((c,idx)=>{if(inputs[idx]){inputs[idx].value=c;inputs[idx].classList.add('filled')}});inputs[Math.min(t.length,5)].focus();btn.disabled=t.length<6}});
        inp.addEventListener('focus',()=>inp.select());
    });
    document.getElementById('otpForm').addEventListener('submit',()=>{hidden.value=inputs.map(d=>d.value).join('')});

    <?php if(!$canResend && $cooldown > 0): ?>
    let cd=<?= $cooldown ?>;const timer=document.getElementById('cdTimer');
    const ci=setInterval(()=>{cd--;if(cd<=0){clearInterval(ci);location.reload()}else{timer.textContent=cd+'s'}},1000);
    <?php endif; ?>
    </script>
</body>
</html>