<?php
/**
 * Envío de OTP por correo.
 * Requiere: composer require phpmailer/phpmailer
 */
class EmailService {

    public function sendOTP(string $to, string $name, string $code, string $purpose): bool {
        /* Modo debug: guarda OTP en sesión para mostrarlo en la vista */
        if (EMAIL_MODE === 'debug') {
            AppSession::set('debug_otp', $code);
            AppSession::set('debug_email', $to);
            error_log("[DEBUG OTP] {$to}: {$code}");
            return true;
        }

        /* Modo SMTP con PHPMailer */
        if (EMAIL_MODE === 'smtp') {
            /* Verificar que PHPMailer esté instalado via composer */
            if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
                error_log("ERROR: PHPMailer no encontrado. Ejecuta: composer require phpmailer/phpmailer");
                return false;
            }

            try {
                $m = new \PHPMailer\PHPMailer\PHPMailer(true);
                $m->isSMTP();
                $m->Host       = SMTP_HOST;
                $m->Port       = SMTP_PORT;
                $m->SMTPAuth   = true;
                $m->Username   = SMTP_USER;
                $m->Password   = SMTP_PASS;
                $m->SMTPSecure = 'tls';
                $m->CharSet    = 'UTF-8';
                $m->setFrom(SMTP_FROM, SMTP_FROM_NAME);
                $m->addAddress($to, $name);
                $m->Subject  = $this->subject($purpose);
                $m->Body     = $this->htmlBody($name, $code, $purpose);
                $m->isHTML(true);
                $m->send();
                return true;
            } catch (\Exception $e) {
                error_log("SMTP Error: " . $e->getMessage());
                return false;
            }
        }

        /* Fallback: mail() nativo */
        $headers = "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">\r\n"
                 . "MIME-Version: 1.0\r\n"
                 . "Content-Type: text/html; charset=UTF-8";
        return mail($to, $this->subject($purpose), $this->htmlBody($name, $code, $purpose), $headers);
    }

    private function subject(string $purpose): string {
        return match ($purpose) {
            'verification' => 'Verifica tu cuenta — ' . APP_NAME,
            'login'        => 'Código de acceso — ' . APP_NAME,
            default        => 'Código de seguridad — ' . APP_NAME
        };
    }

    private function htmlBody(string $name, string $code, string $purpose): string {
        $action = match ($purpose) {
            'verification' => 'verificar tu cuenta',
            'login'        => 'iniciar sesión',
            default        => 'completar la acción'
        };

        return <<<HTML
<!DOCTYPE html><html><head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#090909;font-family:Arial,Helvetica,sans-serif;">
<div style="max-width:520px;margin:40px auto;background:#151515;border:1px solid #232323;border-radius:12px;overflow:hidden;">
  <div style="background:linear-gradient(135deg,#f97316,#ea580c);padding:28px 32px;">
    <h1 style="margin:0;color:#fff;font-size:22px;font-weight:700;">CodeCraft</h1>
  </div>
  <div style="padding:32px;">
    <p style="margin:0 0 6px;color:#f0f0f0;font-size:15px;">Hola, <strong>{$name}</strong></p>
    <p style="margin:0 0 20px;color:#8a8a8a;font-size:13px;line-height:1.6;">Usa este código para {$action}. Expira en 5 minutos.</p>
    <div style="background:#1a1a1a;border:2px dashed #f97316;border-radius:8px;padding:20px;text-align:center;margin:20px 0;">
      <p style="margin:0 0 6px;color:#505050;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Tu código</p>
      <p style="margin:0;color:#f97316;font-size:34px;font-weight:700;letter-spacing:8px;font-family:monospace;">{$code}</p>
    </div>
    <p style="margin:0;color:#505050;font-size:11px;">Si no solicitaste esto, ignora este correo.</p>
  </div>
  <div style="padding:16px 32px;border-top:1px solid #232323;text-align:center;">
    <p style="margin:0;color:#505050;font-size:10px;">&copy; " . date('Y') . " CodeCraft — Todos los derechos reservados</p>
  </div>
</div>
</body></html>
HTML;
    }
}