<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> — Iniciar sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
        html, body { height: 100%; overflow: hidden; }
        #particleCanvas { pointer-events: none; }
    </style>
</head>
<body>
    <div class="bg-layer" aria-hidden="true">
        <div class="grid-pattern"></div>
        <canvas id="particleCanvas"></canvas>
    </div>
    <div class="toast-container" id="toastBox"></div>

    <div class="card card-split" style="display:flex;align-items:stretch;min-height:580px;max-width:920px;width:95vw;margin:auto;position:relative;z-index:10">
        <aside class="brand-side" style="flex:0 0 380px;background:linear-gradient(165deg,#12100d,#0e0c09 50%,#0a0908);display:flex;flex-direction:column;justify-content:space-between;padding:48px 40px;position:relative;overflow:hidden">
            <div style="position:absolute;width:320px;height:320px;background:radial-gradient(circle,rgba(249,115,22,.08) 0%,transparent 70%);top:-70px;right:-90px;animation:brandGlow 9s ease-in-out infinite alternate"></div>
            <div style="position:relative;z-index:1">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:48px">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;box-shadow:0 4px 24px rgba(249,115,22,.3)"><i class="fa-solid fa-code"></i></div>
                    <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;color:var(--fg)"><?= APP_NAME ?></div>
                </div>
                <h2 style="font-family:'Space Grotesk',sans-serif;font-size:30px;font-weight:700;line-height:1.25;color:var(--fg);margin-bottom:16px">Plataforma de<br><span style="background:linear-gradient(135deg,#f97316,#fb923c);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Gestión Empresarial</span></h2>
                <p style="font-size:14.5px;line-height:1.7;color:var(--fg-muted);max-width:280px">Accede a tu espacio de trabajo centralizado. Monitorea proyectos, equipos y métricas en tiempo real.</p>
            </div>
            <div style="position:relative;z-index:1;display:flex;flex-direction:column;gap:16px">
                <div style="display:flex;align-items:center;gap:12px;font-size:13px;color:var(--fg-muted)"><i class="fa-solid fa-lock" style="width:32px;height:32px;border-radius:8px;background:rgba(249,115,22,.07);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0"></i><span>Cifrado de extremo a extremo</span></div>
                <div style="display:flex;align-items:center;gap:12px;font-size:13px;color:var(--fg-muted)"><i class="fa-solid fa-fingerprint" style="width:32px;height:32px;border-radius:8px;background:rgba(249,115,22,.07);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0"></i><span>Autenticación multifactor</span></div>
                <div style="display:flex;align-items:center;gap:12px;font-size:13px;color:var(--fg-muted)"><i class="fa-solid fa-clock-rotate-left" style="width:32px;height:32px;border-radius:8px;background:rgba(249,115,22,.07);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0"></i><span>Auditoría de sesiones en tiempo real</span></div>
            </div>
        </aside>

        <section style="flex:1;background:var(--bg-card);padding:48px 44px;display:flex;flex-direction:column;justify-content:center">
            <header style="margin-bottom:32px">
                <h1 style="font-family:'Space Grotesk',sans-serif;font-size:26px;font-weight:700;color:var(--fg);margin-bottom:8px">Iniciar sesión</h1>
                <p style="font-size:14px;color:var(--fg-muted)">Ingresa tus credenciales para acceder al sistema</p>
            </header>

            <form method="POST" action="?action=login" novalidate autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

                <div class="field-group">
                    <label class="field-label" for="email">Correo electrónico</label>
                    <div class="field-wrapper">
                        <i class="fa-regular fa-envelope field-icon"></i>
                        <input type="email" id="email" name="email" class="field-input" placeholder="usuario@empresa.com" autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Contraseña</label>
                    <div class="field-wrapper">
                        <i class="fa-solid fa-key field-icon"></i>
                        <input type="password" id="password" name="password" class="field-input" placeholder="Ingresa tu contraseña" autocomplete="current-password" required style="padding-right:48px">
                        <button type="button" class="toggle-password" aria-label="Mostrar contraseña"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full" style="height:52px;font-size:15px"><span class="btn-text">Acceder al sistema</span><div class="spinner"></div></button>
            </form>

            <div class="divider"><span>O</span></div>
            <p style="text-align:center;font-size:13.5px;color:var(--fg-muted)">¿No tienes una cuenta? <a href="?action=register" class="link" style="font-weight:600">Solicitar acceso</a></p>
        </section>
    </div>

    <script>
    !function(){const c=document.getElementById('particleCanvas'),x=c.getContext('2d');let p=[],a,w,h;function r(){w=c.width=innerWidth;h=c.height=innerHeight}r();addEventListener('resize',r);for(let i=0;i<Math.min(50,w*h/30000);i++)p.push({x:Math.random()*w,y:Math.random()*h,vx:(Math.random()-.5)*.22,vy:(Math.random()-.5)*.22,s:Math.random()*1.2+.4,o:Math.random()*.18+.05});let m={x:-1e3,y:-1e3};addEventListener('mousemove',e=>{m.x=e.clientX;m.y=e.clientY});!function d(){x.clearRect(0,0,w,h);for(let i=0;i<p.length;i++){const q=p[i];q.x+=q.vx;q.y+=q.vy;if(q.x<0||q.x>w)q.vx*=-1;if(q.y<0||q.y>h)q.vy*=-1;x.beginPath();x.arc(q.x,q.y,Math.max(.1,q.s),0,6.28);x.fillStyle=`rgba(249,115,22,${q.o})`;x.fill();for(let j=i+1;j<p.length;j++){const k=p[j],dx=q.x-k.x,dy=q.y-k.y,dd=Math.sqrt(dx*dx+dy*dy);if(dd<130){x.beginPath();x.moveTo(q.x,q.y);x.lineTo(k.x,k.y);x.strokeStyle=`rgba(249,115,22,${(1-dd/130)*.05})`;x.lineWidth=.5;x.stroke()}}const dx=q.x-m.x,dy=q.y-m.y,dd=Math.sqrt(dx*dx+dy*dy);if(dd<100){const f=(1-dd/100)*.012;q.vx+=dx*f;q.vy+=dy*f}const sp=Math.sqrt(q.vx*q.vx+q.vy*q.vy);if(sp>.7){q.vx*=.98;q.vy*=.98}}a=requestAnimationFrame(d)}()}();

    function toast(t,ti,m){const ic={success:'fa-check',error:'fa-xmark',warning:'fa-exclamation',info:'fa-info'},e=document.createElement('div');e.className=`toast toast-${t}`;e.innerHTML=`<div class="toast-icon"><i class="fa-solid ${ic[t]}"></i></div><div class="toast-content"><div class="toast-title">${ti}</div><div class="toast-message">${m}</div></div><button class="toast-close"><i class="fa-solid fa-xmark"></i></button>`;const rm=()=>{e.classList.add('removing');setTimeout(()=>e.remove(),300)};e.querySelector('.toast-close').onclick=rm;setTimeout(rm,6000);document.getElementById('toastBox').appendChild(e)}
    <?php $error = AppSession::getFlash('error'); if($error): ?>toast('error','Error','<?= addslashes($error) ?>');<?php endif; ?>
    <?php $success = AppSession::getFlash('success'); if($success): ?>toast('success','Éxito','<?= addslashes($success) ?>');<?php endif; ?>

    document.querySelector('.toggle-password').addEventListener('click',function(){const i=this.querySelector('i'),inp=this.parentElement.querySelector('input');if(inp.type==='password'){inp.type='text';i.classList.replace('fa-eye','fa-eye-slash')}else{inp.type='password';i.classList.replace('fa-eye-slash','fa-eye')}});
    </script>
</body>
</html>
