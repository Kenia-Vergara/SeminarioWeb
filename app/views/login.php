<!DOCTYPE html>
<html lang="es" class="cosmic-body">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — SecureAuth</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        <?php include __DIR__ . '/partials/styles.php'; ?>
    </style>
</head>
<body class="cosmic-body">
    <div class="cosmic-bg-wrap">
        <div class="planet">
            <div class="planet-glow"></div>
        </div>
        <div style="position: absolute; width: 100%; height: 100%; background: radial-gradient(circle at 50% 50%, transparent 40%, rgba(3, 3, 5, 0.9) 100%);"></div>
    </div>

    <div class="toast-container" id="toastBox"></div>

    <main class="glass-card animate-in">
        <header style="margin-bottom: 32px;">
            <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">Sign In</h1>
            <p style="color: var(--fg-muted); font-size: 14px;">Keep it all together and you'll be fine</p>
        </header>

        <form method="POST" action="?action=login" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

            <div class="field-group">
                <label class="field-label" for="email">Email or Phone</label>
                <input type="email" id="email" name="email" class="field-input" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="field-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label class="field-label" for="password" style="margin-bottom: 0;">Password</label>
                    <button type="button" class="toggle-password" style="background: none; border: none; color: var(--fg-muted); font-size: 13px; cursor: pointer; padding: 0;">Show</button>
                </div>
                <input type="password" id="password" name="password" class="field-input" placeholder="••••••••" required>
            </div>

            <div style="text-align: left; margin-bottom: 24px;">
                <a href="#" style="font-size: 13px; color: var(--fg-muted); text-decoration: none;">Forgot Password</a>
            </div>

            <button type="submit" class="btn-primary" style="margin-bottom: 20px;">Sign In</button>

            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px; color: var(--fg-dim); font-size: 12px;">
                <div style="flex: 1; height: 1px; background: rgba(255, 255, 255, 0.1);"></div>
                <span>or</span>
                <div style="flex: 1; height: 1px; background: rgba(255, 255, 255, 0.1);"></div>
            </div>

            <button type="button" class="btn-secondary" style="width: 100%; height: 48px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 32px;">
                <i class="fa-brands fa-apple"></i> Sign in with Apple
            </button>

            <p style="text-align: center; font-size: 13px; color: var(--fg-muted);">
                New to Atomz <a href="?action=register" style="color: #6366F1; font-weight: 600; text-decoration: none;">Join Now</a>
            </p>
        </form>
    </main>

    <script>
    function toast(t,ti,m){const ic={success:'fa-check',error:'fa-xmark'},e=document.createElement('div');e.className=`toast toast-${t}`;e.innerHTML=`<div class="toast-content"><div class="toast-title" style="color: ${t==='error'?'#ef4444':'#4ade80'}">${ti}</div><div class="toast-message" style="font-size: 12px; color: #a1a1aa">${m}</div></div>`;const rm=()=>{e.style.opacity='0';e.style.transform='translateX(20px)';setTimeout(()=>e.remove(),300)};setTimeout(rm,6000);document.getElementById('toastBox').appendChild(e)}
    <?php $error = AppSession::getFlash('error'); if($error): ?>toast('error','Error','<?= addslashes($error) ?>');<?php endif; ?>
    <?php $success = AppSession::getFlash('success'); if($success): ?>toast('success','Success','<?= addslashes($success) ?>');<?php endif; ?>

    document.querySelector('.toggle-password').addEventListener('click', function() {
        const inp = document.getElementById('password');
        if (inp.type === 'password') {
            inp.type = 'text'; this.textContent = 'Hide';
        } else {
            inp.type = 'password'; this.textContent = 'Show';
        }
    });

    // Añadir algunas estrellas aleatorias
    for(let i=0; i<80; i++) {
        let s = document.createElement('div');
        s.style.position = 'fixed';
        s.style.left = Math.random()*100 + 'vw';
        s.style.top = Math.random()*100 + 'vh';
        s.style.width = Math.random()*2 + 'px';
        s.style.height = s.style.width;
        s.style.background = '#fff';
        s.style.borderRadius = '50%';
        s.style.opacity = Math.random();
        s.style.zIndex = '1';
        document.body.appendChild(s);
    }
    </script>
</body>
</html>
