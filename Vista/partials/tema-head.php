<?php
/**
 * Tema visual común de Be Loyal (negro y grises, fondo 3D, bordes luminosos).
 * Se incluye dentro del <head> de cada vista:
 *   <?php include __DIR__ . '/partials/tema-head.php'; ?>
 */
?>
    <!-- Tipografía: Inter (DM Sans se mantiene para la pasarela de pago) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS vía CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    inter: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    dm: ["'DM Sans'", 'Arial', 'sans-serif'],
                },
            }
        }
    }
    </script>

    <style>
        /* ---------- Fondo: degradado de gris (arriba) a negro (abajo) ---------- */
        body.bl-body {
            background-color: #000;
            background-image:
                radial-gradient(ellipse 120% 70% at 50% -10%, #3a3a3a 0%, #1f1f1f 38%, transparent 70%),
                linear-gradient(180deg, #262626 0%, #121212 45%, #050505 80%, #000 100%);
            background-attachment: fixed;
        }

        /* Scrollbar discreta */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #3f3f3f; border-radius: 2px; }

        /* ---------- Escena 3D animada (detrás de todo) ---------- */
        .scene {
            position: fixed;
            inset: 0;
            overflow: hidden;
            perspective: 1400px;
            pointer-events: none;
            z-index: 0;
            --g: min(max(110vmin, 85vmax), 1100px);
        }
        .scene-depth { position: absolute; inset: 0; will-change: transform; }
        .globe {
            position: absolute;
            aspect-ratio: 1;
            transform-style: preserve-3d;
            animation: globe-spin 70s linear infinite;
        }
        .globe--main  { width: var(--g); top: 50%; left: 50%; margin: calc(var(--g) / -2) 0 0 calc(var(--g) / -2); }
        .globe--small { width: min(38vmin, 320px); top: 6%; right: -6%; animation-duration: 45s; animation-direction: reverse; }
        .globe span {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.06);
            transform: rotateY(calc(var(--i) * 22.5deg));
        }
        .globe span.lat {
            border-color: rgba(255, 255, 255, 0.045);
            transform: rotateX(90deg) translateZ(var(--z)) scale(var(--s));
        }
        @keyframes globe-spin {
            from { transform: rotateX(-22deg) rotateZ(8deg) rotateY(0deg); }
            to   { transform: rotateX(-22deg) rotateZ(8deg) rotateY(360deg); }
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            background: radial-gradient(circle, rgba(255,255,255,0.09), transparent 70%);
            animation: orb-float 26s ease-in-out infinite alternate;
        }
        .orb--1 { width: 46vmax; height: 46vmax; top: -18vmax; left: -12vmax; }
        .orb--2 { width: 34vmax; height: 34vmax; bottom: -14vmax; right: -10vmax; animation-duration: 32s; animation-delay: -8s; }
        @keyframes orb-float {
            from { transform: translate3d(0, 0, 0) scale(1); }
            to   { transform: translate3d(6vmax, 4vmax, 0) scale(1.15); }
        }
        .scene::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,0.7) 100%);
        }

        /* Luz que sigue al cursor */
        .cursor-light {
            position: absolute;
            left: 0; top: 0;
            width: 640px; height: 640px;
            border-radius: 50%;
            background: radial-gradient(closest-side, rgba(255,255,255,0.07), rgba(255,255,255,0.025) 50%, transparent 100%);
            opacity: 0;
            transition: opacity .6s ease;
            will-change: transform;
        }

        /* ---------- Tarjetas ---------- */
        .bl-card {
            position: relative;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.02), 0 18px 40px -24px rgba(0,0,0,0.9);
            transition: border-color .3s ease, box-shadow .3s ease, transform .3s ease;
        }
        /* Reflejo de luz que sigue al cursor dentro de la tarjeta */
        .bl-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            opacity: 0;
            transition: opacity .4s ease;
            background: radial-gradient(320px circle at var(--sx, 50%) var(--sy, 0%), rgba(255,255,255,0.06), transparent 65%);
        }
        .bl-card:hover {
            border-color: #3d3d3d;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.04), 0 0 26px rgba(255,255,255,0.05), 0 18px 40px -24px rgba(0,0,0,0.9);
        }
        .bl-card:hover::before { opacity: 1; }

        /* Borde luminoso animado: destello que recorre el contorno */
        @property --angle {
            syntax: "<angle>";
            initial-value: 0deg;
            inherits: false;
        }
        .bl-glow::after {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            pointer-events: none;
            background: conic-gradient(from var(--angle),
                transparent 0deg, rgba(255,255,255,0.85) 60deg, transparent 120deg,
                transparent 180deg, rgba(255,255,255,0.4) 240deg, transparent 300deg);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
                    mask-composite: exclude;
            animation: border-spin 9s linear infinite;
        }
        @keyframes border-spin { to { --angle: 360deg; } }

        /* ---------- Letras iluminadas ---------- */
        .glow-title {
            text-shadow: 0 0 1px rgba(255,255,255,0.55), 0 0 10px rgba(255,255,255,0.28), 0 0 24px rgba(255,255,255,0.1);
        }
        .glow-text { text-shadow: 0 0 8px rgba(255,255,255,0.18); }

        /* ---------- Logo retroiluminado ---------- */
        .logo-glow {
            filter:
                drop-shadow(0 0 1px rgba(255,255,255,0.6))
                drop-shadow(0 0 7px rgba(255,255,255,0.25))
                drop-shadow(0 0 18px rgba(255,255,255,0.1));
        }

        /* ---------- Botón principal: se eleva y brilla ---------- */
        .btn-glow { transition: background-color .2s ease, transform .2s ease, box-shadow .3s ease; }
        .btn-glow:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 18px rgba(255,255,255,0.2), 0 8px 18px -8px rgba(0,0,0,0.8);
        }

        /* ---------- Estado "Activo" / "Inactivo" ---------- */
        .bl-pill-on  { background: rgba(255,255,255,0.09); color: #e5e5e5; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.12); }
        .bl-pill-off { background: #1c1c1c; color: #737373; box-shadow: inset 0 0 0 1px #2a2a2a; }

        /* ---------- Entrada escalonada ---------- */
        .reveal { animation: fade-up .6s cubic-bezier(.2,.7,.2,1) backwards; animation-delay: var(--d, 0s); }
        @keyframes fade-up { from { opacity: 0; transform: translateY(10px); } }

        /* Accesibilidad: sin animación si el usuario lo prefiere */
        @media (prefers-reduced-motion: reduce) {
            .globe, .orb, .bl-glow::after, .reveal { animation: none; }
            .cursor-light, .bl-card::before { display: none; }
            .btn-glow:hover { transform: none; }
        }
    </style>
