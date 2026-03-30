<?php
/**
 * Sistema de Diseño — SecureAuth Enterprise
 * Basado en estéticas espaciales (Cósmico) y dashboards premium (Teal/Dark).
 */
?>
:root {
    /* Paleta Cósmica (Login) */
    --cosmic-bg: #030305;
    --cosmic-accent: #A855F7;
    --cosmic-accent-glow: rgba(168, 85, 247, 0.4);
    --cosmic-btn: linear-gradient(90deg, #A855F7 0%, #7C3AED 100%);
    
    /* Paleta Dashboard (Teal/Dark) */
    --dash-bg: #09090B;
    --dash-surface: #121214;
    --dash-border: #1E1E22;
    --dash-accent: #4FD1C5;
    --dash-accent-muted: rgba(79, 209, 197, 0.1);
    
    /* Neutros */
    --fg: #F4F4F5;
    --fg-muted: #A1A1AA;
    --fg-dim: #52525B;
    
    --radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'DM Sans', sans-serif;
    color: var(--fg);
    background: var(--dash-bg);
    min-height: 100vh;
    overflow-x: hidden;
}

/* ── Estilos de Login (Cósmico) ── */
.cosmic-body {
    background: var(--cosmic-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.cosmic-bg-wrap {
    position: fixed;
    inset: 0;
    z-index: 0;
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
    opacity: 0.6;
    filter: blur(40px);
}

.glass-card {
    background: rgba(18, 18, 22, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: var(--radius);
    padding: 48px;
    width: 100%;
    max-width: 420px;
    position: relative;
    z-index: 10;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* ── Estilos Dashboard (Teal/Clean) ── */
.dash-layout {
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 260px;
    background: var(--dash-surface);
    border-right: 1px solid var(--dash-border);
    display: flex;
    flex-direction: column;
    padding: 24px;
    flex-shrink: 0;
}

.content-area {
    flex: 1;
    padding: 32px;
    overflow-y: auto;
    background: var(--dash-bg);
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    color: var(--fg-muted);
    font-size: 14px;
    transition: var(--transition);
    margin-bottom: 4px;
}

.nav-item:hover, .nav-item.active {
    background: var(--dash-accent-muted);
    color: var(--dash-accent);
}

.stat-card {
    background: var(--dash-surface);
    border: 1px solid var(--dash-border);
    border-radius: var(--radius);
    padding: 24px;
}

.grid-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

/* ── Componentes Comunes ── */
.field-group { margin-bottom: 24px; }
.field-label { display: block; font-size: 13px; color: var(--fg-muted); margin-bottom: 8px; }
.field-input {
    width: 100%;
    height: 48px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 0 16px;
    color: #fff;
    outline: none;
    transition: var(--transition);
}
.field-input:focus { border-color: var(--cosmic-accent); box-shadow: 0 0 0 4px var(--cosmic-accent-glow); }

.btn-primary {
    width: 100%;
    height: 48px;
    background: var(--cosmic-btn);
    border: none;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}
.btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

/* Toast */
.toast-container { position: fixed; top: 24px; right: 24px; z-index: 1000; }
.toast { background: var(--dash-surface); border: 1px solid var(--dash-border); padding: 16px 24px; border-radius: 8px; margin-bottom: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }

/* ── Animaciones ── */
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-in { animation: fadeIn 0.6s ease-out forwards; }
