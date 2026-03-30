<?php
// ─── SERVICIO: EMAIL ─────────────────────────────────
class EmailService {
    public function sendOTP(string $to, string $name, string $code, string $purpose): bool {
        if (EMAIL_MODE === 'debug') {
            AppSession::set('debug_otp', $code);
            AppSession::set('debug_email', $to);
            error_log("[DEBUG OTP] {$to}: {$code}");
            return true;
        }

        if (EMAIL_MODE === 'smtp' && class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $m = new \PHPMailer\PHPMailer\PHPMailer(true);
                $m->isSMTP();
                $m->Host = SMTP_HOST;
                $m->Port = SMTP_PORT;
                $m->SMTPAuth = true;
                $m->Username = SMTP_USER;
                $m->Password = SMTP_PASS;
                $m->SMTPSecure = 'tls';
                $m->CharSet = 'UTF-8';
                $m->setFrom(SMTP_FROM, SMTP_FROM_NAME);
                $m->addAddress($to, $name);
                $m->Subject = match ($purpose) {
                    'verification' => 'Verifique su cuenta — ' . APP_NAME,
                    'login' => 'Código de acceso — ' . APP_NAME,
                    default => 'Código de seguridad — ' . APP_NAME
                };
                $m->Body = $this->htmlBody($name, $code, $purpose);
                $m->isHTML(true);
                $m->send();
                return true;
            } catch (\Exception $e) {
                error_log("SMTP Error: " . $e->getMessage());
                return false;
            }
        }

        $subj = 'Código OTP — ' . APP_NAME;
        $hdrs = "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8";
        return mail($to, $subj, $this->htmlBody($name, $code, $purpose), $hdrs);
    }

    private function htmlBody(string $name, string $code, string $purpose): string {
        $txt = match ($purpose) {
            'verification' => 'verificar tu cuenta',
            'login' => 'iniciar sesión',
            default => 'completar la acción'
        };
        return "<!DOCTYPE html><html><head><meta charset='UTF-8'></head><body style='margin:0;padding:0;background:#060B18;font-family:system-ui,sans-serif'>
<table width='100%' cellpadding='0' cellspacing='0' style='max-width:560px;margin:40px auto;background:#111827;border-radius:12px;overflow:hidden;border:1px solid #1E293B'>
<tr><td style='padding:28px;background:linear-gradient(135deg,#0D1425,#162038);text-align:center'><h1 style='margin:0;color:#00D4AA;font-size:22px;font-weight:700'>SecureAuth Enterprise</h1></td></tr>
<tr><td style='padding:28px'><p style='color:#E2E8F0;font-size:15px;margin:0 0 6px'>Hola, <strong>{$name}</strong></p>
<p style='color:#94A3B8;font-size:13px;margin:0 0 20px'>Usa este código para {$txt}. Expira en 5 minutos.</p>
<div style='background:#0A0F1E;border:2px dashed #00D4AA;border-radius:8px;padding:18px;text-align:center;margin:20px 0'>
<span style='color:#00D4AA;font-size:34px;font-weight:700;letter-spacing:8px;font-family:monospace'>{$code}</span></div>
<p style='color:#475569;font-size:11px;margin:0'>Si no solicitaste esto, ignora este correo.</p></td></tr>
<tr><td style='padding:14px 28px;background:#0D1425;text-align:center'><p style='color:#475569;font-size:10px;margin:0'>&copy; " . date('Y') . " SecureAuth Enterprise</p></td></tr></table></body></html>";
    }
}
