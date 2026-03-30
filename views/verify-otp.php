<?php
/** @var array $user */
/** @var string $mode — 'verification' o 'login' */
/** @var bool $canResend */
/** @var int $cooldown */
/** @var string|null $debugOTP */
/** @var string|null $debugEmail */
/** @var string $csrf */
/** @var string|null $flashError */
/** @var string|null $flashSuccess */

 $maskedEmail = substr($user['email'], 0, 1) . str_repeat('*', max(1, strlen(explode('@', $user['email'])[0]) - 1)) . '@' . explode('@', $user['email'])[1];
 $isVerification = $mode === 'verification';
 $title = $isVerification ? 'Verificar cuenta' : 'Verificación en dos pasos';
 $desc  = $isVerification
    ? 'Confirma tu correo electrónico para activar tu cuenta.'
    : 'Ingresa el código que enviamos a tu correo para completar el acceso.';
?>
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
            <?= $csrf ?>
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

<style>
.otp-digit{width:52px;height:62px;text-align:center;font-family:'Space Grotesk',monospace;font-size:24px;font-weight:700;color:var(--fg);background:var(--bg-input);border:1.5px solid var(--border);border-radius:10px;outline:none;transition:all .2s;caret-color:var(--accent)}
.otp-digit:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow);background:rgba(249,115,22,.02)}
.otp-digit.filled{border-color:rgba(249,115,22,.25)}
.otp-digit.error{border-color:var(--error);box-shadow:0 0 0 3px var(--error-glow);animation:otpShake .4s ease}
@media(max-width:420px){.otp-digit{width:42px;height:52px;font-size:20px}}
</style>