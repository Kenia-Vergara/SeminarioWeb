<?php
/** @var string $csrf */
/** @var string|null $flashError */
/** @var string|null $flashSuccess */
/** @var int $remainingAttempts */
?>
<div class="card card-split" style="display:flex;align-items:stretch;min-height:580px;max-width:920px;width:95vw;margin:auto;position:relative;z-index:10">
    <aside class="brand-side" style="flex:0 0 380px;background:linear-gradient(165deg,#12100d,#0e0c09 50%,#0a0908);display:flex;flex-direction:column;justify-content:space-between;padding:48px 40px;position:relative;overflow:hidden">
        <div style="position:absolute;width:320px;height:320px;background:radial-gradient(circle,rgba(249,115,22,.08) 0%,transparent 70%);top:-70px;right:-90px;animation:brandGlow 9s ease-in-out infinite alternate"></div>
        <div style="position:relative;z-index:1">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:48px">
                <div style="width:48px;height:48px;background:linear-gradient(135deg,#f97316,#c2410c);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;box-shadow:0 4px 24px rgba(249,115,22,.3)"><i class="fa-solid fa-code"></i></div>
                <div style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;color:var(--fg)">CodeCraft</div>
            </div>
            <h2 style="font-family:'Space Grotesk',sans-serif;font-size:30px;font-weight:700;line-height:1.25;color:var(--fg);margin-bottom:16px">Plataforma de<br><span style="background:linear-gradient(135deg,#f97316,#fb923c);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Gestión Empresarial</span></h2>
            <p style="font-size:14.5px;line-height:1.7;color:var(--fg-muted);max-width:280px">Accede a tu espacio de trabajo centralizado. Monitorea proyectos, equipos y métricas en tiempo real.</p>
        </div>
        <div style="position:relative;z-index:1;display:flex;flex-direction:column;gap:16px">
            <div style="display:flex;align-items:center;gap:12px;font-size:13px;color:var(--fg-muted)"><i style="width:32px;height:32px;border-radius:8px;background:rgba(249,115,22,.07);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0" class="fa-solid fa-lock"></i><span>Cifrado de extremo a extremo</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:13px;color:var(--fg-muted)"><i style="width:32px;height:32px;border-radius:8px;background:rgba(249,115,22,.07);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0" class="fa-solid fa-fingerprint"></i><span>Autenticación multifactor</span></div>
            <div style="display:flex;align-items:center;gap:12px;font-size:13px;color:var(--fg-muted)"><i style="width:32px;height:32px;border-radius:8px;background:rgba(249,115,22,.07);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0" class="fa-solid fa-clock-rotate-left"></i><span>Auditoría de sesiones en tiempo real</span></div>
        </div>
    </aside>

    <section style="flex:1;background:var(--bg-card);padding:48px 44px;display:flex;flex-direction:column;justify-content:center">
        <header style="margin-bottom:32px">
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:26px;font-weight:700;color:var(--fg);margin-bottom:8px">Iniciar sesión</h1>
            <p style="font-size:14px;color:var(--fg-muted)">Ingresa tus credenciales para acceder al sistema</p>
        </header>

        <form method="POST" action="login.php" novalidate autocomplete="on">
            <?= $csrf ?>

            <div class="field-group" id="emailGroup">
                <label class="field-label" for="email">Correo electrónico</label>
                <div class="field-wrapper">
                    <i class="fa-regular fa-envelope field-icon"></i>
                    <input type="email" id="email" name="email" class="field-input" placeholder="usuario@empresa.com" autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="field-error" id="emailError"><i class="fa-solid fa-circle-exclamation"></i><span></span></div>
            </div>

            <div class="field-group" id="passwordGroup">
                <label class="field-label" for="password">Contraseña</label>
                <div class="field-wrapper">
                    <i class="fa-solid fa-key field-icon"></i>
                    <input type="password" id="password" name="password" class="field-input" placeholder="Ingresa tu contraseña" autocomplete="current-password" required style="padding-right:48px">
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña"><i class="fa-regular fa-eye"></i></button>
                </div>
                <div class="field-error" id="passwordError"><i class="fa-solid fa-circle-exclamation"></i><span></span></div>
                <div class="pw-rules" id="pwRules">
                    <div class="pw-rule" data-rule="length"><span class="pw-rule-icon"><i class="fa-solid fa-check"></i></span><span>8 caracteres mínimo</span></div>
                    <div class="pw-rule" data-rule="upper"><span class="pw-rule-icon"><i class="fa-solid fa-check"></i></span><span>Una mayúscula</span></div>
                    <div class="pw-rule" data-rule="lower"><span class="pw-rule-icon"><i class="fa-solid fa-check"></i></span><span>Una minúscula</span></div>
                    <div class="pw-rule" data-rule="number"><span class="pw-rule-icon"><i class="fa-solid fa-check"></i></span><span>Un número</span></div>
                    <div class="pw-rule" data-rule="special"><span class="pw-rule-icon"><i class="fa-solid fa-check"></i></span><span>Un carácter especial</span></div>
                </div>
                <div class="str-wrap" id="strWrap">
                    <div class="str-track"><div class="str-fill" id="strFill"></div></div>
                    <span class="str-label" id="strLabel">—</span>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
                <label class="chk-wrap"><input type="checkbox" name="remember"><span class="chk-box"><i class="fa-solid fa-check"></i></span><span>Recordar sesión</span></label>
                <a href="login.php?action=recovery" class="link" onclick="return false" id="forgotLink">Recuperar acceso</a>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="height:52px;font-size:15px"><span class="btn-text">Acceder al sistema</span><div class="spinner"></div></button>
        </form>

        <div class="divider"><span>O</span></div>
        <p style="text-align:center;font-size:13.5px;color:var(--fg-muted)">No tienes una cuenta? <a href="register.php" class="link" style="font-weight:600">Solicitar acceso</a></p>
    </section>
</div>