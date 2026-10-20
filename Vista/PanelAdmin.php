<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Be Loyal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: { extend: { fontFamily: { inter: ['Inter', 'sans-serif'] } } }
    }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 2px; }

        /* Custom select dropdown */
        .custom-select-dropdown { transition: opacity .15s, transform .15s; transform-origin: top; }
        .custom-select-dropdown.hidden { opacity: 0; pointer-events: none; transform: scaleY(.96); }
        .custom-select-dropdown:not(.hidden) { opacity: 1; transform: scaleY(1); }

        /* Ocultar flechas de inputs numéricos */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Tablas con scroll horizontal en móvil */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-wrap table { min-width: 500px; }

        /* ---------- Estilo nuevo (el mismo del login) ---------- */
        .bl-bg {
            background-color: #000;
            background-image:
                radial-gradient(ellipse 120% 70% at 50% -10%, #3a3a3a 0%, #1f1f1f 38%, transparent 70%),
                linear-gradient(180deg, #262626 0%, #121212 45%, #050505 80%, #000 100%);
        }
        .glow-text { text-shadow: 0 0 8px rgba(255,255,255,0.22); }
        .logo-glow {
            filter: drop-shadow(0 0 1px rgba(255,255,255,0.6)) drop-shadow(0 0 7px rgba(255,255,255,0.28)) drop-shadow(0 0 18px rgba(255,255,255,0.12));
        }
        .bl-panel {
            background: #171717;
            border: 1px solid #2a2a2a;
            border-radius: .75rem;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.04), 0 0 28px rgba(255,255,255,0.05), 0 30px 60px -20px rgba(0,0,0,0.8);
        }

        /* Borde luminoso que recorre el contorno de las tarjetas de cifras */
        @property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }
        .card-glow { position: relative; }
        .card-glow::before {
            content: "";
            position: absolute;
            inset: -1px;
            padding: 1px;
            border-radius: inherit;
            pointer-events: none;
            background: conic-gradient(from var(--angle), transparent 0deg, rgba(255,255,255,0.8) 60deg, transparent 120deg, transparent 180deg, rgba(255,255,255,0.4) 240deg, transparent 300deg);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
                    mask-composite: exclude;
            animation: border-spin 9s linear infinite;
            animation-delay: var(--delay, 0s);
        }
        @keyframes border-spin { to { --angle: 360deg; } }

        /* Botones de periodo y pestañas */
        .seg-btn {
            padding: .375rem .75rem; font-size: .75rem; border-radius: .5rem; color: #8a8a8a;
            background: linear-gradient(180deg, #232323, #161616); border: 1px solid #2f2f2f;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08);
            transition: color .2s, border-color .2s, box-shadow .2s, transform .12s;
        }
        .seg-btn:hover { color: #f5f5f5; border-color: #4a4a4a; box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 0 10px rgba(255,255,255,.12); text-shadow: 0 0 8px rgba(255,255,255,.4); }
        .seg-btn:active { transform: scale(.96); }
        /* Acción delicada: mismo botón metálico, en rojo */
        .btn-peligro { color: rgba(248,113,113,.8); border-color: rgba(248,113,113,.28); }
        .btn-peligro:hover { color: #fca5a5; border-color: rgba(248,113,113,.6); box-shadow: inset 0 1px 0 rgba(255,255,255,.1), 0 0 12px rgba(248,113,113,.3); text-shadow: 0 0 8px rgba(248,113,113,.5); }
        .btn-ok { color: rgba(52,211,153,.85); border-color: rgba(52,211,153,.28); }
        .btn-ok:hover { color: #a7f3d0; border-color: rgba(52,211,153,.6); box-shadow: inset 0 1px 0 rgba(255,255,255,.1), 0 0 12px rgba(52,211,153,.3); text-shadow: 0 0 8px rgba(52,211,153,.5); }
        /* Formularios: etiquetas con luz y campos hundidos con foco plateado */
        .etiqueta-bl { display: block; margin-bottom: .375rem; font-size: .75rem; color: #b5b5b5; text-shadow: 0 0 8px rgba(255,255,255,.15); }
        .campo-bl {
            width: 100%; height: 2.5rem; padding: 0 .75rem; border-radius: .5rem; font-size: .8125rem; color: #e5e5e5;
            background: #0d0d0d; border: 1px solid #2f2f2f; outline: none;
            box-shadow: inset 0 1px 2px rgba(0,0,0,.8), inset 0 -1px 0 rgba(255,255,255,.04);
            transition: border-color .2s, box-shadow .2s;
        }
        .campo-bl::placeholder { color: #5a5a5a; }
        .campo-bl:hover { border-color: #3f3f3f; }
        .campo-bl:focus, .campo-bl:focus-visible { border-color: #8a8a8a; box-shadow: inset 0 1px 2px rgba(0,0,0,.8), 0 0 0 3px rgba(255,255,255,.08), 0 0 14px rgba(255,255,255,.12); }
        /* Píldoras: duración y estado (con LED) */
        .chip-bl { display: inline-flex; align-items: center; gap: .3rem; padding: .2rem .55rem; border-radius: 9999px; font-size: .6875rem; color: #b5b5b5; background: #1f1f1f; border: 1px solid #2f2f2f; }
        .chip-bl svg { width: 12px; height: 12px; }
        .estado-bl { display: inline-flex; align-items: center; gap: .4rem; font-size: .6875rem; color: #737373; width: 4.75rem; }
        .estado-bl i { width: 7px; height: 7px; border-radius: 9999px; background: #3a3a3a; }
        .estado-bl.is-on { color: #a7f3d0; }
        .estado-bl.is-on i { background: #34d399; box-shadow: 0 0 6px #34d399, 0 0 12px #059669; }
        /* Stock: barra corta y texto; ámbar si queda poco, rojo si está agotado */
        .stock-bl { display: inline-flex; align-items: center; gap: .5rem; width: 9rem; font-size: .6875rem; color: #b5b5b5; }
        .stock-texto { width: 4.25rem; flex-shrink: 0; text-align: right; }
        .stock-barra { flex: 1; height: 6px; padding: 1px; border-radius: 9999px; background: #0d0d0d; box-shadow: inset 0 1px 2px rgba(0,0,0,.8); }
        .stock-barra i { display: block; height: 100%; border-radius: 9999px; background: linear-gradient(180deg, #fff, #9a9a9a); }
        .stock-poco .stock-texto { color: #fcd34d; }
        .stock-poco .stock-barra i { background: #f59e0b; box-shadow: 0 0 6px rgba(245,158,11,.6); }
        .stock-agotado .stock-texto { color: #fca5a5; }
        .stock-agotado .stock-barra i { width: 100% !important; background: rgba(248,113,113,.25); }
        /* Aviso de reposición: ámbar, con la bebida y lo que queda; las agotadas en rojo */
        .aviso-reponer {
            display: flex; flex-wrap: wrap; align-items: center; gap: .5rem .75rem; padding: .625rem .875rem; border-radius: .75rem;
            background: linear-gradient(180deg, rgba(245,158,11,.10), rgba(245,158,11,.04)); border: 1px solid rgba(245,158,11,.35);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.06), 0 0 18px rgba(245,158,11,.12);
        }
        .aviso-icono { width: 18px; height: 18px; flex-shrink: 0; color: #fbbf24; filter: drop-shadow(0 0 5px rgba(245,158,11,.7)); animation: aviso-latido 2.4s ease-in-out infinite; }
        @keyframes aviso-latido { 50% { filter: drop-shadow(0 0 10px rgba(245,158,11,1)); } }
        .aviso-titulo { font-size: .8125rem; font-weight: 500; color: #fde68a; text-shadow: 0 0 8px rgba(245,158,11,.45); }
        .aviso-chip { white-space: nowrap; padding: .15rem .55rem; border-radius: 9999px; font-size: .6875rem; color: #fde68a; background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3); }
        .aviso-chip.is-agotado { color: #fecaca; background: rgba(248,113,113,.12); border-color: rgba(248,113,113,.4); }
        /* Avatar: foto del empleado o su inicial en un círculo de plata */
        .avatar-bl {
            position: relative; display: grid; place-items: center; width: 38px; height: 38px; flex-shrink: 0; border-radius: 9999px; overflow: hidden;
            font-size: .875rem; font-weight: 600; color: #0a0a0a;
            background: linear-gradient(135deg, #ffffff 0%, #c4c4c4 40%, #7a7a7a 60%, #e5e5e5 100%);
            box-shadow: 0 0 0 2px #171717, 0 0 0 3px rgba(255,255,255,.22), 0 0 12px rgba(255,255,255,.18);
        }
        .avatar-bl img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .avatar-bl svg { width: 55%; height: 55%; color: #3a3a3a; }
        .avatar-grande { width: 64px; height: 64px; font-size: 1.5rem; }
        /* Casilla: interruptor pequeño con LED verde al marcarla */
        .check-bl { display: inline-flex; align-items: center; gap: .5rem; font-size: .75rem; color: #b5b5b5; cursor: pointer; user-select: none; }
        .check-bl input { position: absolute; opacity: 0; width: 1px; height: 1px; }
        .check-bl span { position: relative; width: 30px; height: 17px; border-radius: 9999px; background: #0d0d0d; border: 1px solid #333; box-shadow: inset 0 1px 2px rgba(0,0,0,.8); transition: border-color .2s; }
        .check-bl span::after { content: ""; position: absolute; top: 2px; left: 2px; width: 11px; height: 11px; border-radius: 9999px; background: linear-gradient(180deg, #8a8a8a, #4a4a4a); transition: transform .2s, background .2s, box-shadow .2s; }
        .check-bl input:checked + span { border-color: rgba(52,211,153,.5); }
        .check-bl input:checked + span::after { transform: translateX(13px); background: #34d399; box-shadow: 0 0 6px #34d399, 0 0 12px #059669; }
        .check-bl input:focus-visible + span { outline: 2px solid #8a8a8a; outline-offset: 2px; }
        .sep-vertical { width: 1px; height: 22px; margin: 0 .375rem; background: linear-gradient(180deg, transparent, rgba(255,255,255,.2), transparent); }
        @media (max-width: 639px) { .sep-vertical { display: none; } }
        .seg-btn.is-active {
            color: #0a0a0a; font-weight: 500; text-shadow: none;
            background: linear-gradient(180deg, #f5f5f5, #bdbdbd); border-color: #e5e5e5;
            box-shadow: inset 0 1px 0 #fff, 0 0 12px rgba(255,255,255,.25);
        }

        /* Desplegables: mismo borde luminoso que la tarjeta, relieve, giro 3D hacia el cursor y luz al pasar y al pulsar */
        .bl-details, .bl-3d {
            /* relieve: luz en el filo de arriba, sombra en el de abajo */
            box-shadow: inset 0 1px 0 rgba(255,255,255,.07), inset 0 -1px 0 rgba(0,0,0,.6),
                        0 0 0 1px rgba(255,255,255,0.04), 0 0 28px rgba(255,255,255,0.05), 0 30px 60px -20px rgba(0,0,0,0.8);
            transform: perspective(1000px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
            transition: transform .3s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .bl-details:hover, .bl-3d:hover {
            transform: perspective(1000px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg)) translateY(-2px);
            border-color: #3a3a3a;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.12), inset 0 -1px 0 rgba(0,0,0,.6),
                        0 0 0 1px rgba(255,255,255,0.07), 0 0 36px rgba(255,255,255,0.09), 0 30px 60px -20px rgba(0,0,0,0.8);
        }
        /* Globo de alambre en 3D girando en la cabecera, como los del fondo del login */
        .globo-mini {
            position: absolute; right: 70px; top: 50%; width: 120px; aspect-ratio: 1; margin-top: -60px;
            transform-style: preserve-3d; pointer-events: none; opacity: .55;
            animation: globo-giro 40s linear infinite; transition: opacity .3s ease;
        }
        .globo-mini span { position: absolute; inset: 0; border-radius: 50%; border: 1px solid rgba(255,255,255,.08); transform: rotateY(calc(var(--i) * 45deg)); }
        .globo-mini span.lat { border-color: rgba(255,255,255,.06); transform: rotateX(90deg); }
        .bl-details:hover .globo-mini, .bl-details[open] .globo-mini, .bl-3d:hover .globo-mini { opacity: 1; }
        /* En la tarjeta de ingresos el globo es más grande y va en el centro del gráfico, detrás de las barras */
        .bl-3d .globo-clip { position: absolute; inset: 0; z-index: -1; overflow: hidden; perspective: 700px; pointer-events: none; }
        .bl-3d .globo-mini { left: 50%; top: 50%; right: auto; width: 240px; margin: -120px 0 0 -120px; opacity: .5; }
        .bl-3d::after { /* destello que cruza la tarjeta al pasar el cursor */
            content: ""; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
            background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.05) 50%, transparent 60%);
            background-size: 250% 100%; background-position: 150% 0; background-repeat: no-repeat;
        }
        .bl-3d:hover::after { background-position: -50% 0; transition: background-position 1.2s ease; }
        @keyframes globo-giro {
            from { transform: rotateX(-22deg) rotateZ(8deg) rotateY(0deg); }
            to   { transform: rotateX(-22deg) rotateZ(8deg) rotateY(360deg); }
        }
        .bl-details > summary {
            position: relative; overflow: hidden; perspective: 600px;
            display: flex; align-items: center; gap: .75rem; padding: .8rem 1.25rem;
            cursor: pointer; list-style: none; border-radius: .75rem;
            transition: transform .12s ease;
        }
        .bl-details > summary::-webkit-details-marker { display: none; }
        .bl-details > summary:focus-visible { outline: 2px solid #737373; outline-offset: 2px; }
        .bl-details > summary:active { transform: scale(.985); }
        .bl-details > summary::after { /* destello que cruza la cabecera al pasar el cursor */
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(105deg, transparent 35%, rgba(255,255,255,.09) 50%, transparent 65%);
            transform: translateX(-100%);
        }
        .bl-details > summary:hover::after { transform: translateX(100%); transition: transform .9s ease; }
        /* Icono fino de cada sección: apagado en reposo, se ilumina al pasar y al abrir */
        .bl-details .icono { width: 16px; height: 16px; flex-shrink: 0; color: #5a5a5a; transition: color .25s, filter .25s; }
        .bl-details:hover .icono { color: #b5b5b5; filter: drop-shadow(0 0 4px rgba(255,255,255,.35)); }
        .bl-details[open] .icono { color: #e5e5e5; filter: drop-shadow(0 0 4px rgba(255,255,255,.55)) drop-shadow(0 0 10px rgba(255,255,255,.2)); }
        /* letras con luz */
        .bl-details .titulo {
            font-size: .875rem; color: #e5e5e5;
            text-shadow: 0 0 1px rgba(255,255,255,.5), 0 0 10px rgba(255,255,255,.25), 0 0 22px rgba(255,255,255,.1);
            transition: text-shadow .25s, color .25s;
        }
        .bl-details:hover .titulo, .bl-details[open] .titulo {
            color: #fff;
            text-shadow: 0 0 1px rgba(255,255,255,.7), 0 0 14px rgba(255,255,255,.4), 0 0 30px rgba(255,255,255,.18);
        }
        .bl-details .resumen { margin-left: auto; font-size: .75rem; color: #8a8a8a; text-align: right; text-shadow: 0 0 6px rgba(255,255,255,.15); transition: color .25s; }
        .bl-details:hover .resumen { color: #b5b5b5; }
        /* flecha dentro de una pastilla metálica */
        .bl-details .chev-pill {
            display: grid; place-items: center; width: 26px; height: 26px; flex-shrink: 0; border-radius: 9999px;
            background: linear-gradient(180deg, #2e2e2e, #161616); border: 1px solid #3a3a3a;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.12);
            transition: border-color .25s, box-shadow .25s;
        }
        .bl-details:hover .chev-pill { border-color: #737373; box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 0 10px rgba(255,255,255,.25); }
        .bl-details .chev { width: .875rem; height: .875rem; color: #b5b5b5; transition: transform .3s ease, color .25s; }
        .bl-details:hover .chev { color: #fff; }
        .bl-details[open] .chev { transform: rotate(180deg); }
        /* al abrir, el contenido baja y aparece */
        .bl-details[open] > summary + div { animation: abrir .35s cubic-bezier(.2,.7,.2,1); }
        /* Formulario fijo (no desplegable): misma cabecera, siempre encendida */
        .form-cabecera { position: relative; overflow: hidden; perspective: 600px; display: flex; align-items: center; gap: .75rem; padding: .8rem 1.25rem; border-radius: .75rem; }
        .form-fijo .icono { color: #e5e5e5; filter: drop-shadow(0 0 4px rgba(255,255,255,.55)) drop-shadow(0 0 10px rgba(255,255,255,.2)); }
        .form-fijo .titulo { color: #fff; text-shadow: 0 0 1px rgba(255,255,255,.7), 0 0 14px rgba(255,255,255,.4), 0 0 30px rgba(255,255,255,.18); }
        .form-fijo .globo-mini { opacity: 1; }
        @keyframes abrir { from { opacity: 0; transform: translateY(-6px); } }
        /* onda de luz al pulsar */
        .onda {
            position: absolute; width: 16px; height: 16px; margin: -8px 0 0 -8px; border-radius: 9999px; pointer-events: none;
            background: radial-gradient(circle, rgba(255,255,255,.35), rgba(255,255,255,0) 70%);
            animation: onda .6s ease-out forwards;
        }
        @keyframes onda { to { transform: scale(40); opacity: 0; } }

        /* Gráficos: efectivo y tarjeta con su color, el resto en gris claro */
        .dot { width: 8px; height: 8px; border-radius: 9999px; flex-shrink: 0; display: inline-block; }
        .dot-efectivo, .seg-efectivo { background: #059669; }
        .dot-tarjeta,  .seg-tarjeta  { background: #2563eb; }
        .dot-efectivo { box-shadow: 0 0 6px #34d399; }
        .dot-tarjeta  { box-shadow: 0 0 6px #60a5fa; }
        .seg-neutro { background: #d4d4d4; }
        /* Gráfico de ingresos: barras de metal pulido con luz LED de color (efectivo en plata, tarjeta en grafito) */
        .bar-col { position: relative; flex: 1 1 0; max-width: 22px; min-height: 2px; display: flex; flex-direction: column; gap: 3px; outline: none; transition: transform .25s; }
        .bar-col.bar-vacia { background: #333; border-radius: 2px 2px 0 0; }
        .bar-col:hover, .bar-col:focus-visible { transform: translateY(-4px); }
        .bar-col > div { position: relative; }
        .bar-col > div:first-child { border-radius: 3px 3px 0 0; }
        .bar-col > .seg-efectivo { background: linear-gradient(90deg, #5a5a5a 0%, #f5f5f5 32%, #a3a3a3 55%, #e5e5e5 78%, #4a4a4a 100%); box-shadow: inset 0 -1px 0 rgba(0,0,0,.4); }
        .bar-col > .seg-tarjeta  { background: linear-gradient(90deg, #1a1a1a 0%, #8a8a8a 32%, #3a3a3a 55%, #6b6b6b 78%, #141414 100%); box-shadow: inset 0 1px 0 rgba(255,255,255,.25); }
        .bar-col > div::before { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 2px; border-radius: 2px; }
        .bar-col > .seg-efectivo::before { background: #34d399; box-shadow: 0 0 6px #34d399, 0 0 14px #059669; }
        .bar-col > .seg-tarjeta::before  { background: #60a5fa; box-shadow: 0 0 6px #60a5fa, 0 0 14px #2563eb; }
        /* destello que recorre las barras una a una, como el borde de la tarjeta */
        .bar-col:not(.bar-vacia)::after {
            content: ""; position: absolute; inset: 0; pointer-events: none; border-radius: 3px 3px 0 0;
            background: linear-gradient(115deg, transparent 30%, rgba(255,255,255,.85) 50%, transparent 70%);
            background-size: 100% 300%; background-repeat: no-repeat; mix-blend-mode: soft-light;
            animation: barrido 6s ease-in-out infinite; animation-delay: calc(var(--i, 0) * 0.08s);
        }
        @keyframes barrido { 0% { background-position: 0 -150%; } 40%, 100% { background-position: 0 250%; } }
        .bar-cima { position: absolute; left: 50%; bottom: calc(100% + 10px); transform: translateX(-50%); font-size: 11px; font-weight: 500; white-space: nowrap; pointer-events: none; }
        .eje-base { height: 2px; background: linear-gradient(90deg, #3a3a3a, #d4d4d4 50%, #3a3a3a); box-shadow: 0 1px 6px rgba(255,255,255,.25); }
        /* números y fechas en plata con brillo */
        .plata {
            background: linear-gradient(180deg, #ffffff 0%, #d4d4d4 45%, #8a8a8a 100%);
            -webkit-background-clip: text; background-clip: text; color: transparent;
            filter: drop-shadow(0 0 5px rgba(255,255,255,.35));
        }
        /* Barras horizontales de metal en una ranura hundida, con luz LED en la punta */
        .hbar-track {
            display: flex; gap: 3px; height: 10px; padding: 2px; border-radius: 9999px;
            background: #0d0d0d; box-shadow: inset 0 1px 2px rgba(0,0,0,.8), inset 0 -1px 0 rgba(255,255,255,.05);
        }
        .hbar { position: relative; height: 6px; outline: none; overflow: hidden; transition: filter .2s; }
        .hbar:first-child { border-radius: 9999px 0 0 9999px; }
        .hbar:last-child  { border-radius: 0 9999px 9999px 0; }
        .hbar:only-child  { border-radius: 9999px; }
        .hbar.seg-efectivo, .hbar.seg-neutro { background: linear-gradient(180deg, #ffffff 0%, #c4c4c4 40%, #8a8a8a 60%, #e5e5e5 100%); }
        .hbar.seg-tarjeta { background: linear-gradient(180deg, #9a9a9a 0%, #4a4a4a 40%, #1f1f1f 60%, #6b6b6b 100%); }
        .hbar::after { content: ""; position: absolute; top: 0; bottom: 0; right: 0; width: 2px; }
        .hbar.seg-efectivo::after { background: #34d399; box-shadow: 0 0 6px #34d399, 0 0 12px #059669; }
        .hbar.seg-tarjeta::after  { background: #60a5fa; box-shadow: 0 0 6px #60a5fa, 0 0 12px #2563eb; }
        .hbar.seg-neutro::after   { background: #fff;    box-shadow: 0 0 6px #fff, 0 0 12px rgba(255,255,255,.6); }
        .hbar::before { /* destello que recorre el metal */
            content: ""; position: absolute; inset: 0; pointer-events: none; mix-blend-mode: soft-light;
            background: linear-gradient(90deg, transparent 30%, rgba(255,255,255,.9) 50%, transparent 70%);
            background-size: 300% 100%; background-repeat: no-repeat;
            animation: barrido-h 6s ease-in-out infinite; animation-delay: calc(var(--n, 0) * .15s);
        }
        @keyframes barrido-h { 0% { background-position: 150% 0; } 40%, 100% { background-position: -150% 0; } }
        .hbar:hover, .hbar:focus-visible { filter: brightness(1.25); }

        /* Filas dentro de los desplegables: aparecen escalonadas, se iluminan al pasar */
        .fila {
            position: relative; overflow: hidden; padding: .5rem .625rem; margin: 0 -.625rem; border-radius: .5rem;
            transition: background-color .25s, transform .25s;
        }
        .fila::after { /* destello al pasar */
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(105deg, transparent 35%, rgba(255,255,255,.07) 50%, transparent 65%);
            transform: translateX(-100%);
        }
        .fila:hover { background: rgba(255,255,255,.03); transform: translateX(2px); }
        .fila:hover::after { transform: translateX(100%); transition: transform .9s ease; }
        .fila-nombre { color: #e5e5e5; transition: color .25s, text-shadow .25s; text-shadow: 0 0 8px rgba(255,255,255,.15); }
        .fila:hover .fila-nombre { color: #fff; text-shadow: 0 0 1px rgba(255,255,255,.6), 0 0 12px rgba(255,255,255,.35); }
        .fila-tabla { transition: background-color .25s; }
        .fila-tabla:hover { background: rgba(255,255,255,.03); }
        .fila-tabla:hover td { color: #f5f5f5; text-shadow: 0 0 8px rgba(255,255,255,.3); }
        .bl-details[open] .fila,
        .bl-details[open] .fila-tabla { animation: fila-entra .45s cubic-bezier(.2,.7,.2,1) backwards; animation-delay: calc(min(var(--n, 0), 8) * 60ms + 80ms); }
        @keyframes fila-entra { from { opacity: 0; transform: translateY(6px); } }
        /* Leyenda nítida: texto sin halo y puntos con borde definido */
        .leyenda-bl { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; font-size: 12px; font-weight: 500; color: #d4d4d4; }
        .leyenda-bl .dot { box-shadow: 0 0 0 1px rgba(255,255,255,.18), 0 0 3px currentColor; }
        .leyenda-bl .dot-efectivo { color: #34d399; }
        .leyenda-bl .dot-tarjeta  { color: #60a5fa; }

        #chart-tip { position: fixed; z-index: 70; pointer-events: none; padding: .375rem .625rem; font-size: .75rem; color: #f5f5f5; background: #0a0a0a; border: 1px solid #333; border-radius: .5rem; box-shadow: 0 8px 20px rgba(0,0,0,.6); white-space: nowrap; }
        .th-bl { font-size: .75rem; font-weight: 400; color: #8a8a8a; padding-bottom: .5rem; border-bottom: 1px solid #262626; text-shadow: 0 0 6px rgba(255,255,255,.2); }
        .th-bl { padding-left: .5rem; padding-right: .5rem; }
        .th-bl:first-child { padding-left: .625rem; }
        .th-bl:last-child  { padding-right: .625rem; }
        .td-bl { padding: .55rem .5rem; font-size: .75rem; color: #b5b5b5; transition: color .25s, text-shadow .25s; }
        .td-bl:first-child { position: relative; overflow: hidden; padding-left: .625rem; border-radius: .5rem 0 0 .5rem; }
        .td-bl:last-child  { padding-right: .625rem; border-radius: 0 .5rem .5rem 0; }

        @media (prefers-reduced-motion: reduce) {
            .card-glow::before, .bar-col::after, .onda, .bl-details[open] > summary + div, .globo-mini,
            .hbar::before, .bl-details[open] .fila, .bl-details[open] .fila-tabla { animation: none; }
            .fila:hover { transform: none; }
            .fila::after { display: none; }
            .bar-col, .bl-details, .bl-3d, .bl-details > summary, .bl-details .chev { transition: none; }
            .bl-details:hover, .bl-3d:hover { transform: none; }
            .bl-3d::after, .nav-item::after { display: none; }
            .aviso-icono { animation: none; }
            .logo-3d, .logo-stage::before, .logo-mueve, .logo-brillo, .pedestal-tapa::before { animation: none; }
            .nav-item:hover, .nav-item:active { transform: none; }
            .bl-details > summary::after { display: none; }
            .globo-mini { animation: none; }
        }

        /* ---------- Menú lateral: panel flotante con borde luminoso, relieve y giro 3D ---------- */
        .bl-side { border-radius: 1rem; }
        .bl-side.bl-3d:hover { transform: perspective(1000px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg)); } /* sin elevarse */
        .side-sep { height: 1px; margin: 0 1rem; background: linear-gradient(90deg, transparent, rgba(255,255,255,.16), transparent); }
        /* logo retroiluminado con un globo de alambre detrás, como en el login */
        .side-logo { position: relative; display: flex; flex-direction: column; align-items: center; gap: .75rem; padding: 1.5rem 1rem 1.25rem; }
        .side-logo .globo-clip { position: absolute; inset: 0; z-index: -1; overflow: hidden; perspective: 600px; pointer-events: none; border-radius: 1rem 1rem 0 0; }
        .bl-side .side-logo .globo-mini { left: 50%; top: 42%; right: auto; width: 150px; margin: -75px 0 0 -75px; opacity: .5; }
        .logo-stage { position: relative; perspective: 700px; }
        .logo-stage::before {
            content: ""; position: absolute; inset: -28% -38%; border-radius: 50%; pointer-events: none;
            background: radial-gradient(closest-side, rgba(255,255,255,0.11), rgba(255,255,255,0.04) 55%, transparent 100%);
            filter: blur(14px); animation: retroluz 6s ease-in-out infinite;
        }
        /* Logo: la misma animación que en el login (balanceo, neón que parpadea, destello y pedestal de plata) */
        .logo-mueve { position: relative; transform-origin: 50% 50%; animation: logo-giro 12s ease-in-out infinite; }
        .logo-3d { position: relative; display: block; animation: neon 8s ease-in-out infinite;
            filter: drop-shadow(0 0 1px rgba(255,255,255,0.6)) drop-shadow(0 0 7px rgba(255,255,255,0.28)) drop-shadow(0 0 18px rgba(255,255,255,0.12)) drop-shadow(0 6px 10px rgba(0,0,0,0.55)); }
        @keyframes neon {
            0%, 100%, 60%, 63.5%, 66% {
                filter: drop-shadow(0 0 1px rgba(255,255,255,0.6)) drop-shadow(0 0 7px rgba(255,255,255,0.28))
                        drop-shadow(0 0 18px rgba(255,255,255,0.12)) drop-shadow(0 6px 10px rgba(0,0,0,0.55));
            }
            30%, 45% {
                filter: drop-shadow(0 0 2px rgba(255,255,255,0.9)) drop-shadow(0 0 12px rgba(255,255,255,0.5))
                        drop-shadow(0 0 30px rgba(255,255,255,0.25)) drop-shadow(0 6px 10px rgba(0,0,0,0.55));
            }
            62%, 65% { filter: brightness(.55) drop-shadow(0 0 1px rgba(255,255,255,0.3)) drop-shadow(0 6px 10px rgba(0,0,0,0.55)); }
        }
        .logo-brillo {
            position: absolute; inset: 0; pointer-events: none;
            -webkit-mask: var(--logo) center / contain no-repeat; mask: var(--logo) center / contain no-repeat;
            background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.95) 50%, transparent 60%);
            background-size: 300% 100%; background-repeat: no-repeat;
            animation: logo-destello 6s ease-in-out infinite;
        }
        @keyframes logo-destello { 0%, 55% { background-position: 150% 0; } 85%, 100% { background-position: -50% 0; } }
        .pedestal { position: absolute; left: 50%; top: 100%; width: 120%; height: 18px; margin: 3px 0 0 -60%; pointer-events: none; }
        .pedestal-luz { position: absolute; left: 10%; right: 10%; top: 16px; height: 12px; border-radius: 50%;
            background: radial-gradient(closest-side, rgba(255,255,255,.25), transparent); filter: blur(3px); }
        .pedestal-lado { position: absolute; left: 0; right: 0; top: 6px; height: 9px; border-radius: 0 0 50% 50% / 0 0 6px 6px;
            background: linear-gradient(90deg, #1a1a1a, #9a9a9a 30%, #e5e5e5 45%, #6b6b6b 70%, #141414); }
        .pedestal-tapa { position: absolute; left: 0; right: 0; top: 0; height: 12px; border-radius: 50%;
            background: radial-gradient(ellipse at 40% 35%, #4a4a4a, #222 60%, #161616);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 0 0 1px rgba(255,255,255,.18); }
        .pedestal-tapa::after { content: ""; position: absolute; inset: 2px 10%; border-radius: 50%; border: 1px solid rgba(255,255,255,.12); }
        @property --filo { syntax: "<angle>"; initial-value: 0deg; inherits: false; }
        .pedestal-tapa::before {
            content: ""; position: absolute; inset: 0; border-radius: 50%; pointer-events: none;
            background: conic-gradient(from var(--filo), transparent 0deg, rgba(255,255,255,.55) 30deg, transparent 70deg);
            -webkit-mask: radial-gradient(closest-side, transparent 85%, #000 88%); mask: radial-gradient(closest-side, transparent 85%, #000 88%);
            animation: filo-luz 5s linear infinite;
        }
        @keyframes filo-luz { to { --filo: 360deg; } }
        @keyframes logo-giro { 0%, 100% { transform: rotateY(-6deg) rotateX(2deg); } 50% { transform: rotateY(6deg) rotateX(-1deg); } }
        @keyframes retroluz { 0%, 100% { opacity: .75; transform: scale(1); } 50% { opacity: 1; transform: scale(1.06); } }
        .side-etiqueta { font-size: 10px; letter-spacing: .18em; text-transform: uppercase; }
        /* usuario */
        .side-usuario { display: flex; align-items: center; gap: .75rem; padding: 1rem 1.25rem; }
        .avatar-metal {
            display: grid; place-items: center; width: 36px; height: 36px; flex-shrink: 0; border-radius: 9999px;
            font-size: .875rem; font-weight: 600; color: #0a0a0a;
            background: linear-gradient(135deg, #ffffff 0%, #c4c4c4 40%, #7a7a7a 60%, #e5e5e5 100%);
            box-shadow: 0 0 0 2px #171717, 0 0 0 3px rgba(255,255,255,.25), 0 0 14px rgba(255,255,255,.25);
        }
        .side-nombre { font-size: .8125rem; font-weight: 500; color: #f5f5f5; text-shadow: 0 0 1px rgba(255,255,255,.5), 0 0 10px rgba(255,255,255,.3); }
        /* opciones del menú */
        .nav-item {
            position: relative; overflow: hidden; display: flex; align-items: center; gap: .75rem;
            margin: 2px .75rem; padding: .625rem .75rem; border-radius: .625rem; border: 1px solid transparent;
            font-size: .8125rem; color: #8a8a8a;
            transition: color .25s, background-color .25s, border-color .25s, box-shadow .25s, transform .25s, text-shadow .25s;
        }
        .nav-item::after { /* destello al pasar */
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(105deg, transparent 35%, rgba(255,255,255,.08) 50%, transparent 65%);
            transform: translateX(-100%);
        }
        .nav-item:hover { color: #f5f5f5; background: rgba(255,255,255,.03); transform: translateX(3px); text-shadow: 0 0 10px rgba(255,255,255,.35); }
        .nav-item:hover::after { transform: translateX(100%); transition: transform .9s ease; }
        .nav-item:active { transform: translateX(3px) scale(.97); }
        .nav-item:focus-visible { outline: 2px solid #737373; outline-offset: 2px; }
        .nav-item .icono { width: 17px; height: 17px; flex-shrink: 0; color: #5a5a5a; transition: color .25s, filter .25s; }
        .nav-item:hover .icono { color: #b5b5b5; filter: drop-shadow(0 0 4px rgba(255,255,255,.35)); }
        .nav-item.is-active {
            color: #fff; font-weight: 500;
            background: linear-gradient(180deg, #262626, #1a1a1a); border-color: #3a3a3a;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.1), 0 0 14px rgba(255,255,255,.06);
            text-shadow: 0 0 1px rgba(255,255,255,.6), 0 0 12px rgba(255,255,255,.35);
        }
        .nav-item.is-active .icono { color: #e5e5e5; filter: drop-shadow(0 0 4px rgba(255,255,255,.55)) drop-shadow(0 0 10px rgba(255,255,255,.2)); }
        .nav-item.is-active::before { /* LED de la opción activa */
            content: ""; position: absolute; left: 0; top: 50%; width: 3px; height: 18px; margin-top: -9px; border-radius: 0 3px 3px 0;
            background: #fff; box-shadow: 0 0 6px #fff, 0 0 14px rgba(255,255,255,.6);
        }
        .nav-salir:hover { color: #fca5a5; text-shadow: 0 0 10px rgba(248,113,113,.45); background: rgba(248,113,113,.05); }
        .nav-salir:hover .icono { color: #f87171; filter: drop-shadow(0 0 4px rgba(248,113,113,.6)); }
        /* botón de menú en el móvil */
        #sidebar-toggle { background: linear-gradient(180deg, #232323, #161616); border: 1px solid #3a3a3a; box-shadow: inset 0 1px 0 rgba(255,255,255,.1), 0 0 12px rgba(255,255,255,.08); }

        /* Fondo: sala de columnas en 3D, fija detrás del menú y del contenido */
        #fondo-sala { position: fixed; inset: 0; width: 100%; height: 100%; z-index: 0; pointer-events: none; }
        #sidebar, main { position: relative; z-index: 1; }

        /* Sidebar móvil */
        @media (max-width: 767px) {
            #sidebar { position: fixed; top: 0; left: 0; bottom: 0; transform: translateX(calc(-100% - 1.5rem)); transition: transform .25s ease; z-index: 60; }
            #sidebar.open { transform: translateX(0); }
            #sidebar-backdrop { display: none; }
            #sidebar-backdrop.open { display: block; }
        }
    </style>
</head>
<body class="bl-bg font-inter flex h-screen overflow-hidden text-white">

<?php include __DIR__ . '/TransicionSesion.php'; ?>

<!-- FONDO: sala de columnas (se dibuja una vez y al cambiar el tamaño de la ventana) -->
<canvas id="fondo-sala" aria-hidden="true"></canvas>

<!-- MOBILE HAMBURGER -->
<button id="sidebar-toggle" aria-label="Menú"
        class="md:hidden fixed top-3 left-3 z-50 w-10 h-10 rounded-xl bg-black/70 border border-white/10 flex items-center justify-center text-white">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
</button>

<!-- BACKDROP MÓVIL -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-black/60 z-50 md:hidden"></div>

<!-- SIDEBAR: panel flotante con borde luminoso, relieve y giro 3D -->
<aside id="sidebar" class="bl-panel card-glow bl-3d bl-side w-56 m-3 flex flex-col flex-shrink-0 h-[calc(100%-1.5rem)]" style="--delay:-4s">
    <div class="flex flex-col h-full overflow-y-auto">

    <div class="side-logo">
        <div class="globo-clip" aria-hidden="true">
            <span class="globo-mini"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
        </div>
        <div class="logo-stage mb-4" style="--logo: url('public/img/logos/beloyal-logo.svg')">
            <div class="logo-mueve">
                <img src="public/img/logos/beloyal-logo.svg" alt="Be Loyal" class="logo-3d w-20 h-auto select-none" draggable="false">
                <span class="logo-brillo" aria-hidden="true"></span>
            </div>
            <div class="pedestal" aria-hidden="true"><span class="pedestal-luz"></span><span class="pedestal-lado"></span><span class="pedestal-tapa"></span></div>
        </div>
        <p class="side-etiqueta plata">Panel de administración</p>
    </div>

    <div class="side-sep"></div>

    <div class="side-usuario">
        <span class="avatar-metal" aria-hidden="true"><?= htmlspecialchars(mb_strtoupper(mb_substr($usuario['nombre'], 0, 1))) ?></span>
        <div class="min-w-0">
            <p class="side-nombre truncate"><?= htmlspecialchars($usuario['nombre']) ?></p>
            <p class="plata text-[11px] mt-0.5">Administrador</p>
        </div>
    </div>

    <div class="side-sep"></div>

    <nav class="flex-1 py-3" aria-label="Secciones">
        <a href="index.php?page=admin&seccion=dashboard" class="nav-item <?= $seccion === 'dashboard' ? 'is-active' : '' ?>"<?= $seccion === 'dashboard' ? ' aria-current="page"' : '' ?>>
            <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
            <span>Dashboard</span>
        </a>
        <a href="index.php?page=admin&seccion=servicios" class="nav-item <?= $seccion === 'servicios' ? 'is-active' : '' ?>"<?= $seccion === 'servicios' ? ' aria-current="page"' : '' ?>>
            <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4L8.12 15.88"/><path d="M14.47 14.48L20 20"/><path d="M8.12 8.12L12 12"/></svg>
            <span>Servicios</span>
        </a>
        <a href="index.php?page=admin&seccion=productos" class="nav-item <?= $seccion === 'productos' ? 'is-active' : '' ?>"<?= $seccion === 'productos' ? ' aria-current="page"' : '' ?>>
            <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>
            <span>Productos</span>
        </a>
        <a href="index.php?page=admin&seccion=empleados" class="nav-item <?= $seccion === 'empleados' ? 'is-active' : '' ?>"<?= $seccion === 'empleados' ? ' aria-current="page"' : '' ?>>
            <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Empleados</span>
        </a>
    </nav>

    <div class="side-sep"></div>

    <div class="py-3">
        <a href="index.php?page=logout" class="nav-item nav-salir">
            <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            <span>Cerrar sesión</span>
        </a>
    </div>

    </div>
</aside>

<!-- CONTENT -->
<main class="flex-1 p-4 sm:p-7 overflow-y-auto pt-16 md:pt-7">

    <?php if ($mensaje): ?>
    <div class="text-xs font-light text-emerald-400 bg-emerald-900/30 border border-emerald-800/50 rounded-xl px-4 py-2.5 mb-5">
        <?= htmlspecialchars($mensaje) ?>
    </div>
    <?php endif; ?>

    <?php
    // Aviso de reposición (solo en Productos): bebidas a la venta con 10 o menos unidades, y las agotadas
    $REPONER = 10;
    $porReponer = $seccion !== 'productos' ? [] : array_filter($productos, fn ($p) => (int) $p['stock'] === 0 || ($p['activo'] && (int) $p['stock'] <= $REPONER));
    usort($porReponer, fn ($a, $b) => (int) $a['stock'] <=> (int) $b['stock']);
    ?>
    <?php if ($porReponer): ?>
    <div class="aviso-reponer mb-4" role="status">
        <svg class="aviso-icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 3.9L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        <span class="aviso-titulo">Hay que comprar más bebidas</span>
        <span class="flex flex-wrap gap-1.5 basis-full sm:basis-0 sm:flex-1 min-w-0 order-last sm:order-none">
            <?php foreach ($porReponer as $p): $st = (int) $p['stock']; ?>
            <span class="aviso-chip <?= $st === 0 ? 'is-agotado' : '' ?>" title="<?= $st === 0 ? 'Agotada: está desactivada y no sale en el punto de venta' : 'Quedan ' . $st . ' unidades' ?>">
                <?= htmlspecialchars($p['nombre']) ?> · <?= $st === 0 ? 'agotada' : 'quedan ' . $st ?>
            </span>
            <?php endforeach; ?>
        </span>
    </div>
    <?php endif; ?>

    <?php if ($seccion === 'dashboard'):
        // "hoy 14:32", "ayer 18:05" o "12/10 09:15"
        $fechaCorta = function (string $fecha): string {
            $dt  = new DateTime($fecha);
            $dia = $dt->format('Y-m-d');
            if ($dia === date('Y-m-d'))                      return 'hoy ' . $dt->format('H:i');
            if ($dia === date('Y-m-d', strtotime('-1 day'))) return 'ayer ' . $dt->format('H:i');
            return $dt->format('d/m H:i');
        };
        $euros = fn ($n) => '€' . number_format((float) $n, 2);
        $VISIBLES = 8;

        // Serie del gráfico: un punto por día (por mes en "Año"), con los días sin ventas a 0
        $porFecha = [];
        foreach ($porDia as $d) $porFecha[$d['dia']] = $d;
        $serie = [];
        $hoy   = new DateTime('today');
        if ($periodo === 'ano') {
            $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
            for ($m = 1; $m <= (int) $hoy->format('n'); $m++) {
                $clave = $hoy->format('Y') . sprintf('-%02d-', $m);
                $p = ['etiqueta' => $meses[$m - 1], 'ingresos' => 0, 'efectivo' => 0, 'tarjeta' => 0, 'ventas' => 0];
                foreach ($porFecha as $dia => $d) {
                    if (!str_starts_with($dia, $clave)) continue;
                    $p['ingresos'] += (float) $d['ingresos'];
                    $p['efectivo'] += (float) $d['ingresos_efectivo'];
                    $p['tarjeta']  += (float) $d['ingresos_tarjeta'];
                    $p['ventas']   += (int) $d['ventas'];
                }
                $serie[] = $p;
            }
        } elseif ($periodo !== 'dia') {
            $desde = match ($periodo) {
                'semana' => new DateTime('monday this week'),
                'mes'    => new DateTime('first day of this month'),
                default  => new DateTime('-30 days'),
            };
            for ($d = $desde->setTime(0, 0); $d <= $hoy; $d->modify('+1 day')) {
                $k = $d->format('Y-m-d');
                $serie[] = [
                    'etiqueta' => $d->format('d/m'),
                    'ingresos' => (float) ($porFecha[$k]['ingresos'] ?? 0),
                    'efectivo' => (float) ($porFecha[$k]['ingresos_efectivo'] ?? 0),
                    'tarjeta'  => (float) ($porFecha[$k]['ingresos_tarjeta'] ?? 0),
                    'ventas'   => (int) ($porFecha[$k]['ventas'] ?? 0),
                ];
            }
        }
        // Tope del eje redondeado (1, 2, 2.5, 5 × 10^n)
        $maxIng = $serie ? max(array_column($serie, 'ingresos')) : 0;
        $tope   = 1;
        if ($maxIng > 0) {
            $paso = 10 ** floor(log10($maxIng));
            foreach ([1, 2, 2.5, 5, 10] as $f) { if ($f * $paso >= $maxIng) { $tope = $f * $paso; break; } }
        }
        $mejor = $serie && $maxIng > 0 ? $serie[array_search($maxIng, array_column($serie, 'ingresos'))] : null;
        $maxEmp = $porEmpleado ? max(array_map(fn ($e) => (float) $e['ingresos'], $porEmpleado)) : 0;
    ?>
    <!-- DASHBOARD -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <h1 class="glow-text text-base font-medium text-[#f5f5f5]">Dashboard</h1>
        <div class="flex flex-wrap items-center gap-1.5">
            <?php foreach (['todo' => 'Todo', 'dia' => 'Hoy', 'semana' => 'Semana', 'mes' => 'Mes', 'ano' => 'Año'] as $val => $label): ?>
            <a href="index.php?page=admin&seccion=dashboard&periodo=<?= $val ?>"
               class="seg-btn <?= $periodo === $val ? 'is-active' : '' ?>"><?= $label ?></a>
            <?php endforeach; ?>
            <span class="sep-vertical" aria-hidden="true"></span>
            <button type="button" onclick="abrirResetModal()" class="seg-btn btn-peligro">Resetear ventas…</button>
        </div>
    </div>

    <!-- Ingresos: total, efectivo, tarjeta y su evolución en una sola tarjeta -->
    <section class="bl-panel card-glow bl-3d p-4 sm:p-5 mb-3" aria-label="Ingresos">
        <div class="relative grid gap-5 lg:grid-cols-[250px_minmax(0,1fr)]">

            <div class="flex flex-col gap-4">
                <div>
                    <p class="glow-text text-xs text-[#b5b5b5] mb-1">Ingresos</p>
                    <p class="text-4xl font-light text-[#f5f5f5]"><?= $euros($resumen['ingresos_totales']) ?></p>
                    <p class="text-xs text-[#8a8a8a] mt-1.5"><?= $resumen['total_ventas'] ?> ventas</p>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#262626]">
                    <?php foreach (['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta'] as $metodo => $nombre):
                        $pct = $resumen['ingresos_totales'] > 0 ? round($porMetodo[$metodo]['ingresos'] / $resumen['ingresos_totales'] * 100) : 0;
                    ?>
                    <div>
                        <p class="glow-text text-xs text-[#b5b5b5] mb-1 flex items-center gap-1.5"><span class="dot dot-<?= $metodo ?>"></span><?= $nombre ?></p>
                        <p class="text-xl font-light text-[#f5f5f5]"><?= $euros($porMetodo[$metodo]['ingresos']) ?></p>
                        <p class="text-xs text-[#8a8a8a] mt-1"><?= $porMetodo[$metodo]['ventas'] ?> ventas · <?= $pct ?>%</p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="relative min-w-0 flex flex-col">
                <div class="globo-clip" aria-hidden="true">
                    <span class="globo-mini"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                </div>
                <div class="flex flex-wrap items-baseline justify-between gap-2 mb-3 text-xs">
                    <span class="text-[#b5b5b5]"><?= match ($periodo) { 'ano' => 'Por mes', 'todo' => 'Por día · últimos 30 días', default => 'Por día' } ?></span>
                    <span class="text-[#8a8a8a]"><?= $mejor ? ($periodo === 'ano' ? 'Mejor mes: ' : 'Mejor día: ') . $mejor['etiqueta'] . ' · ' . $euros($mejor['ingresos']) : '' ?></span>
                </div>
                <?php if ($periodo === 'dia'): ?>
                <p class="flex-1 grid place-items-center text-xs text-[#8a8a8a] py-6 text-center">Elige Semana, Mes o Año para ver la evolución de los ingresos.</p>
                <?php else: ?>
                <div class="relative h-32 pl-12" role="img" aria-label="Gráfico de ingresos por efectivo y tarjeta; los valores están en la tabla siguiente">
                    <?php foreach ([1, 0.5, 0] as $f): ?>
                    <div class="absolute left-12 right-0 <?= $f == 0 ? 'eje-base' : 'border-t border-[#262626]' ?>" style="bottom:<?= $f * 100 ?>%"></div>
                    <span class="plata absolute left-0 w-10 text-right text-[11px] tabular-nums -translate-y-1/2" style="top:<?= (1 - $f) * 100 ?>%">€<?= number_format($tope * $f, 0) ?></span>
                    <?php endforeach; ?>
                    <div class="absolute inset-0 left-12 flex items-end justify-around gap-1 sm:gap-2.5">
                        <?php foreach ($serie as $i => $p): ?>
                        <div class="bar-col <?= $p['ingresos'] > 0 ? '' : 'bar-vacia' ?>" tabindex="0" style="height:<?= round($p['ingresos'] / $tope * 100, 2) ?>%;--i:<?= $i ?>"
                             data-tip="<?= htmlspecialchars($p['etiqueta'] . ' · ' . $euros($p['ingresos']) . ' (efectivo ' . $euros($p['efectivo']) . ' · tarjeta ' . $euros($p['tarjeta']) . ') · ' . $p['ventas'] . ' ventas') ?>">
                            <?php if ($p['tarjeta'] > 0): ?><div class="seg-tarjeta" style="flex-grow:<?= $p['tarjeta'] ?>"></div><?php endif; ?>
                            <?php if ($p['efectivo'] > 0): ?><div class="seg-efectivo" style="flex-grow:<?= $p['efectivo'] ?>"></div><?php endif; ?>
                            <?php if ($mejor && $p === $mejor): ?><span class="bar-cima plata hidden sm:block"><?= $euros($p['ingresos']) ?></span><?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="plata flex justify-between pl-12 mt-2 text-[11px] tabular-nums">
                    <span><?= $serie[0]['etiqueta'] ?? '' ?></span>
                    <span><?= $serie[intdiv(count($serie), 2)]['etiqueta'] ?? '' ?></span>
                    <span><?= $serie ? end($serie)['etiqueta'] : '' ?></span>
                </div>
                <div class="leyenda-bl mt-2 pl-12">
                    <span class="flex items-center gap-1.5"><span class="dot dot-efectivo"></span>Efectivo · plata</span>
                    <span class="flex items-center gap-1.5"><span class="dot dot-tarjeta"></span>Tarjeta · grafito</span>
                </div>
                <div class="sr-only"><table>
                    <caption>Ingresos por fecha</caption>
                    <tr><th>Fecha</th><th>Ingresos</th><th>Efectivo</th><th>Tarjeta</th><th>Ventas</th></tr>
                    <?php foreach ($serie as $p): ?>
                    <tr><td><?= $p['etiqueta'] ?></td><td><?= $euros($p['ingresos']) ?></td><td><?= $euros($p['efectivo']) ?></td><td><?= $euros($p['tarjeta']) ?></td><td><?= $p['ventas'] ?></td></tr>
                    <?php endforeach; ?>
                </table></div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <div class="space-y-3">

        <!-- Ventas por empleado -->
        <details class="bl-panel card-glow bl-details" style="--delay:-2s">
            <summary>
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="titulo">Ventas por empleado</span>
                <span class="resumen"><?= $porEmpleado && $maxEmp > 0 ? 'Más ingresos: ' . htmlspecialchars($porEmpleado[0]['nombre']) : '' ?></span>
                <span class="chev-pill"><svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></span>
            </summary>
            <div class="px-5 pb-5">
                <div class="leyenda-bl mb-3">
                    <span class="flex items-center gap-1.5"><span class="dot dot-efectivo"></span>Efectivo · plata</span>
                    <span class="flex items-center gap-1.5"><span class="dot dot-tarjeta"></span>Tarjeta · grafito</span>
                </div>
                <div class="space-y-1">
                    <?php foreach ($porEmpleado as $n => $e): ?>
                    <div class="fila" style="--n:<?= $n ?>">
                        <div class="flex items-baseline justify-between gap-3 text-xs mb-2">
                            <span class="fila-nombre"><?= htmlspecialchars($e['nombre']) ?></span>
                            <span class="tabular-nums"><span class="plata"><?= $euros($e['ingresos']) ?></span> <span class="text-[#737373]">· <?= $e['ventas'] ?> ventas</span></span>
                        </div>
                        <div class="hbar-track">
                            <?php if ($maxEmp > 0): ?>
                            <div class="hbar seg-efectivo" style="width:<?= round($e['ingresos_efectivo'] / $maxEmp * 100, 2) ?>%" tabindex="0" data-tip="<?= htmlspecialchars($e['nombre'] . ' · efectivo ' . $euros($e['ingresos_efectivo'])) ?>"></div>
                            <div class="hbar seg-tarjeta" style="width:<?= round($e['ingresos_tarjeta'] / $maxEmp * 100, 2) ?>%" tabindex="0" data-tip="<?= htmlspecialchars($e['nombre'] . ' · tarjeta ' . $euros($e['ingresos_tarjeta'])) ?>"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($porEmpleado)): ?>
                    <p class="text-xs text-[#737373] text-center py-4">Sin datos</p>
                    <?php endif; ?>
                </div>
            </div>
        </details>

        <!-- Lo más vendido: top 5 con pestañas -->
        <details class="bl-panel card-glow bl-details" style="--delay:-5s">
            <summary>
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4L8.12 15.88"/><path d="M14.47 14.48L20 20"/><path d="M8.12 8.12L12 12"/></svg>
                <span class="titulo">Lo más vendido</span>
                <span class="resumen"><?= $svcVendidos ? 'Nº 1: ' . htmlspecialchars($svcVendidos[0]['nombre']) : '' ?></span>
                <span class="chev-pill"><svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></span>
            </summary>
            <div class="px-5 pb-5">
                <div class="flex gap-1.5 mb-3" role="tablist">
                    <button type="button" role="tab" aria-selected="true" data-tab="top-servicios" class="seg-btn top-tab is-active">Servicios</button>
                    <button type="button" role="tab" aria-selected="false" data-tab="top-productos" class="seg-btn top-tab">Productos</button>
                </div>
                <?php foreach (['top-servicios' => $svcVendidos, 'top-productos' => $prodVendidos] as $idTab => $lista):
                    $top    = array_slice($lista, 0, 5);
                    $maxUds = $top ? max(array_map(fn ($i) => (float) $i['unidades'], $top)) : 0;
                ?>
                <div id="<?= $idTab ?>" class="top-panel space-y-1" <?= $idTab === 'top-productos' ? 'hidden' : '' ?>>
                    <?php foreach ($top as $n => $item): ?>
                    <div class="fila" style="--n:<?= $n ?>">
                        <div class="flex items-baseline justify-between gap-3 text-xs mb-2">
                            <span class="fila-nombre"><?= htmlspecialchars($item['nombre']) ?></span>
                            <span class="tabular-nums"><span class="plata"><?= $item['unidades'] ?> uds</span> <span class="text-[#737373]">· <?= $euros($item['ingresos']) ?></span></span>
                        </div>
                        <div class="hbar-track">
                            <div class="hbar seg-neutro" style="--n:<?= $n ?>;width:<?= $maxUds > 0 ? round($item['unidades'] / $maxUds * 100, 2) : 0 ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($top)): ?>
                    <p class="text-xs text-[#737373] text-center py-4">Sin datos</p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </details>

        <!-- Ventas recientes -->
        <details class="bl-panel card-glow bl-details" style="--delay:-8s">
            <summary>
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                <span class="titulo">Ventas recientes</span>
                <span class="resumen"><?= $ventasRecientes ? 'Última: ' . $fechaCorta($ventasRecientes[0]['fecha']) : '' ?></span>
                <span class="chev-pill"><svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></span>
            </summary>
            <div class="px-5 pb-5">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left th-bl">Fecha</th>
                            <th class="text-left th-bl">Empleado</th>
                            <th class="text-left th-bl">Método</th>
                            <th class="text-right th-bl">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventasRecientes as $i => $v):
                            $esEfectivo = str_contains(strtolower($v['metodo_pago'] ?? ''), 'efectivo');
                        ?>
                        <tr class="fila-tabla border-b border-[#232323] last:border-0 <?= $i >= $VISIBLES ? 'venta-extra hidden' : '' ?>" style="--n:<?= $i ?>">
                            <td class="td-bl tabular-nums"><?= $fechaCorta($v['fecha']) ?></td>
                            <td class="td-bl"><?= htmlspecialchars($v['empleado'] ?? '—') ?></td>
                            <td class="td-bl"><span class="inline-flex items-center gap-1.5"><?php if (!empty($v['metodo_pago'])): ?><span class="dot <?= $esEfectivo ? 'dot-efectivo' : 'dot-tarjeta' ?>"></span><?php endif; ?><?= htmlspecialchars(ucfirst($v['metodo_pago'] ?? '—')) ?></span></td>
                            <td class="td-bl text-right tabular-nums"><span class="plata"><?= $euros($v['total']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($ventasRecientes)): ?>
                        <tr><td colspan="4" class="py-4 text-center text-xs text-[#737373]">Sin ventas registradas</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (count($ventasRecientes) > $VISIBLES): ?>
                <button type="button" id="ver-mas-ventas"
                        class="seg-btn mt-3"
                        data-mas="Ver más (<?= count($ventasRecientes) - $VISIBLES ?>)" data-menos="Ver menos">
                    Ver más (<?= count($ventasRecientes) - $VISIBLES ?>)
                </button>
                <?php endif; ?>
            </div>
        </details>

    </div>


    <!-- Tooltip de los gráficos -->
    <div id="chart-tip" role="status" hidden></div>

    <?php elseif ($seccion === 'servicios'):
        $optsS   = ['peluqueria' => 'Peluquería', 'trenzas' => 'Trenzas', 'uñas' => 'Uñas', 'bono' => 'Bono'];
        $selS    = $editServicio['especialidad'] ?? 'peluqueria';
        $euros   = fn ($n) => '€' . number_format((float) $n, 2);
        $grupos  = ['peluqueria' => 'Peluquería', 'trenzas' => 'Trenzas', 'uñas' => 'Uñas', 'bono' => 'Bonos'];
        $iconosGrupo = [
            'peluqueria' => '<circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4L8.12 15.88"/><path d="M14.47 14.48L20 20"/><path d="M8.12 8.12L12 12"/>',
            'trenzas'    => '<path d="M8 3c4 3-4 6 0 9s-4 6 0 9"/><path d="M16 3c-4 3 4 6 0 9s4 6 0 9"/>',
            'uñas'       => '<path d="M12 3c-2.5 0-4 2-4 5v9a4 4 0 0 0 8 0V8c0-3-1.5-5-4-5z"/><path d="M10 7c.5-1 1.2-1.5 2-1.5"/>',
            'bono'       => '<path d="M3 8a2 2 0 0 0 0 4v4h18v-4a2 2 0 0 0 0-4V4H3z" transform="translate(0 2)"/><path d="M13 6v2M13 11v2M13 16v2"/>',
        ];
        $serviciosPorGrupo = [];
        foreach ($servicios as $s) $serviciosPorGrupo[$s['especialidad'] ?? 'peluqueria'][] = $s;
        $activos = count(array_filter($servicios, fn ($s) => $s['activo']));
        $primerGrupo = array_key_first(array_intersect_key($grupos, $serviciosPorGrupo));
    ?>
    <!-- SERVICIOS -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h1 class="glow-text text-base font-medium text-[#f5f5f5]">Servicios</h1>
            <p class="text-xs text-[#8a8a8a] mt-0.5"><?= $activos ?> <?= $activos === 1 ? 'activo' : 'activos' ?> de <?= count($servicios) ?></p>
        </div>
    </div>

    <div class="space-y-3">

        <!-- Formulario: nuevo o editar -->
        <section id="form-servicio" class="bl-panel card-glow bl-details form-fijo" style="--delay:-3s">
            <div class="form-cabecera">
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $editServicio ? '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>' : '<path d="M12 5v14M5 12h14"/>' ?></svg>
                <span class="titulo"><?= $editServicio ? 'Editar servicio' : 'Nuevo servicio' ?></span>
                <span class="resumen"><?= $editServicio ? htmlspecialchars($editServicio['nombre']) : 'Nombre, precio, duración y tipo' ?></span>
            </div>
            <div class="px-5 pb-5">
                <form method="POST" action="index.php?page=<?= $editServicio ? 'admin_actualizar_servicio' : 'admin_crear_servicio' ?>">
                    <?= csrf_field() ?>
                    <?php if ($editServicio): ?>
                    <input type="hidden" name="id" value="<?= $editServicio['id_servicio'] ?>">
                    <?php endif; ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                        <div>
                            <label for="svc-nombre" class="etiqueta-bl">Nombre</label>
                            <input id="svc-nombre" type="text" name="nombre" required placeholder="Corte clásico" value="<?= htmlspecialchars($editServicio['nombre'] ?? '') ?>" class="campo-bl">
                        </div>
                        <div>
                            <label for="svc-precio" class="etiqueta-bl">Precio (€)</label>
                            <input id="svc-precio" type="number" name="precio" step="0.01" min="0" required placeholder="12.00" value="<?= $editServicio['precio'] ?? '' ?>" class="campo-bl">
                        </div>
                        <div>
                            <label for="svc-duracion" class="etiqueta-bl">Duración (min)</label>
                            <input id="svc-duracion" type="number" name="duracion" min="0" required placeholder="30" value="<?= $editServicio['duracion'] ?? '' ?>" class="campo-bl">
                        </div>
                        <div>
                            <span class="etiqueta-bl">Tipo</span>
                            <div class="custom-select relative">
                                <input type="hidden" name="especialidad" value="<?= $selS ?>">
                                <button type="button" class="custom-select-btn campo-bl flex items-center justify-between">
                                    <span class="custom-select-label"><?= $optsS[$selS] ?></span>
                                    <svg class="w-3.5 h-3.5 text-[#8a8a8a]" style="transition:transform .2s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="custom-select-dropdown hidden absolute top-full left-0 right-0 mt-1 bl-panel overflow-hidden z-50 py-1">
                                    <?php foreach ($optsS as $v => $l): ?>
                                    <div class="custom-select-option px-3 py-2 text-xs cursor-pointer transition <?= $v === $selS ? 'text-white bg-white/5' : 'text-zinc-400 hover:bg-zinc-700/50 hover:text-zinc-200' ?>" data-value="<?= $v ?>"><?= $l ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="seg-btn is-active"><?= $editServicio ? 'Guardar cambios' : 'Añadir servicio' ?></button>
                        <?php if ($editServicio): ?>
                        <a href="index.php?page=admin&seccion=servicios" class="seg-btn">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>

        <!-- Un desplegable por tipo de servicio -->
        <?php foreach ($grupos as $esp => $titulo):
            if (empty($serviciosPorGrupo[$esp])) continue;
            $lista  = $serviciosPorGrupo[$esp];
            $desde  = min(array_map(fn ($s) => (float) $s['precio'], $lista));
            $nAct   = count(array_filter($lista, fn ($s) => $s['activo']));
        ?>
        <details class="bl-panel card-glow bl-details" style="--delay:-<?= 2 + array_search($esp, array_keys($grupos)) * 3 ?>s" <?= !$editServicio && $esp === $primerGrupo ? 'open' : '' ?>>
            <summary>
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $iconosGrupo[$esp] ?></svg>
                <span class="titulo"><?= $titulo ?></span>
                <span class="resumen"><?= $nAct ?> <?= $nAct === 1 ? 'activo' : 'activos' ?> · desde <?= $euros($desde) ?></span>
                <span class="chev-pill"><svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></span>
            </summary>
            <div class="px-5 pb-4 space-y-1">
                <?php foreach ($lista as $n => $s): ?>
                <div class="fila flex flex-wrap items-center gap-x-4 gap-y-2 <?= $s['activo'] ? '' : 'opacity-60' ?>" style="--n:<?= $n ?>">
                    <!-- En el móvil, nombre y duración van juntos en la primera línea -->
                    <span class="flex items-center justify-between gap-3 basis-full sm:contents">
                        <span class="fila-nombre text-sm sm:flex-1 sm:min-w-[10rem]"><?= htmlspecialchars($s['nombre']) ?></span>
                        <span class="sm:w-20 flex sm:justify-end"><?php if ((int) $s['duracion'] > 0): ?><span class="chip-bl"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><?= (int) $s['duracion'] ?> min</span><?php endif; ?></span>
                    </span>
                    <span class="plata text-sm tabular-nums sm:w-20 sm:text-right"><?= $euros($s['precio']) ?></span>
                    <span class="estado-bl <?= $s['activo'] ? 'is-on' : '' ?>"><i></i><?= $s['activo'] ? 'Activo' : 'Inactivo' ?></span>
                    <span class="flex gap-1.5 ml-auto sm:w-[10.5rem] justify-end">
                        <a href="index.php?page=admin&seccion=servicios&editar=<?= $s['id_servicio'] ?>" class="seg-btn">Editar</a>
                        <?php if ($s['activo']): ?>
                        <a href="#" class="seg-btn btn-peligro" onclick="abrirConfirm('Dejará de aparecer en el punto de venta. Puedes volver a activarlo cuando quieras.', 'index.php?page=admin_eliminar_servicio&id=<?= $s['id_servicio'] ?>', '¿Desactivar este servicio?'); return false;">Desactivar</a>
                        <?php else: ?>
                        <a href="index.php?page=admin_activar_servicio&id=<?= $s['id_servicio'] ?>" class="seg-btn btn-ok">Activar</a>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </details>
        <?php endforeach; ?>

        <?php if (empty($servicios)): ?>
        <p class="text-xs text-[#737373] text-center py-6">Todavía no hay servicios. Pulsa "+ Nuevo servicio" para añadir el primero.</p>
        <?php endif; ?>
    </div>

    <?php elseif ($seccion === 'productos'):
        $euros    = fn ($n) => '€' . number_format((float) $n, 2);
        $POCO     = 10;  // con 10 o menos unidades se avisa de que hay que comprar más
        $activosP = array_values(array_filter($productos, fn ($p) => $p['activo']));
        $agotados = count(array_filter($productos, fn ($p) => (int) $p['stock'] === 0));
        $pocos    = count(array_filter($activosP, fn ($p) => (int) $p['stock'] > 0 && (int) $p['stock'] <= $POCO));
        $maxStock = $productos ? max(1, max(array_map(fn ($p) => (int) $p['stock'], $productos))) : 1;
    ?>
    <!-- PRODUCTOS -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h1 class="glow-text text-base font-medium text-[#f5f5f5]">Productos</h1>
            <p class="text-xs text-[#8a8a8a] mt-0.5">
                <?= count($activosP) ?> <?= count($activosP) === 1 ? 'activo' : 'activos' ?> de <?= count($productos) ?>
                <?php if ($agotados): ?> · <span class="text-[#fca5a5]"><?= $agotados ?> <?= $agotados === 1 ? 'agotado' : 'agotados' ?></span><?php endif; ?>
                <?php if ($pocos): ?> · <span class="text-[#fcd34d]"><?= $pocos ?> con poco stock</span><?php endif; ?>
            </p>
        </div>
    </div>

    <div class="space-y-3">

        <!-- Formulario: nuevo o editar -->
        <section id="form-producto" class="bl-panel card-glow bl-details form-fijo" style="--delay:-3s">
            <div class="form-cabecera">
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $editProducto ? '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>' : '<path d="M12 5v14M5 12h14"/>' ?></svg>
                <span class="titulo"><?= $editProducto ? 'Editar producto' : 'Nuevo producto' ?></span>
                <span class="resumen"><?= $editProducto ? htmlspecialchars($editProducto['nombre']) : 'Nombre, precio y stock' ?></span>
            </div>
            <div class="px-5 pb-5">
                <form method="POST" action="index.php?page=<?= $editProducto ? 'admin_actualizar_producto' : 'admin_crear_producto' ?>">
                    <?= csrf_field() ?>
                    <?php if ($editProducto): ?>
                    <input type="hidden" name="id" value="<?= $editProducto['id_producto'] ?>">
                    <?php endif; ?>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                        <div>
                            <label for="prod-nombre" class="etiqueta-bl">Nombre</label>
                            <input id="prod-nombre" type="text" name="nombre" required placeholder="Cera mate" value="<?= htmlspecialchars($editProducto['nombre'] ?? '') ?>" class="campo-bl">
                        </div>
                        <div>
                            <label for="prod-precio" class="etiqueta-bl">Precio (€)</label>
                            <input id="prod-precio" type="number" name="precio" step="0.01" min="0" required placeholder="9.50" value="<?= $editProducto['precio'] ?? '' ?>" class="campo-bl">
                        </div>
                        <div>
                            <label for="prod-stock" class="etiqueta-bl">Stock (unidades)</label>
                            <input id="prod-stock" type="number" name="stock" min="0" required placeholder="20" value="<?= $editProducto['stock'] ?? '' ?>" class="campo-bl">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="seg-btn is-active"><?= $editProducto ? 'Guardar cambios' : 'Añadir producto' ?></button>
                        <?php if ($editProducto): ?>
                        <a href="index.php?page=admin&seccion=productos" class="seg-btn">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>

        <!-- Catálogo con buscador -->
        <details class="bl-panel card-glow bl-details" style="--delay:-6s" <?= $editProducto ? '' : 'open' ?>>
            <summary>
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>
                <span class="titulo">Catálogo</span>
                <span class="resumen"><?= count($productos) ?> <?= count($productos) === 1 ? 'producto' : 'productos' ?></span>
                <span class="chev-pill"><svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></span>
            </summary>
            <div class="px-5 pb-4">
                <?php if (count($productos) > 5): ?>
                <div class="relative mb-2">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#5a5a5a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                    <label for="buscar-producto" class="sr-only">Buscar producto</label>
                    <input id="buscar-producto" type="search" placeholder="Buscar producto…" class="campo-bl" style="padding-left: 2.25rem" data-filtra="#lista-productos">
                </div>
                <?php endif; ?>
                <div id="lista-productos" class="space-y-1">
                    <?php foreach ($productos as $n => $p):
                        $st = (int) $p['stock'];
                        $nivel = $st === 0 ? 'agotado' : ($st <= $POCO ? 'poco' : 'ok');
                    ?>
                    <div class="fila flex flex-wrap items-center gap-x-4 gap-y-2 <?= $p['activo'] ? '' : 'opacity-60' ?>" style="--n:<?= $n ?>" data-nombre="<?= htmlspecialchars(mb_strtolower($p['nombre'])) ?>">
                        <!-- En el móvil, nombre y stock van juntos en la primera línea -->
                        <span class="flex items-center justify-between gap-3 basis-full sm:contents">
                            <span class="fila-nombre text-sm sm:flex-1 sm:min-w-[10rem]"><?= htmlspecialchars($p['nombre']) ?></span>
                            <span class="stock-bl stock-<?= $nivel ?>" title="<?= $st ?> unidades en stock">
                                <span class="stock-barra"><i style="width:<?= round(min(1, $st / $maxStock) * 100) ?>%"></i></span>
                                <span class="stock-texto"><?= $st === 0 ? 'Agotado' : ($nivel === 'poco' ? 'Quedan ' . $st : $st . ' uds') ?></span>
                                <?php if ($st === 0): ?><span class="sr-only"> (se activará solo al reponer stock)</span><?php endif; ?>
                            </span>
                        </span>
                        <span class="plata text-sm tabular-nums sm:w-20 sm:text-right"><?= $euros($p['precio']) ?></span>
                        <span class="estado-bl <?= $p['activo'] ? 'is-on' : '' ?>"><i></i><?= $p['activo'] ? 'Activo' : 'Inactivo' ?></span>
                        <span class="flex flex-wrap gap-1.5 ml-auto sm:w-[15.5rem] justify-end">
                            <a href="index.php?page=admin&seccion=productos&editar=<?= $p['id_producto'] ?>" class="seg-btn">Editar</a>
                            <?php if ($p['activo']): ?>
                            <a href="#" class="seg-btn btn-peligro" onclick="abrirConfirm('Dejará de aparecer en el punto de venta. Puedes volver a activarlo cuando quieras.', 'index.php?page=admin_eliminar_producto&id=<?= $p['id_producto'] ?>', '¿Desactivar este producto?'); return false;">Desactivar</a>
                            <?php else: ?>
                            <?php if ($st > 0): ?>
                            <a href="index.php?page=admin_activar_producto&id=<?= $p['id_producto'] ?>" class="seg-btn btn-ok">Activar</a>
                            <?php endif; ?>
                            <button type="button" class="seg-btn btn-peligro" onclick="abrirBorrado(<?= (int) $p['id_producto'] ?>, <?= htmlspecialchars(json_encode($p['nombre']), ENT_QUOTES) ?>)">Eliminar</button>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                    <p class="sin-resultados hidden text-xs text-[#737373] text-center py-4">Ningún producto coincide con la búsqueda.</p>
                    <?php if (empty($productos)): ?>
                    <p class="text-xs text-[#737373] text-center py-6">Todavía no hay productos. Pulsa "+ Nuevo producto" para añadir el primero.</p>
                    <?php endif; ?>
                </div>
            </div>
        </details>
    </div>

    <?php elseif ($seccion === 'empleados'):
        $optsE   = ['peluqueria' => 'Peluquería', 'trenzas' => 'Trenzas', 'uñas' => 'Uñas'];
        $selE    = $editEmpleado['especialidad'] ?? 'peluqueria';
        $activosE = count(array_filter($empleados, fn ($e) => $e['activo']));
        $inicial = fn ($nombre) => htmlspecialchars(mb_strtoupper(mb_substr($nombre, 0, 1)));
    ?>
    <!-- EMPLEADOS -->
    <div class="mb-4">
        <h1 class="glow-text text-base font-medium text-[#f5f5f5]">Empleados</h1>
        <p class="text-xs text-[#8a8a8a] mt-0.5"><?= $activosE ?> <?= $activosE === 1 ? 'activo' : 'activos' ?> de <?= count($empleados) ?></p>
    </div>

    <div class="space-y-3">

        <!-- Formulario fijo: nuevo o editar -->
        <section id="form-empleado" class="bl-panel card-glow bl-details form-fijo" style="--delay:-3s">
            <div class="form-cabecera">
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $editEmpleado ? '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>' : '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/>' ?></svg>
                <span class="titulo"><?= $editEmpleado ? 'Editar empleado' : 'Nuevo empleado' ?></span>
                <span class="resumen"><?= $editEmpleado ? htmlspecialchars($editEmpleado['nombre']) : 'Datos de acceso, especialidad y foto' ?></span>
            </div>
            <div class="px-5 pb-5">
                <form method="POST" enctype="multipart/form-data" action="index.php?page=<?= $editEmpleado ? 'admin_actualizar_empleado' : 'admin_crear_empleado' ?>">
                    <?= csrf_field() ?>
                    <?php if ($editEmpleado): ?>
                    <input type="hidden" name="id" value="<?= $editEmpleado['id_usuario'] ?>">
                    <?php endif; ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                        <div>
                            <label for="emp-nombre" class="etiqueta-bl">Nombre</label>
                            <input id="emp-nombre" type="text" name="nombre" required placeholder="Luis" value="<?= htmlspecialchars($editEmpleado['nombre'] ?? '') ?>" class="campo-bl">
                        </div>
                        <div>
                            <label for="emp-email" class="etiqueta-bl">Email</label>
                            <input id="emp-email" type="email" name="email" required placeholder="luis@beloyal.com" value="<?= htmlspecialchars($editEmpleado['email'] ?? '') ?>" class="campo-bl">
                        </div>
                        <div>
                            <label for="emp-password" class="etiqueta-bl"><?= $editEmpleado ? 'Nueva contraseña' : 'Contraseña' ?></label>
                            <input id="emp-password" type="password" name="password" <?= $editEmpleado ? '' : 'required' ?> autocomplete="new-password"
                                   placeholder="<?= $editEmpleado ? 'Déjala vacía para no cambiarla' : '••••••••' ?>" class="campo-bl">
                        </div>
                        <div>
                            <span class="etiqueta-bl">Especialidad</span>
                            <div class="custom-select relative">
                                <input type="hidden" name="especialidad" value="<?= $selE ?>">
                                <button type="button" class="custom-select-btn campo-bl flex items-center justify-between">
                                    <span class="custom-select-label"><?= $optsE[$selE] ?></span>
                                    <svg class="w-3.5 h-3.5 text-[#8a8a8a]" style="transition:transform .2s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="custom-select-dropdown hidden absolute top-full left-0 right-0 mt-1 bl-panel overflow-hidden z-50 py-1">
                                    <?php foreach ($optsE as $v => $l): ?>
                                    <div class="custom-select-option px-3 py-2 text-xs cursor-pointer transition <?= $v === $selE ? 'text-white bg-white/5' : 'text-zinc-400 hover:bg-zinc-700/50 hover:text-zinc-200' ?>" data-value="<?= $v ?>"><?= $l ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Foto: vista previa redonda, botón para elegir archivo y, al editar, quitar la actual -->
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <span id="foto-previa" class="avatar-bl avatar-grande">
                            <?php if (!empty($editEmpleado['logo'])): ?>
                            <img src="public/img/logos/<?= htmlspecialchars($editEmpleado['logo']) ?>" alt="" onerror="this.remove()">
                            <?php else: ?>
                            <?= $editEmpleado ? $inicial($editEmpleado['nombre']) : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/></svg>' ?>
                            <?php endif; ?>
                        </span>
                        <div class="flex flex-col gap-1.5">
                            <span class="etiqueta-bl !mb-0">Foto del empleado <span class="text-[#737373]">(opcional)</span></span>
                            <div class="flex flex-wrap items-center gap-2">
                                <label class="seg-btn cursor-pointer">
                                    Elegir foto
                                    <input id="emp-foto" type="file" name="foto_empleado" accept="image/jpeg,image/png,image/webp" class="sr-only">
                                </label>
                                <span id="foto-nombre" class="text-xs text-[#8a8a8a]">JPG, PNG o WEBP. Sale en la cabecera de su panel.</span>
                            </div>
                            <?php if (!empty($editEmpleado['logo'])): ?>
                            <label class="check-bl"><input type="checkbox" name="quitar_foto" value="1"><span></span>Quitar la foto actual</label>
                            <?php endif; ?>
                        </div>
                        <?php if ($editEmpleado): ?>
                        <label class="check-bl sm:ml-auto"><input type="checkbox" name="activo" value="1" <?= $editEmpleado['activo'] ? 'checked' : '' ?>><span></span>Empleado activo</label>
                        <?php endif; ?>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="seg-btn is-active"><?= $editEmpleado ? 'Guardar cambios' : 'Añadir empleado' ?></button>
                        <?php if ($editEmpleado): ?>
                        <a href="index.php?page=admin&seccion=empleados" class="seg-btn">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </section>

        <!-- Equipo -->
        <details class="bl-panel card-glow bl-details" style="--delay:-6s" open>
            <summary>
                <span class="globo-mini" aria-hidden="true"><span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span><span class="lat"></span></span>
                <svg class="icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="titulo">Equipo</span>
                <span class="resumen"><?= count($empleados) ?> <?= count($empleados) === 1 ? 'empleado' : 'empleados' ?></span>
                <span class="chev-pill"><svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></span>
            </summary>
            <div class="px-5 pb-4 space-y-1">
                <?php foreach ($empleados as $n => $emp): ?>
                <div class="fila flex flex-wrap items-center gap-x-4 gap-y-2 <?= $emp['activo'] ? '' : 'opacity-60' ?>" style="--n:<?= $n ?>">
                    <span class="flex items-center gap-3 basis-full sm:basis-auto sm:flex-1 min-w-0">
                        <span class="avatar-bl">
                            <?php if (!empty($emp['logo'])): ?>
                            <img src="public/img/logos/<?= htmlspecialchars($emp['logo']) ?>" alt="" onerror="this.remove()">
                            <?php endif; ?>
                            <?= $inicial($emp['nombre']) ?>
                        </span>
                        <span class="min-w-0">
                            <span class="fila-nombre text-sm block truncate"><?= htmlspecialchars($emp['nombre']) ?></span>
                            <span class="text-xs text-[#8a8a8a] block truncate"><?= htmlspecialchars($emp['email']) ?></span>
                        </span>
                    </span>
                    <span class="chip-bl w-[6.5rem] justify-center"><?= htmlspecialchars($optsE[$emp['especialidad']] ?? ucfirst($emp['especialidad'])) ?></span>
                    <span class="estado-bl <?= $emp['activo'] ? 'is-on' : '' ?>"><i></i><?= $emp['activo'] ? 'Activo' : 'Inactivo' ?></span>
                    <span class="flex flex-wrap gap-1.5 ml-auto sm:w-[15.5rem] justify-end">
                        <a href="index.php?page=admin&seccion=empleados&editar=<?= $emp['id_usuario'] ?>" class="seg-btn">Editar</a>
                        <?php if ($emp['activo']): ?>
                        <a href="#" class="seg-btn btn-peligro" onclick="abrirConfirm('No podrá entrar al punto de venta. Puedes volver a activarlo cuando quieras, y eliminarlo del todo mientras esté inactivo.', 'index.php?page=admin_eliminar_empleado&id=<?= $emp['id_usuario'] ?>', '¿Desactivar a <?= htmlspecialchars(addslashes($emp['nombre']), ENT_QUOTES) ?>?'); return false;">Desactivar</a>
                        <?php else: ?>
                        <a href="index.php?page=admin_activar_empleado&id=<?= $emp['id_usuario'] ?>" class="seg-btn btn-ok">Activar</a>
                        <button type="button" class="seg-btn btn-peligro" onclick="abrirBorrado(<?= (int) $emp['id_usuario'] ?>, <?= htmlspecialchars(json_encode($emp['nombre']), ENT_QUOTES) ?>, 'admin_eliminar_definitivo_empleado', 'Si el empleado tiene ventas registradas no se borrará, para no estropear el historial.')">Eliminar</button>
                        <?php endif; ?>
                    </span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($empleados)): ?>
                <p class="text-xs text-[#737373] text-center py-6">Todavía no hay empleados. Rellena el formulario de arriba para añadir el primero.</p>
                <?php endif; ?>
            </div>
        </details>
    </div>

    <?php endif; ?>

</main>


<!-- MODAL CONFIRMACIÓN GENÉRICO -->
<div id="confirm-modal" style="display:none;" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-zinc-900 border border-white/10 rounded-2xl p-7 w-80 max-w-full mx-4 shadow-2xl">
        <p class="text-base font-semibold text-white mb-1" id="confirm-title">¿Estás seguro?</p>
        <p class="text-xs text-zinc-500 mb-6" id="confirm-msg"></p>
        <div class="flex gap-3">
            <button onclick="cerrarConfirm()" class="flex-1 border border-white/10 text-xs text-zinc-400 py-3 rounded-xl hover:text-white hover:border-white/30 transition">
                Cancelar
            </button>
            <a id="confirm-btn" href="#" class="flex-1 text-center bg-red-600 text-white text-xs font-semibold py-3 rounded-xl hover:bg-red-500 transition">
                Confirmar
            </a>
        </div>
    </div>
</div>

<!-- MODAL ELIMINAR DEFINITIVAMENTE: productos y empleados (formulario POST con CSRF) -->
<div id="borrar-modal" style="display:none;" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bl-panel card-glow p-6 w-[22rem] max-w-full mx-4" role="dialog" aria-modal="true" aria-labelledby="borrar-titulo">
        <p id="borrar-titulo" class="glow-text text-base font-medium text-white mb-1">¿Eliminar definitivamente?</p>
        <p class="text-xs text-[#b5b5b5] mb-1"><span id="borrar-nombre" class="text-white"></span> se borrará para siempre y no se podrá recuperar.</p>
        <p id="borrar-nota" class="text-xs text-[#8a8a8a] mb-5">Si el producto aparece en alguna venta no se borrará, para no estropear el historial.</p>
        <form id="borrar-form" method="POST" action="index.php?page=admin_eliminar_definitivo_producto" class="flex gap-2">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="borrar-id" value="">
            <button type="button" onclick="cerrarBorrado()" class="seg-btn flex-1">Cancelar</button>
            <button type="submit" class="seg-btn btn-peligro flex-1">Eliminar para siempre</button>
        </form>
    </div>
</div>

<!-- MODAL RESETEAR VENTAS (con contraseña) -->
<div id="reset-modal" style="display:none;" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-zinc-900 border border-white/10 rounded-2xl p-7 w-80 max-w-full mx-4 shadow-2xl">
        <p class="text-base font-semibold text-white mb-1">Resetear ventas</p>
        <p class="text-xs text-zinc-500 mb-5">Esta acción eliminará <strong class="text-red-400">TODAS</strong> las ventas y no se puede deshacer. Introduce tu contraseña para confirmar.</p>
        <form method="POST" action="index.php?page=admin_resetear_ventas">
            <?= csrf_field() ?>
            <input type="password" name="password_confirm" id="reset-pw-input"
                   placeholder="Tu contraseña de administrador"
                   autocomplete="current-password"
                   class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-red-500/50 transition mb-4 placeholder-zinc-600">
            <div class="flex gap-3">
                <button type="button" onclick="cerrarResetModal()" class="flex-1 border border-white/10 text-xs text-zinc-400 py-3 rounded-xl hover:text-white hover:border-white/30 transition">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 bg-red-600 text-white text-xs font-semibold py-3 rounded-xl hover:bg-red-500 transition">
                    Confirmar y eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CONTADOR DE INACTIVIDAD -->
<div id="inactivity-badge" style="display:none;"
     class="fixed bottom-4 right-4 z-50 bg-zinc-900 border border-white/10 rounded-xl px-3 py-2 text-xs text-zinc-500 shadow-lg select-none">
    Sesión expira en <span id="inactivity-secs" class="text-white font-medium"></span>s
</div>

<script>
// ── Fondo: sala de columnas ─────────────────────────────────────────────────
(function () {
    const lienzo = document.getElementById('fondo-sala');
    if (!lienzo) return;
    const c = lienzo.getContext('2d');
    const gris = v => 'rgb(' + v + ',' + v + ',' + v + ')';

    function dibujar() {
        const dpr = Math.min(window.devicePixelRatio || 1, 2), w = innerWidth, h = innerHeight;
        lienzo.width = Math.round(w * dpr); lienzo.height = Math.round(h * dpr);
        c.setTransform(dpr, 0, 0, dpr, 0, 0);
        c.fillStyle = '#050505'; c.fillRect(0, 0, w, h);

        // cámara: proyecta (x, y, z) con el horizonte a media altura
        const f = Math.min(w, h) * 1.1, hy = h * .5, alturaCam = 1.4;
        const P = (x, y, z) => [w / 2 + x * f / z, hy - (y - alturaCam) * f / z];
        const fin = P(0, alturaCam, 80);

        // luz al fondo de la sala
        let g = c.createRadialGradient(fin[0], fin[1], 0, fin[0], fin[1], Math.max(w, h) * .5);
        g.addColorStop(0, 'rgba(255,255,255,.16)'); g.addColorStop(1, 'rgba(255,255,255,0)');
        c.fillStyle = g; c.fillRect(0, 0, w, h);

        // juntas del suelo
        c.strokeStyle = 'rgba(255,255,255,.06)'; c.lineWidth = 1;
        for (let x = -6; x <= 6; x += .75) { const a = P(x, 0, 1), b = P(x, 0, 80); c.beginPath(); c.moveTo(a[0], a[1]); c.lineTo(b[0], b[1]); c.stroke(); }
        for (let z = 1.5; z < 80; z *= 1.18) { const a = P(-30, 0, z), b = P(30, 0, z); c.beginPath(); c.moveTo(a[0], a[1]); c.lineTo(b[0], b[1]); c.stroke(); }

        // franja de luz en el horizonte
        g = c.createLinearGradient(0, fin[1] - 60, 0, fin[1] + 40);
        g.addColorStop(0, 'rgba(255,255,255,0)'); g.addColorStop(.6, 'rgba(255,255,255,.08)'); g.addColorStop(1, 'rgba(255,255,255,0)');
        c.fillStyle = g; c.fillRect(0, fin[1] - 60, w, 100);

        // columnas, de lejos a cerca; la cara que mira al centro recibe la luz del fondo
        for (let z = 60; z >= 2.5; z -= 3.2) {
            [-2.4, 2.4].forEach(function (x) {
                const r = .32, base = P(x, 0, z), arriba = P(x, 7, z), ancho = P(x + r, 0, z)[0] - base[0];
                const k = Math.min(1, 6 / z);
                const rg = c.createLinearGradient(0, base[1], 0, base[1] + ancho * 6);     // reflejo en el suelo
                rg.addColorStop(0, 'rgba(255,255,255,' + (.05 * k).toFixed(3) + ')'); rg.addColorStop(1, 'rgba(255,255,255,0)');
                c.fillStyle = rg; c.fillRect(base[0] - ancho, base[1], ancho * 2, ancho * 6);
                const fuste = c.createLinearGradient(base[0] - ancho, 0, base[0] + ancho, 0), izq = x < 0;
                const claro = gris(Math.round(30 + 45 * k)), oscuro = gris(Math.round(8 + 10 * k));
                fuste.addColorStop(0, izq ? oscuro : claro); fuste.addColorStop(.5, gris(Math.round(18 + 22 * k))); fuste.addColorStop(1, izq ? claro : oscuro);
                c.fillStyle = fuste; c.fillRect(base[0] - ancho, arriba[1], ancho * 2, base[1] - arriba[1]);
                c.fillStyle = gris(Math.round(14 + 30 * k));                                // basa con filo de luz
                c.fillRect(base[0] - ancho * 1.35, base[1] - ancho * .5, ancho * 2.7, ancho * .5);
                c.strokeStyle = 'rgba(255,255,255,' + (.25 * k).toFixed(3) + ')';
                c.beginPath(); c.moveTo(base[0] - ancho * 1.35, base[1] - ancho * .5); c.lineTo(base[0] + ancho * 1.35, base[1] - ancho * .5); c.stroke();
            });
        }

        // viñeta y un velo suave para que el contenido se lea bien encima
        g = c.createRadialGradient(w / 2, h / 2, Math.min(w, h) * .25, w / 2, h / 2, Math.max(w, h) * .75);
        g.addColorStop(0, 'rgba(0,0,0,0)'); g.addColorStop(1, 'rgba(0,0,0,.7)');
        c.fillStyle = g; c.fillRect(0, 0, w, h);
        c.fillStyle = 'rgba(0,0,0,.25)'; c.fillRect(0, 0, w, h);
    }

    let pendiente = 0;
    addEventListener('resize', function () { cancelAnimationFrame(pendiente); pendiente = requestAnimationFrame(dibujar); });
    dibujar();
})();

// ── Sidebar móvil ────────────────────────────────────────────────────────────
(function () {
    const sidebar  = document.getElementById('sidebar');
    const toggle   = document.getElementById('sidebar-toggle');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar || !toggle || !backdrop) return;
    backdrop.classList.remove('open');
    function open()  { sidebar.classList.add('open');    backdrop.classList.add('open');    }
    function close() { sidebar.classList.remove('open'); backdrop.classList.remove('open'); }
    toggle.addEventListener('click', open);
    backdrop.addEventListener('click', close);
    sidebar.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
})();

function abrirConfirm(msg, url, titulo) {
    document.getElementById('confirm-title').textContent = titulo || '¿Estás seguro?';
    document.getElementById('confirm-msg').textContent = msg;
    document.getElementById('confirm-btn').href = url;
    document.getElementById('confirm-modal').style.display = 'flex';
}
function cerrarConfirm() {
    document.getElementById('confirm-modal').style.display = 'none';
}
document.getElementById('confirm-modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarConfirm();
});

// accion y nota son opcionales: por defecto, borrar un producto
function abrirBorrado(id, nombre, accion, nota) {
    document.getElementById('borrar-id').value = id;
    document.getElementById('borrar-nombre').textContent = nombre;
    document.getElementById('borrar-form').action = 'index.php?page=' + (accion || 'admin_eliminar_definitivo_producto');
    document.getElementById('borrar-nota').textContent = nota || 'Si el producto aparece en alguna venta no se borrará, para no estropear el historial.';
    document.getElementById('borrar-modal').style.display = 'flex';
    setTimeout(() => document.querySelector('#borrar-modal .seg-btn').focus(), 50);
}
function cerrarBorrado() {
    document.getElementById('borrar-modal').style.display = 'none';
}
document.getElementById('borrar-modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarBorrado();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarBorrado();
});

function abrirResetModal() {
    document.getElementById('reset-pw-input').value = '';
    document.getElementById('reset-modal').style.display = 'flex';
    setTimeout(() => document.getElementById('reset-pw-input').focus(), 50);
}
function cerrarResetModal() {
    document.getElementById('reset-modal').style.display = 'none';
}
document.getElementById('reset-modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarResetModal();
});

// ── Dashboard: "Ver más" ventas y pestañas de lo más vendido ────────────────
(function () {
    const verMas = document.getElementById('ver-mas-ventas');
    if (verMas) {
        verMas.addEventListener('click', function () {
            const abrir = verMas.textContent.trim() !== verMas.dataset.menos;
            document.querySelectorAll('.venta-extra').forEach(tr => tr.classList.toggle('hidden', !abrir));
            verMas.textContent = abrir ? verMas.dataset.menos : verMas.dataset.mas;
        });
    }
    document.querySelectorAll('.top-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.top-tab').forEach(function (t) {
                t.classList.toggle('is-active', t === tab);
                t.setAttribute('aria-selected', String(t === tab));
            });
            document.querySelectorAll('.top-panel').forEach(p => p.hidden = p.id !== tab.dataset.tab);
        });
    });

    // Onda de luz desde el punto donde se pulsa la cabecera de un desplegable
    document.querySelectorAll('.bl-details > summary').forEach(function (sum) {
        sum.addEventListener('pointerdown', function (e) {
            const r = sum.getBoundingClientRect();
            const o = document.createElement('span');
            o.className = 'onda';
            o.style.left = (e.clientX - r.left) + 'px';
            o.style.top  = (e.clientY - r.top) + 'px';
            sum.appendChild(o);
            o.addEventListener('animationend', () => o.remove());
            setTimeout(() => o.remove(), 800);
        });
    });

    // Los desplegables y la tarjeta de ingresos se inclinan un poco hacia el cursor, como la tarjeta del login
    if (matchMedia('(pointer: fine)').matches && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('.bl-details, .bl-3d').forEach(function (d) {
            d.addEventListener('pointermove', function (e) {
                const r = d.getBoundingClientRect();
                const nx = (e.clientX - r.left) / r.width - 0.5, ny = (e.clientY - r.top) / r.height - 0.5;
                d.style.setProperty('--rx', (-ny * 2).toFixed(2) + 'deg');
                d.style.setProperty('--ry', (nx * 1.5).toFixed(2) + 'deg');
            });
            d.addEventListener('pointerleave', function () {
                d.style.removeProperty('--rx');
                d.style.removeProperty('--ry');
            });
        });
    }

    // Tooltip: al pasar el ratón o con el teclado sobre una barra
    const tip = document.getElementById('chart-tip');
    if (tip) {
        function mostrar(el) {
            tip.textContent = el.dataset.tip;
            tip.hidden = false;
            const r = el.getBoundingClientRect(), t = tip.getBoundingClientRect();
            const x = Math.min(Math.max(8, r.left + r.width / 2 - t.width / 2), innerWidth - t.width - 8);
            const y = r.top - t.height - 8 < 8 ? r.bottom + 8 : r.top - t.height - 8;
            tip.style.left = x + 'px';
            tip.style.top  = y + 'px';
        }
        document.addEventListener('pointerover', e => { const el = e.target.closest('[data-tip]'); if (el) mostrar(el); else tip.hidden = true; });
        document.addEventListener('focusin',     e => { const el = e.target.closest('[data-tip]'); if (el) mostrar(el); else tip.hidden = true; });
        document.querySelector('main').addEventListener('scroll', () => tip.hidden = true);
    }
})();

// ── Foto del empleado: muestra la imagen elegida antes de guardar ────────────
(function () {
    const campo = document.getElementById('emp-foto');
    if (!campo) return;
    campo.addEventListener('change', function () {
        const f = campo.files && campo.files[0];
        if (!f) return;
        document.getElementById('foto-nombre').textContent = f.name;
        const img = document.createElement('img');
        img.alt = '';
        img.src = URL.createObjectURL(f);
        document.getElementById('foto-previa').replaceChildren(img);
    });
})();

// ── Buscador: filtra las filas por nombre al escribir ───────────────────────
document.querySelectorAll('[data-filtra]').forEach(function (campo) {
    const lista = document.querySelector(campo.dataset.filtra);
    if (!lista) return;
    campo.addEventListener('input', function () {
        const texto = campo.value.trim().toLowerCase();
        let visibles = 0;
        lista.querySelectorAll('[data-nombre]').forEach(function (fila) {
            const ver = fila.dataset.nombre.includes(texto);
            fila.classList.toggle('hidden', !ver);
            if (ver) visibles++;
        });
        const aviso = lista.querySelector('.sin-resultados');
        if (aviso) aviso.classList.toggle('hidden', visibles > 0);
    });
});

// ── Custom selects ──────────────────────────────────────────────────────────
(function () {
    function findPanel(el) {
        var node = el.parentElement;
        while (node && node !== document.body) {
            if (node.classList && (node.classList.contains('bl-panel') || (node.classList.contains('rounded-2xl') && node.classList.contains('backdrop-blur-sm')))) {
                return node;
            }
            node = node.parentElement;
        }
        return null;
    }

    function closeAll() {
        document.querySelectorAll('.custom-select-dropdown').forEach(function(d) {
            d.classList.add('hidden');
        });
        document.querySelectorAll('.custom-select-btn svg').forEach(function(s) {
            s.style.transform = '';
        });
        document.querySelectorAll('[data-cs-bumped]').forEach(function(p) {
            p.style.zIndex = '';
            p.style.position = '';
            p.removeAttribute('data-cs-bumped');
        });
    }

    document.querySelectorAll('.custom-select').forEach(function(wrap) {
        var btn      = wrap.querySelector('.custom-select-btn');
        var dropdown = wrap.querySelector('.custom-select-dropdown');
        var hidden   = wrap.querySelector('input[type=hidden]');
        var label    = wrap.querySelector('.custom-select-label');
        var chevron  = btn.querySelector('svg');

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = !dropdown.classList.contains('hidden');
            closeAll();
            if (!isOpen) {
                var panel = findPanel(wrap);
                if (panel) {
                    panel.style.position = 'relative';
                    panel.style.zIndex = '40';
                    panel.setAttribute('data-cs-bumped', '1');
                }
                dropdown.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            }
        });

        wrap.querySelectorAll('.custom-select-option').forEach(function(opt) {
            opt.addEventListener('click', function() {
                hidden.value = opt.dataset.value;
                label.textContent = opt.textContent.trim();
                wrap.querySelectorAll('.custom-select-option').forEach(function(o) {
                    o.classList.remove('text-white', 'bg-white/5');
                    o.classList.add('text-zinc-400');
                    o.classList.remove('hover:bg-zinc-700/50', 'hover:text-zinc-200');
                });
                opt.classList.add('text-white', 'bg-white/5');
                opt.classList.remove('text-zinc-400');
                dropdown.classList.add('hidden');
                chevron.style.transform = '';
            });
        });
    });

    document.addEventListener('click', closeAll);
})();

// ── Contador de inactividad ──────────────────────────────────────────────────
(function () {
    const TIMEOUT   = 300;
    const WARN_AT   = 60;
    const badge     = document.getElementById('inactivity-badge');
    const secsEl    = document.getElementById('inactivity-secs');
    let remaining   = TIMEOUT;
    let interval;

    function resetTimer() {
        remaining = TIMEOUT;
        badge.style.display = 'none';
    }

    function tick() {
        remaining--;
        if (remaining <= 0) {
            clearInterval(interval);
            window.location.href = 'index.php?page=logout';
            return;
        }
        if (remaining <= WARN_AT) {
            badge.style.display = 'block';
            secsEl.textContent  = remaining;
        } else {
            badge.style.display = 'none';
        }
    }

    ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function(evt) {
        document.addEventListener(evt, resetTimer, true);
    });

    interval = setInterval(tick, 1000);
})();
</script>

</body>
</html>