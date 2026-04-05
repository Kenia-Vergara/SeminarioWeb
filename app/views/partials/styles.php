<?php
/**
 * Sistema de Diseño — Apex Solutions
 * Paleta cósmica para login/register/otp | Dark premium para dashboard.
 */
?>
:root {
    /* Paleta Cósmica (Login / Auth) */
    --cosmic-bg: #030305;
    --cosmic-accent: #A855F7;
    --cosmic-accent-glow: rgba(168, 85, 247, 0.35);
    --cosmic-btn: linear-gradient(90deg, #A855F7 0%, #7C3AED 100%);

    /* Paleta Dashboard */
    --dash-bg: #09090B;
    --dash-surface: #111113;
    --dash-surface-2: #18181B;
    --dash-border: #27272A;
    --dash-accent: #A855F7;
    --dash-accent-muted: rgba(168, 85, 247, 0.08);
    --dash-teal: #4FD1C5;
    --dash-teal-muted: rgba(79, 209, 197, 0.08);

    /* Semantics */
    --color-success: #22C55E;
    --color-warning: #F59E0B;
    --color-danger:  #EF4444;
    --color-info:    #3B82F6;

    /* Neutros */
    --fg:       #F4F4F5;
    --fg-muted: #A1A1AA;
    --fg-dim:   #52525B;

    --radius: 12px;
    --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'DM Sans', sans-serif;
    color: var(--fg);
    background: var(--dash-bg);
    min-height: 100vh;
    overflow-x: hidden;
}

/* ══════════════════════════════════════════
   COSMIC AUTH LAYOUT (Login / Register / OTP)
   ══════════════════════════════════════════ */

.cosmic-body {
    background: var(--cosmic-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    overflow: hidden;
}

.cosmic-bg-wrap {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
}

.planet {
    position: absolute;
    top: -20%;
    right: -10%;
    width: 70vw;
    height: 70vw;
    background: radial-gradient(circle at 30% 30%, #3B0764 0%, #030305 70%);
    border-radius: 50%;
    box-shadow: -20px 20px 100px rgba(168, 85, 247, 0.15);
}

.planet-glow {
    position: absolute;
    top: 50%;
    left: 10%;
    width: 80%;
    height: 80%;
    background: linear-gradient(135deg, transparent 40%, var(--cosmic-accent) 100%);
    border-radius: 50%;
    opacity: 0.5;
    filter: blur(48px);
}

/* Fondo de grilla sutil (register / otp) */
.bg-layer {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
    background: var(--cosmic-bg);
}

.grid-pattern {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(168,85,247,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(168,85,247,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
}

.bg-layer::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(168,85,247,0.12) 0%, transparent 70%);
}

/* ── Cards de autenticación ── */
.glass-card {
    background: rgba(17, 17, 19, 0.80);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: var(--radius);
    padding: 48px;
    width: 100%;
    max-width: 440px;
    position: relative;
    z-index: 10;
    box-shadow: 0 25px 60px -12px rgba(0,0,0,0.6);
}

/* Card genérica (register / otp) */
.card {
    background: rgba(17, 17, 19, 0.85);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: var(--radius);
    padding: 40px;
    position: relative;
    z-index: 10;
    box-shadow: 0 25px 60px -12px rgba(0,0,0,0.6);
}

/* ── Fila de dos columnas ── */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* ── Divisor ── */
.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: var(--fg-dim);
    font-size: 12px;
}
.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--dash-border);
}

/* ── Link ── */
a.link {
    color: var(--cosmic-accent);
    text-decoration: none;
    transition: var(--transition);
}
a.link:hover { opacity: 0.8; text-decoration: underline; }

/* ══════════════════════════════════════════
   DASHBOARD LAYOUT
   ══════════════════════════════════════════ */

.dash-layout {
    display: flex;
    height: 100vh;
    overflow: hidden;
}

.sidebar {
    width: 240px;
    background: var(--dash-surface);
    border-right: 1px solid var(--dash-border);
    display: flex;
    flex-direction: column;
    padding: 20px;
    flex-shrink: 0;
    gap: 0;
}

.content-area {
    flex: 1;
    padding: 28px 32px;
    overflow-y: auto;
    background: var(--dash-bg);
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--fg-muted);
    font-size: 13.5px;
    text-decoration: none;
    transition: var(--transition);
    margin-bottom: 2px;
}
.nav-item:hover, .nav-item.active {
    background: var(--dash-accent-muted);
    color: var(--dash-accent);
}

.stat-card {
    background: var(--dash-surface);
    border: 1px solid var(--dash-border);
    border-radius: var(--radius);
    padding: 20px 24px;
}

.grid-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

/* ── Componentes Comunes ── */
.field-group { margin-bottom: 20px; }
.field-label {
    display: block;
    font-size: 12.5px;
    color: var(--fg-muted);
    margin-bottom: 7px;
    font-weight: 500;
}
.field-input {
    width: 100%;
    height: 46px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 8px;
    padding: 0 14px;
    color: var(--fg);
    font-size: 14px;
    outline: none;
    transition: var(--transition);
    font-family: inherit;
}
.field-input:focus {
    border-color: var(--cosmic-accent);
    box-shadow: 0 0 0 3px var(--cosmic-accent-glow);
}
.field-input::placeholder { color: var(--fg-dim); }

.btn-primary {
    width: 100%;
    height: 48px;
    background: var(--cosmic-btn);
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    font-family: inherit;
    letter-spacing: 0.3px;
}
.btn-primary:hover { opacity: 0.88; transform: translateY(-1px); }

/* Alias .btn.btn-primary para register */
.btn { display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; font-family: inherit; font-weight: 600; cursor: pointer; transition: var(--transition); border: none; }
.btn-full { width: 100%; }

/* ── Badges de severidad ── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.badge-info     { background: rgba(59,130,246,0.12); color: #60A5FA; }
.badge-warning  { background: rgba(245,158,11,0.12); color: #FBBF24; }
.badge-critical { background: rgba(239,68,68,0.12);  color: #F87171; }
.badge-success  { background: rgba(34,197,94,0.12);  color: #4ADE80; }

/* ── Toast ── */
.toast-container { position: fixed; top: 20px; right: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 8px; }
.toast {
    background: var(--dash-surface);
    border: 1px solid var(--dash-border);
    padding: 14px 18px;
    border-radius: 10px;
    min-width: 260px;
    max-width: 360px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    display: flex;
    align-items: flex-start;
    gap: 12px;
    opacity: 1;
    transform: translateX(0);
    transition: opacity 0.3s, transform 0.3s;
}
.toast.removing { opacity: 0; transform: translateX(20px); }
.toast-icon { font-size: 15px; margin-top: 1px; }
.toast-title { font-size: 13px; font-weight: 600; margin-bottom: 2px; }
.toast-message { font-size: 12px; color: var(--fg-muted); }
.toast-close { margin-left: auto; background: none; border: none; color: var(--fg-dim); cursor: pointer; padding: 0; font-size: 12px; flex-shrink: 0; }

/* ── Animaciones ── */
@keyframes fadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
.animate-in { animation: fadeIn 0.5s ease-out forwards; }

@keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }
.pulse { animation: pulse 2s infinite; }
