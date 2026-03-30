<?php
/** @var string $csrf */
/** @var string|null $flashError */
/** @var array $formErrors */
/** @var array $formData */
?>
<div class="card" style="max-width:560px;width:95vw;margin:auto;padding:44px;position:relative;z-index:10">
    <header style="text-align:center;margin-bottom:32px">
        <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,rgba(249,115,22,.12),rgba(249,115,22,.03));border:1px solid rgba(249,115,22,.12);display:flex;align-items:center;justify-content:center;font-size:24px;color:var(--accent);margin:0 auto 20px"><i class="fa-solid fa-user-plus"></i></div>
        <h1 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--fg);margin-bottom:8px">Crear cuenta</h1>
        <p style="font-size:14px;color:var(--fg-muted)">Completa el formulario para solicitar acceso a la plataforma</p>
    </header>

    <?php if ($formErrors): ?>
    <div style="background:var(--error-glow);border:1px solid rgba(239,68,68,.2);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px">
        <?php foreach ($formErrors as $err): ?>
        <p style="font-size:12.5px;color:var(--error);margin-bottom:4px"><i class="fa-solid fa-circle-exclamation" style="margin-right:6px"></i><?= Security::sanitize($err) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="register.php" novalidate>
        <?= $csrf ?>

        <div class="form-row">
            <div class="field-group">
                <label class="field-label" for="company_name">Razón social</label>
                <div class="field-wrapper">
                    <i class="fa-solid fa-building field-icon"></i>
                    <input type="text" id="company_name" name="company_name" class="field-input" placeholder="Empresa S.A.S" value="<?= htmlspecialchars($formData['company_name'] ?? '') ?>" required>
                </div>
            </div>
            <div class="field-group">
                <label class="field-label" for="company_nit">NIT</label>
                <div class="field-wrapper">
                    <i class="fa-solid fa-hashtag field-icon"></i>
                    <input type="text" id="company_nit" name="company_nit" class="field-input" placeholder="900123456-1" value="<?= htmlspecialchars($formData['company_nit'] ?? '') ?>" required>
                </div>
            </div>
        </div>

        <div class="field-group">
            <label class="field-label" for="full_name">Nombre completo</label>
            <div class="field-wrapper">
                <i class="fa-solid fa-user field-icon"></i>
                <input type="text" id="full_name" name="full_name" class="field-input" placeholder="Carlos Andrés Mendoza" value="<?= htmlspecialchars($formData['full_name'] ?? '') ?>" required>
            </div>
        </div>

        <div class="field-group">
            <label class="field-label" for="reg_email">Correo electrónico</label>
            <div class="field-wrapper">
                <i class="fa-regular fa-envelope field-icon"></i>
                <input type="email" id="reg_email" name="email" class="field-input" placeholder="usuario@empresa.com" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
            </div>
        </div>

        <div class="field-group">
            <label class="field-label" for="department">Departamento</label>
            <div class="field-wrapper">
                <i class="fa-solid fa-sitemap field-icon"></i>
                <input type="text" id="department" name="department" class="field-input" placeholder="Tecnología, Finanzas, RH..." value="<?= htmlspecialchars($formData['department'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="field-group">
                <label class="field-label" for="reg_password">Contraseña</label>
                <div class="field-wrapper">
                    <i class="fa-solid fa-key field-icon"></i>
                    <input type="password" id="reg_password" name="password" class="field-input" placeholder="Mínimo 8 caracteres" required style="padding-right:48px">
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña" data-target="reg_password"><i class="fa-regular fa-eye"></i></button>
                </div>
            </div>
            <div class="field-group">
                <label class="field-label" for="confirm_password">Confirmar contraseña</label>
                <div class="field-wrapper">
                    <i class="fa-solid fa-key field-icon"></i>
                    <input type="password" id="confirm_password" name="confirm_password" class="field-input" placeholder="Repetir contraseña" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="height:52px;font-size:15px;margin-top:8px"><span class="btn-text">Crear cuenta</span><div class="spinner"></div></button>
    </form>

    <div class="divider"><span>O</span></div>
    <p style="text-align:center;font-size:13.5px;color:var(--fg-muted)">Ya tienes una cuenta? <a href="login.php" class="link" style="font-weight:600">Iniciar sesión</a></p>
</div>