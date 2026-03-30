<?php
/**
 * Estilos compartidos — incluye solo una vez por página.
 * No imprimir directamente, usar en <style> de cada página.
 */
?>
:root{
    --bg:#090909;--bg-surface:#0f0f0f;--bg-card:#151515;--bg-input:#1a1a1a90;
    --fg:#f0f0f0;--fg-muted:#8a8a8a;--fg-dim:#505050;
    --accent:#f97316;--accent-glow:rgba(249,115,22,.12);--accent-hover:#fb923c;
    --error:#ef4444;--error-glow:rgba(239,68,68,.1);--warning:#f59e0b;--success:#f97316;
    --border:#232323;--border-focus:#f97316;
    --radius:12px;--radius-sm:8px;
    --shadow-card:0 24px 80px rgba(0,0,0,.6),0 4px 20px rgba(0,0,0,.4);
    --transition:.3s cubic-bezier(.4,0,.2,1);
    --sidebar-w:260px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{height:100%}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--fg);min-height:100vh;position:relative}
a{color:var(--accent);text-decoration:none}

/* ── Fondo animado ── */
.bg-layer{position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none}
.bg-layer::before{content:'';position:absolute;width:650px;height:650px;background:radial-gradient(circle,rgba(249,115,22,.06) 0%,transparent 70%);top:-180px;right:-120px;animation:fb1 20s ease-in-out infinite}
.bg-layer::after{content:'';position:absolute;width:500px;height:500px;background:radial-gradient(circle,rgba(249,115,22,.035) 0%,transparent 70%);bottom:-200px;left:-120px;animation:fb2 24s ease-in-out infinite}
@keyframes fb1{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(-90px,70px) scale(1.12)}66%{transform:translate(50px,-50px) scale(.92)}}
@keyframes fb2{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(70px,-90px) scale(1.18)}}
.grid-pattern{position:absolute;inset:0;background-image:linear-gradient(var(--border) 1px,transparent 1px),linear-gradient(90deg,var(--border) 1px,transparent 1px);background-size:80px 80px;opacity:.06;mask-image:radial-gradient(ellipse 60% 60% at 50% 50%,black 20%,transparent 100%);-webkit-mask-image:radial-gradient(ellipse 60% 60% at 50% 50%,black 20%,transparent 100%)}
#particleCanvas{position:absolute;inset:0;width:100%;height:100%}

/* ── Animaciones ── */
@keyframes cardEntry{from{opacity:0;transform:translateY(40px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes spin{to{transform:rotate(360deg)}}
@keyframes shake{0%,100%{transform:translateX(0)}15%{transform:translateX(-8px)}30%{transform:translateX(6px)}45%{transform:translateX(-5px)}60%{transform:translateX(4px)}75%{transform:translateX(-2px)}90%{transform:translateX(1px)}}
@keyframes otpShake{0%,100%{transform:translateX(0)}20%{transform:translateX(-6px)}40%{transform:translateX(5px)}60%{transform:translateX(-3px)}80%{transform:translateX(2px)}}
@keyframes toastIn{from{opacity:0;transform:translateX(40px) scale(.95)}to{opacity:1;transform:translateX(0) scale(1)}}
@keyframes toastOut{to{opacity:0;transform:translateX(40px) scale(.95)}}
@keyframes toastTimer{from{width:100%}to{width:0%}}
@keyframes statIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes brandGlow{from{opacity:.4;transform:scale(.85)}to{opacity:1;transform:scale(1.25)}}

/* ── Toast ── */
.toast-container{position:fixed;top:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:10px}
.toast{display:flex;align-items:flex-start;gap:12px;padding:14px 20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:0 12px 40px rgba(0,0,0,.5);min-width:300px;max-width:440px;animation:toastIn .4s cubic-bezier(.16,1,.3,1) both;position:relative;overflow:hidden}
.toast.removing{animation:toastOut .3s ease forwards}
.toast::before{content:'';position:absolute;bottom:0;left:0;height:3px;background:var(--accent);animation:toastTimer 6s linear forwards}
.toast.toast-error::before{background:var(--error)}.toast.toast-warning::before{background:var(--warning)}.toast.toast-info::before{background:#fb923c}
.toast-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.toast-success .toast-icon{background:rgba(249,115,22,.1);color:#f97316}
.toast-error .toast-icon{background:var(--error-glow);color:var(--error)}
.toast-warning .toast-icon{background:rgba(245,158,11,.1);color:var(--warning)}
.toast-info .toast-icon{background:rgba(251,146,60,.1);color:#fb923c}
.toast-content{flex:1;min-width:0}
.toast-title{font-size:13px;font-weight:600;color:var(--fg);margin-bottom:2px}
.toast-message{font-size:12px;color:var(--fg-muted);line-height:1.5}
.toast-close{background:none;border:none;color:var(--fg-dim);cursor:pointer;padding:4px;font-size:12px;transition:color .2s;flex-shrink:0}
.toast-close:hover{color:var(--fg-muted)}

/* ── Inputs / Fields ── */
.field-group{margin-bottom:20px;position:relative}
.field-label{display:block;font-size:12px;font-weight:500;color:var(--fg-muted);margin-bottom:7px;text-transform:uppercase;letter-spacing:.8px}
.field-wrapper{position:relative}
.field-wrapper i.field-icon{position:absolute;left:16px;top:50%;transform:translateY(-50%);font-size:14px;color:var(--fg-dim);transition:color var(--transition);pointer-events:none}
.field-input{width:100%;height:48px;padding:0 16px 0 44px;background:var(--bg-input);border:1.5px solid var(--border);border-radius:var(--radius-sm);color:var(--fg);font-family:'DM Sans',sans-serif;font-size:14px;outline:none;transition:all var(--transition)}
.field-input::placeholder{color:var(--fg-dim)}
.field-input:focus{border-color:var(--border-focus);background:rgba(249,115,22,.02);box-shadow:0 0 0 3px var(--accent-glow)}
.field-wrapper:focus-within i.field-icon{color:var(--accent)}
.field-group.has-error .field-input{border-color:var(--error);box-shadow:0 0 0 3px var(--error-glow)}
.field-group.has-error .field-icon{color:var(--error)!important}
.field-error{font-size:12px;color:var(--error);margin-top:5px;display:flex;align-items:center;gap:5px;opacity:0;max-height:0;overflow:hidden;transition:all .25s}
.field-group.has-error .field-error{opacity:1;max-height:30px}
.field-input.no-icon{padding-left:16px}
.toggle-password{position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--fg-dim);cursor:pointer;padding:4px;font-size:14px;transition:color .2s;z-index:2}
.toggle-password:hover{color:var(--fg-muted)}

/* ── Password rules ── */
.pw-rules{margin-top:10px;padding:12px 14px;background:rgba(0,0,0,.25);border-radius:var(--radius-sm);border:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:6px 14px;opacity:0;max-height:0;overflow:hidden;transition:all .35s}
.pw-rules.visible{opacity:1;max-height:200px}
.pw-rule{display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--fg-dim);transition:color .3s}
.pw-rule .pw-rule-icon{width:16px;height:16px;border-radius:50%;border:1.5px solid var(--fg-dim);display:flex;align-items:center;justify-content:center;font-size:8px;transition:all .3s;flex-shrink:0}
.pw-rule.pass{color:var(--success)}
.pw-rule.pass .pw-rule-icon{border-color:var(--success);background:var(--success);color:var(--bg)}
.str-wrap{margin-top:8px;display:flex;align-items:center;gap:10px;opacity:0;max-height:0;overflow:hidden;transition:all .35s}
.str-wrap.visible{opacity:1;max-height:30px}
.str-track{flex:1;height:4px;background:var(--border);border-radius:4px;overflow:hidden}
.str-fill{height:100%;width:0%;border-radius:4px;transition:width .4s,background .4s}
.str-label{font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.5px;min-width:60px;text-align:right;transition:color .3s}

/* ── Botones ── */
.btn{height:48px;border:none;border-radius:var(--radius-sm);font-family:'DM Sans',sans-serif;font-size:14px;font-weight:600;cursor:pointer;transition:all var(--transition);display:inline-flex;align-items:center;justify-content:center;gap:8px;position:relative;overflow:hidden}
.btn-primary{background:linear-gradient(135deg,#f97316,#ea580c);color:#fff}
.btn-primary::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,transparent 0%,rgba(255,255,255,.12) 50%,transparent 100%);transform:translateX(-100%);transition:transform .6s}
.btn-primary:hover::before{transform:translateX(100%)}
.btn-primary:hover{box-shadow:0 6px 32px rgba(249,115,22,.35);transform:translateY(-1px)}
.btn-primary:disabled{opacity:.45;cursor:not-allowed;transform:none;box-shadow:none}
.btn-primary:disabled::before{display:none}
.btn-primary .spinner{width:18px;height:18px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;display:none}
.btn-primary.loading .spinner{display:block}
.btn-primary.loading .btn-text{display:none}
.btn-full{width:100%}
.btn-ghost{background:none;border:1px solid var(--border);color:var(--fg-muted)}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent)}

/* ── Link ── */
.link{font-size:13px;color:var(--accent);font-weight:500;cursor:pointer;transition:color .2s;background:none;border:none;padding:0;font-family:inherit}
.link:hover{color:var(--accent-hover)}

/* ── Checkbox ── */
.chk-wrap{display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none}
.chk-wrap input{display:none}
.chk-box{width:18px;height:18px;border-radius:5px;border:1.5px solid var(--border);background:var(--bg-input);display:flex;align-items:center;justify-content:center;transition:all var(--transition);flex-shrink:0}
.chk-box i{font-size:10px;color:var(--bg);opacity:0;transform:scale(.5);transition:all .2s}
.chk-wrap input:checked+.chk-box{background:var(--accent);border-color:var(--accent)}
.chk-wrap input:checked+.chk-box i{opacity:1;transform:scale(1)}
.chk-wrap span{font-size:13px;color:var(--fg-muted)}

/* ── Form row (2 columnas) ── */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}

/* ── Divisor ── */
.divider{display:flex;align-items:center;gap:16px;margin:22px 0}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border)}
.divider span{font-size:12px;color:var(--fg-dim);text-transform:uppercase;letter-spacing:1px}

/* ── Card base ── */
.card{background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-card);animation:cardEntry .8s cubic-bezier(.16,1,.3,1) both}

/* ── Focus visible ── */
.field-input:focus-visible,.btn:focus-visible,.link:focus-visible{outline:2px solid var(--accent);outline-offset:2px}

/* ── Reduced motion ── */
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important}}

/* ── Responsive ── */
@media(max-width:768px){
    .form-row{grid-template-columns:1fr}
    .card-split{flex-direction:column!important}
    .brand-side{display:none!important}
}