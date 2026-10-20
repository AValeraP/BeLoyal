<?php
$POCO    = 10; // a partir de aquí la bebida avisa de que quedan pocas
$inicial = htmlspecialchars(mb_strtoupper(mb_substr($trabajador['nombre'], 0, 1)));
$nombresEsp = ['peluqueria' => 'Peluquería', 'trenzas' => 'Trenzas', 'uñas' => 'Uñas', 'todas' => 'Todas'];
$tarjeta = function (array $item, string $prefijo, string $tipo, string $icono, ?string $etiqueta = null, ?int $stock = null) use ($POCO) {
    ob_start(); ?>
            <button type="button" class="item-card bl-3d" data-prefix="<?= $prefijo ?>" data-tipo="<?= $tipo ?>" data-id="<?= (int)$item['id'] ?>"
                    data-precio="<?= (float)$item['precio'] ?>" data-nombre="<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>"<?= $stock !== null ? ' data-stock="' . $stock . '"' : '' ?>>
                <span class="item-cabeza">
                    <svg class="item-icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $icono ?></svg>
                    <?php if ($etiqueta): ?><span class="item-etiqueta"><?= $etiqueta ?></span><?php endif; ?>
                    <?php if ($stock !== null && $stock <= $POCO): ?><span class="item-quedan">Quedan <?= $stock ?></span><?php endif; ?>
                    <span class="item-cuenta" aria-hidden="true"></span>
                </span>
                <span class="item-nombre"><?= htmlspecialchars($item['nombre']) ?></span>
                <span class="item-precio plata">€<?= number_format($item['precio'], 2, ',', '.') ?></span>
            </button>
<?php return ob_get_clean();
};
$ICONO_SERVICIO = '<circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4L8.12 15.88"/><path d="M14.47 14.48L20 20"/><path d="M8.12 8.12L12 12"/>';
$ICONO_BONO     = '<path d="M3 9a2 2 0 0 0 0 4v4a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-4a2 2 0 0 1 0-4V5a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1z" transform="translate(0 1)"/><path d="M14 5v2M14 11v2M14 17v2"/>';
$ICONO_BEBIDA   = '<path d="M6 3h12l-1.5 17a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1z"/><path d="M5.5 8h13"/>';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV – <?= htmlspecialchars($usuario['nombre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    inter: ['Inter', 'sans-serif'],
                    dm: ["'DM Sans'", 'Arial', 'sans-serif'],
                },
            }
        }
    }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 2px; }

        /* ---------- Estilo del login y del panel de administración ---------- */
        .bl-bg { background: #000; }
        .bl-panel {
            background: #171717; border: 1px solid #2a2a2a; border-radius: .75rem;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.04), 0 0 28px rgba(255,255,255,0.05), 0 30px 60px -20px rgba(0,0,0,0.8);
        }
        /* Borde luminoso que recorre el contorno */
        @property --angle { syntax: "<angle>"; initial-value: 0deg; inherits: false; }
        .card-glow { position: relative; }
        .card-glow::before {
            content: ""; position: absolute; inset: -1px; padding: 1px; border-radius: inherit; pointer-events: none;
            background: conic-gradient(from var(--angle), transparent 0deg, rgba(255,255,255,0.8) 60deg, transparent 120deg, transparent 180deg, rgba(255,255,255,0.4) 240deg, transparent 300deg);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude;
            animation: border-spin 9s linear infinite; animation-delay: var(--delay, 0s);
        }
        @keyframes border-spin { to { --angle: 360deg; } }
        /* Relieve y giro 3D hacia el cursor */
        .bl-3d {
            box-shadow: inset 0 1px 0 rgba(255,255,255,.07), inset 0 -1px 0 rgba(0,0,0,.6),
                        0 0 0 1px rgba(255,255,255,0.04), 0 0 28px rgba(255,255,255,0.05), 0 30px 60px -20px rgba(0,0,0,0.8);
            transform: perspective(1000px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
            transition: transform .3s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .plata {
            background: linear-gradient(180deg, #ffffff 0%, #d4d4d4 45%, #8a8a8a 100%);
            -webkit-background-clip: text; background-clip: text; color: transparent;
            filter: drop-shadow(0 0 5px rgba(255,255,255,.35));
        }
        .titulo-luz { color: #fff; text-shadow: 0 0 1px rgba(255,255,255,.7), 0 0 14px rgba(255,255,255,.4), 0 0 30px rgba(255,255,255,.18); }
        .sep-luz { height: 1px; flex: 1; background: linear-gradient(90deg, rgba(255,255,255,.18), transparent); }
        .seg-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .375rem;
            padding: .375rem .75rem; font-size: .75rem; border-radius: .5rem; color: #8a8a8a;
            background: linear-gradient(180deg, #232323, #161616); border: 1px solid #2f2f2f;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.08);
            transition: color .2s, border-color .2s, box-shadow .2s, transform .12s;
        }
        .seg-btn:hover { color: #f5f5f5; border-color: #4a4a4a; box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 0 10px rgba(255,255,255,.12); text-shadow: 0 0 8px rgba(255,255,255,.4); }
        .seg-btn:active { transform: scale(.96); }
        .seg-btn:focus-visible { outline: 2px solid #8a8a8a; outline-offset: 2px; }
        .seg-btn:disabled { opacity: .35; cursor: not-allowed; box-shadow: none; text-shadow: none; }
        .btn-peligro { color: rgba(248,113,113,.8); border-color: rgba(248,113,113,.28); }
        .btn-peligro:hover { color: #fca5a5; border-color: rgba(248,113,113,.6); box-shadow: inset 0 1px 0 rgba(255,255,255,.1), 0 0 12px rgba(248,113,113,.3); text-shadow: 0 0 8px rgba(248,113,113,.5); }
        .btn-salir:hover { color: #fca5a5; border-color: rgba(248,113,113,.5); text-shadow: 0 0 8px rgba(248,113,113,.5); box-shadow: inset 0 1px 0 rgba(255,255,255,.1), 0 0 12px rgba(248,113,113,.25); }
        /* Botón principal: plata pulida */
        .btn-plata {
            position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; gap: .5rem;
            width: 100%; padding: .8rem 1rem; border-radius: .75rem; font-size: .875rem; font-weight: 600; color: #0a0a0a;
            background: linear-gradient(180deg, #ffffff, #c9c9c9 55%, #a8a8a8); border: 1px solid #e5e5e5;
            box-shadow: inset 0 1px 0 #fff, 0 0 16px rgba(255,255,255,.22), 0 8px 20px -8px rgba(0,0,0,.8);
            transition: box-shadow .2s, transform .12s, filter .2s;
        }
        .btn-plata::after { content: ""; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(105deg, transparent 35%, rgba(255,255,255,.8) 50%, transparent 65%); transform: translateX(-100%); }
        .btn-plata:hover { box-shadow: inset 0 1px 0 #fff, 0 0 26px rgba(255,255,255,.35), 0 8px 20px -8px rgba(0,0,0,.8); }
        .btn-plata:hover::after { transform: translateX(100%); transition: transform .9s ease; }
        .btn-plata:active { transform: scale(.98); }
        .btn-plata:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }
        .btn-plata:disabled { color: #5a5a5a; background: linear-gradient(180deg, #232323, #161616); border-color: #2f2f2f; box-shadow: inset 0 1px 0 rgba(255,255,255,.06); cursor: not-allowed; }
        .btn-plata:disabled::after { display: none; }
        /* Avatar: foto o inicial en un círculo de plata */
        .avatar-bl {
            position: relative; display: grid; place-items: center; width: 32px; height: 32px; flex-shrink: 0; border-radius: 9999px; overflow: hidden;
            font-size: .8125rem; font-weight: 600; color: #0a0a0a;
            background: linear-gradient(135deg, #ffffff 0%, #c4c4c4 40%, #7a7a7a 60%, #e5e5e5 100%);
            box-shadow: 0 0 0 2px #171717, 0 0 0 3px rgba(255,255,255,.22), 0 0 12px rgba(255,255,255,.18);
        }
        .avatar-bl img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .chip-bl { display: inline-flex; align-items: center; gap: .4rem; padding: .25rem .65rem; border-radius: 9999px; font-size: .6875rem; color: #d4d4d4; background: #1f1f1f; border: 1px solid #2f2f2f; white-space: nowrap; }
        .chip-bl i { width: 7px; height: 7px; border-radius: 9999px; background: #34d399; box-shadow: 0 0 6px #34d399, 0 0 12px #059669; }

        /* Logo pequeño de la cabecera: la misma animación que en el login */
        .logo-stage { position: relative; perspective: 700px; }
        .logo-stage::before {
            content: ""; position: absolute; inset: -30% -40%; border-radius: 50%; pointer-events: none;
            background: radial-gradient(closest-side, rgba(255,255,255,0.13), rgba(255,255,255,0.04) 55%, transparent 100%);
            filter: blur(8px); animation: retroluz 6s ease-in-out infinite;
        }
        .logo-mueve { position: relative; animation: logo-giro 12s ease-in-out infinite; }
        .logo-3d { position: relative; display: block; animation: neon 8s ease-in-out infinite;
            filter: drop-shadow(0 0 1px rgba(255,255,255,0.6)) drop-shadow(0 0 7px rgba(255,255,255,0.28)) drop-shadow(0 0 18px rgba(255,255,255,0.12)); }
        @keyframes neon {
            0%, 100%, 60%, 63.5%, 66% { filter: drop-shadow(0 0 1px rgba(255,255,255,0.6)) drop-shadow(0 0 7px rgba(255,255,255,0.28)) drop-shadow(0 0 18px rgba(255,255,255,0.12)); }
            30%, 45% { filter: drop-shadow(0 0 2px rgba(255,255,255,0.9)) drop-shadow(0 0 12px rgba(255,255,255,0.5)) drop-shadow(0 0 30px rgba(255,255,255,0.25)); }
            62%, 65% { filter: brightness(.55) drop-shadow(0 0 1px rgba(255,255,255,0.3)); }
        }
        .logo-brillo {
            position: absolute; inset: 0; pointer-events: none;
            -webkit-mask: var(--logo) center / contain no-repeat; mask: var(--logo) center / contain no-repeat;
            background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.95) 50%, transparent 60%);
            background-size: 300% 100%; background-repeat: no-repeat; animation: logo-destello 6s ease-in-out infinite;
        }
        @keyframes logo-destello { 0%, 55% { background-position: 150% 0; } 85%, 100% { background-position: -50% 0; } }
        @keyframes logo-giro { 0%, 100% { transform: rotateY(-8deg) rotateX(2deg); } 50% { transform: rotateY(8deg) rotateX(-1deg); } }
        @keyframes retroluz { 0%, 100% { opacity: .75; transform: scale(1); } 50% { opacity: 1; transform: scale(1.08); } }
        .marca { font-size: 10px; letter-spacing: .22em; text-transform: uppercase; }

        /* Título de cada bloque: icono con luz, nombre y una línea que se apaga */
        .bloque-titulo { display: flex; align-items: center; gap: .625rem; margin-bottom: .875rem; }
        .bloque-titulo svg { width: 16px; height: 16px; color: #e5e5e5; filter: drop-shadow(0 0 4px rgba(255,255,255,.55)) drop-shadow(0 0 10px rgba(255,255,255,.2)); }
        .bloque-titulo h2 { font-size: .8125rem; font-weight: 500; letter-spacing: .08em; text-transform: uppercase; }
        .bloque-titulo .cuantos { font-size: .6875rem; color: #737373; }

        /* Tarjetas de servicio, bono y bebida: metal oscuro, se inclinan, brillan al pasar y lanzan una onda al pulsar */
        .item-card {
            position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; gap: .5rem;
            min-height: 112px; padding: .9rem 1rem 1rem; text-align: left; border-radius: .875rem; cursor: pointer; user-select: none;
            background: linear-gradient(160deg, #1f1f1f, #141414 60%, #101010); border: 1px solid #2a2a2a; color: inherit;
        }
        .item-card::after { /* destello que cruza la tarjeta */
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(105deg, transparent 35%, rgba(255,255,255,.08) 50%, transparent 65%); transform: translateX(-100%);
        }
        .item-card:hover {
            transform: perspective(1000px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg)) translateY(-3px);
            border-color: #4a4a4a;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.14), inset 0 -1px 0 rgba(0,0,0,.6),
                        0 0 0 1px rgba(255,255,255,0.08), 0 0 26px rgba(255,255,255,0.12), 0 30px 60px -20px rgba(0,0,0,0.8);
        }
        .item-card:hover::after { transform: translateX(100%); transition: transform .9s ease; }
        .item-card:active { transform: perspective(1000px) scale(.97); }
        .item-card:focus-visible { outline: 2px solid #a3a3a3; outline-offset: 2px; }
        .item-card.en-carrito { border-color: #5a5a5a; background: linear-gradient(160deg, #262626, #171717 60%, #121212); }
        .item-card.latido { animation: latido .35s ease; }
        @keyframes latido { 40% { box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 0 0 1px rgba(255,255,255,.35), 0 0 30px rgba(255,255,255,.3); } }
        .item-cabeza { display: flex; align-items: center; gap: .4rem; min-height: 22px; }
        .item-icono { width: 15px; height: 15px; flex-shrink: 0; color: #5a5a5a; transition: color .25s, filter .25s; }
        .item-card:hover .item-icono, .item-card.en-carrito .item-icono { color: #e5e5e5; filter: drop-shadow(0 0 4px rgba(255,255,255,.55)); }
        .item-etiqueta { font-size: .625rem; letter-spacing: .1em; text-transform: uppercase; color: #8a8a8a; }
        .item-quedan { font-size: .625rem; padding: .1rem .45rem; border-radius: 9999px; white-space: nowrap; color: #fde68a; background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3); }
        .item-nombre { font-size: .875rem; font-weight: 500; line-height: 1.25; color: #e5e5e5; text-shadow: 0 0 8px rgba(255,255,255,.15); transition: color .25s, text-shadow .25s; }
        .item-card:hover .item-nombre { color: #fff; text-shadow: 0 0 1px rgba(255,255,255,.6), 0 0 12px rgba(255,255,255,.35); }
        .item-precio { font-size: 1.25rem; font-weight: 300; letter-spacing: -.01em; }
        /* Cuántas lleva en el carrito: botón de plata con el número */
        .item-cuenta {
            display: none; place-items: center; margin-left: auto; min-width: 22px; height: 22px; padding: 0 .35rem; border-radius: 9999px;
            font-size: .6875rem; font-weight: 700; color: #0a0a0a;
            background: linear-gradient(135deg, #ffffff 0%, #c4c4c4 40%, #8a8a8a 60%, #e5e5e5 100%);
            box-shadow: 0 0 0 1px rgba(255,255,255,.3), 0 0 10px rgba(255,255,255,.35);
        }
        .item-card.en-carrito .item-cuenta { display: grid; }
        .onda {
            position: absolute; width: 16px; height: 16px; margin: -8px 0 0 -8px; border-radius: 9999px; pointer-events: none;
            background: radial-gradient(circle, rgba(255,255,255,.35), rgba(255,255,255,0) 70%); animation: onda .6s ease-out forwards;
        }
        @keyframes onda { to { transform: scale(40); opacity: 0; } }

        /* Carrito: panel flotante, filas que entran y control de cantidad metálico */
        .carrito-fila { display: flex; align-items: center; gap: .5rem; padding: .625rem 0; border-bottom: 1px solid #222; animation: fila-entra .3s cubic-bezier(.2,.7,.2,1); }
        .carrito-fila:last-child { border-bottom: 0; }
        @keyframes fila-entra { from { opacity: 0; transform: translateX(8px); } }
        .carrito-nombre { font-size: .8125rem; color: #e5e5e5; text-shadow: 0 0 8px rgba(255,255,255,.15); }
        .paso { display: grid; place-items: center; width: 24px; height: 24px; border-radius: 9999px; font-size: .875rem; line-height: 1; color: #b5b5b5;
            background: linear-gradient(180deg, #2a2a2a, #161616); border: 1px solid #3a3a3a; box-shadow: inset 0 1px 0 rgba(255,255,255,.1);
            transition: color .2s, border-color .2s, box-shadow .2s, transform .12s; }
        .paso:hover { color: #fff; border-color: #737373; box-shadow: inset 0 1px 0 rgba(255,255,255,.18), 0 0 10px rgba(255,255,255,.25); }
        .paso:active { transform: scale(.9); }
        .paso:focus-visible { outline: 2px solid #8a8a8a; outline-offset: 2px; }
        .carrito-vacio { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .625rem; height: 100%; min-height: 140px; font-size: .75rem; color: #5a5a5a; text-align: center; }
        .carrito-vacio svg { width: 30px; height: 30px; color: #3a3a3a; }
        .total-num { font-size: 2rem; font-weight: 300; letter-spacing: -.02em; }
        .total-num.salta { animation: salta .3s ease; }
        @keyframes salta { 40% { transform: scale(1.06); } }

        /* Fondo: salón de barbería con espejos de camerino */
        #fondo-sala { position: fixed; inset: 0; width: 100%; height: 100%; z-index: 0; pointer-events: none; }
        .capa { position: relative; z-index: 1; }

        /* Botón flotante del carrito en el móvil: plata con el número */
        #carrito-abrir {
            background: linear-gradient(180deg, #ffffff, #c9c9c9 55%, #a8a8a8); border: 1px solid #e5e5e5; color: #0a0a0a;
            box-shadow: inset 0 1px 0 #fff, 0 0 20px rgba(255,255,255,.3), 0 10px 24px rgba(0,0,0,.6);
        }
        #carrito-badge { background: #0a0a0a; color: #fff; box-shadow: 0 0 0 1px rgba(255,255,255,.5), 0 0 8px rgba(52,211,153,.6); }

        /* Carrito móvil */
        @media (max-width: 767px) {
            #carrito-panel { position: fixed; top: .75rem; right: .75rem; bottom: .75rem; width: calc(100% - 1.5rem); max-width: 340px; margin: 0; transform: translateX(calc(100% + 1.5rem)); transition: transform .25s ease; z-index: 60; }
            #carrito-panel.open { transform: translateX(0); }
            #carrito-backdrop { display: none; }
            #carrito-backdrop.open { display: block; }
            body.carrito-abierto #carrito-abrir { display: none; }
        }
        @media (min-width: 768px) { #carrito-cerrar { display: none; } }

        @media (prefers-reduced-motion: reduce) {
            .card-glow::before, .logo-stage::before, .logo-mueve, .logo-3d, .logo-brillo, .onda, .item-card.latido, .carrito-fila, .total-num.salta { animation: none; }
            .item-card, .bl-3d { transition: none; }
            .item-card:hover { transform: none; }
            .item-card::after, .btn-plata::after { display: none; }
        }
    </style>
</head>
<body class="bl-bg font-inter flex flex-col h-screen overflow-hidden text-white">

<?php include __DIR__ . '/TransicionSesion.php'; ?>

<!-- FONDO: salón de barbería, con las bombillas y los espejos animados -->
<canvas id="fondo-sala" aria-hidden="true"></canvas>

<!-- CABECERA: barra flotante con el logo animado, el empleado y su especialidad -->
<header class="capa bl-panel card-glow mx-3 mt-3 h-14 flex items-center justify-between gap-3 px-3 sm:px-4 flex-shrink-0" style="--delay:-2s">
    <div class="flex items-center gap-3 min-w-0">
        <div class="logo-stage" style="--logo: url('public/img/logos/beloyal-logo.svg')">
            <div class="logo-mueve">
                <img src="public/img/logos/beloyal-logo.svg" alt="Be Loyal" class="logo-3d w-10 h-auto select-none" draggable="false">
                <span class="logo-brillo" aria-hidden="true"></span>
            </div>
        </div>
        <p class="marca plata hidden sm:block">Caja</p>
    </div>
    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
        <span class="hidden sm:block"><span class="chip-bl"><i aria-hidden="true"></i><?= htmlspecialchars($nombresEsp[$especialidad] ?? ucfirst($especialidad)) ?></span></span>
        <span class="avatar-bl" aria-hidden="true">
            <?= $inicial ?>
            <?php if (!empty($trabajador['logo'])): ?>
            <img src="public/img/logos/<?= htmlspecialchars($trabajador['logo']) ?>" alt="" onerror="this.remove()">
            <?php endif; ?>
        </span>
        <span class="text-[13px] font-medium text-[#f5f5f5] truncate" style="text-shadow:0 0 10px rgba(255,255,255,.3)"><?= htmlspecialchars($trabajador['nombre']) ?></span>
        <a href="index.php?page=logout" class="seg-btn btn-salir ml-1" title="Cerrar sesión">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            <span class="hidden sm:inline">Cerrar sesión</span>
        </a>
    </div>
</header>

<!-- MAIN -->
<div class="capa flex flex-1 overflow-hidden">

    <!-- SERVICIOS, BONOS Y BEBIDAS -->
    <main class="flex-1 overflow-y-auto px-3 sm:px-5 pt-5 pb-24 md:pb-6">

        <section class="mb-7">
            <div class="bloque-titulo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $ICONO_SERVICIO ?></svg>
                <h2 class="titulo-luz"><?= htmlspecialchars($seccionTitulo) ?></h2>
                <span class="cuantos"><?= count($servicios) ?></span>
                <span class="sep-luz"></span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                <?php foreach ($servicios as $s) echo $tarjeta($s, 'svc', 'servicio', $ICONO_SERVICIO); ?>
            </div>
            <?php if (!$servicios): ?><p class="text-xs text-[#5a5a5a]">No hay servicios disponibles.</p><?php endif; ?>
        </section>

        <?php if ($mostrarBonos && !empty($bonos)): ?>
        <section class="mb-7">
            <div class="bloque-titulo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $ICONO_BONO ?></svg>
                <h2 class="titulo-luz">Bonos</h2>
                <span class="cuantos"><?= count($bonos) ?></span>
                <span class="sep-luz"></span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                <?php foreach ($bonos as $b) echo $tarjeta($b, 'svc', 'servicio', $ICONO_BONO, 'Bono'); ?>
            </div>
        </section>
        <?php endif; ?>

        <section>
            <div class="bloque-titulo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?= $ICONO_BEBIDA ?></svg>
                <h2 class="titulo-luz">Bebidas</h2>
                <span class="cuantos"><?= count($bebidas) ?></span>
                <span class="sep-luz"></span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                <?php foreach ($bebidas as $b) echo $tarjeta($b, 'beb', 'producto', $ICONO_BEBIDA, null, (int)$b['stock']); ?>
            </div>
            <?php if (!$bebidas): ?><p class="text-xs text-[#5a5a5a]">No quedan bebidas disponibles.</p><?php endif; ?>
        </section>

    </main>

    <!-- BACKDROP CARRITO MÓVIL -->
    <div id="carrito-backdrop" class="fixed inset-0 bg-black/60 z-50 md:hidden"></div>

    <!-- CARRITO: panel flotante con borde luminoso -->
    <aside id="carrito-panel" class="bl-panel card-glow bl-3d w-80 m-3 ml-0 flex flex-col flex-shrink-0" style="--delay:-5s" aria-label="Carrito">
        <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid #262626">
            <svg class="w-4 h-4 text-[#e5e5e5]" style="filter:drop-shadow(0 0 4px rgba(255,255,255,.55))" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.4a1.5 1.5 0 0 0 1.5 1.1h8.6a1.5 1.5 0 0 0 1.5-1.1L21 7H6"/></svg>
            <div class="flex-1">
                <p class="text-sm font-medium titulo-luz">Carrito</p>
                <p id="carrito-resumen" class="text-[11px] text-[#737373] mt-0.5">Sin artículos</p>
            </div>
            <button id="carrito-cerrar" aria-label="Cerrar carrito" class="paso md:hidden">×</button>
        </div>
        <div class="flex-1 overflow-y-auto px-5 py-2" id="carrito-items"></div>
        <div class="px-5 pt-4 pb-5" style="border-top:1px solid #262626">
            <div class="flex justify-between items-baseline mb-4">
                <span class="text-xs text-[#8a8a8a] uppercase tracking-[.12em]">Total</span>
                <span class="total-num plata" id="total">€0,00</span>
            </div>
            <button id="btn-cobrar" class="btn-plata mb-2" disabled
                    onclick="abrirPasarela(Object.assign({}, carrito), Object.values(carrito).reduce((a,i) => a + i.precio * i.cantidad, 0))">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                Cobrar
            </button>
            <button id="btn-limpiar" onclick="limpiarCarrito()" class="seg-btn btn-peligro w-full py-2" disabled>Vaciar carrito</button>
        </div>
    </aside>

</div>

<!-- BOTÓN FLOTANTE CARRITO (móvil) -->
<button id="carrito-abrir" aria-label="Abrir carrito"
        class="md:hidden fixed bottom-5 right-5 z-40 w-14 h-14 rounded-full flex items-center justify-center">
    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.4a1.5 1.5 0 0 0 1.5 1.1h8.6a1.5 1.5 0 0 0 1.5-1.1L21 7H6"/></svg>
    <span id="carrito-badge" class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full text-[10px] font-bold flex items-center justify-center hidden">0</span>
</button>

<!-- TOAST -->
<div id="toast" role="status"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 translate-y-20 opacity-0 bg-[#141414] border border-white/10 text-white px-[1.4rem] py-[0.65rem] rounded-full text-[0.82rem] font-medium shadow-[0_8px_32px_rgba(0,0,0,0.6)] z-[99999] pointer-events-none whitespace-nowrap transition-[transform,opacity] duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
</div>

<?php include __DIR__ . '/Pasarelapago.php'; ?>

<script>
// ── Fondo: salón de barbería ────────────────────────────────────────────────
// Suelo de damero y pared con espejos de camerino. La escena fija se pinta una vez
// (y al cambiar el tamaño); en cada fotograma solo se añaden las luces que se mueven.
(function () {
    const lienzo = document.getElementById('fondo-sala');
    if (!lienzo) return;
    const c = lienzo.getContext('2d');
    const gris = v => 'rgb(' + v + ',' + v + ',' + v + ')';
    const quieto = matchMedia('(prefers-reduced-motion: reduce)').matches;
    let escena, velo, bombillas, espejos, focos, dpr, w, h;

    function capa() { const k = document.createElement('canvas'); k.width = lienzo.width; k.height = lienzo.height; const x = k.getContext('2d'); x.setTransform(dpr, 0, 0, dpr, 0, 0); return [k, x]; }

    function medir() {
        dpr = Math.min(window.devicePixelRatio || 1, 2); w = innerWidth; h = innerHeight;
        lienzo.width = Math.round(w * dpr); lienzo.height = Math.round(h * dpr);
        bombillas = []; espejos = []; focos = [];
        let x; [escena, x] = capa();
        x.fillStyle = '#050505'; x.fillRect(0, 0, w, h);

        // cámara: proyecta (x, y, z) con el horizonte algo por encima de la mitad
        const f = Math.min(w, h) * 1.1, hy = h * .42, cam = 1.5, fondo = 9;
        const P = (px, py, pz) => [w / 2 + px * f / pz, hy - (py - cam) * f / pz];
        const suelo = P(0, 0, fondo)[1], zocalo = P(0, .5, fondo)[1];

        // pared del fondo y zócalo con filo de luz
        let g = x.createLinearGradient(0, 0, 0, suelo);
        g.addColorStop(0, '#0b0b0b'); g.addColorStop(1, '#1a1a1a');
        x.fillStyle = g; x.fillRect(0, 0, w, suelo);
        x.fillStyle = '#121212'; x.fillRect(0, zocalo, w, suelo - zocalo);
        x.strokeStyle = 'rgba(255,255,255,.12)'; x.beginPath(); x.moveTo(0, zocalo); x.lineTo(w, zocalo); x.stroke();

        // espejos con bombillas, uno cada 2,6 m
        const n = Math.ceil((w / 2) / (2.6 * f / fondo)) + 1;
        for (let i = -n; i <= n; i++) {
            const cx = i * 2.6, a = P(cx - .8, 2.6, fondo), b = P(cx + .8, 1.05, fondo);
            const x0 = a[0], y0 = a[1], x1 = b[0], y1 = b[1];
            x.fillStyle = '#2a2a2a'; x.fillRect(x0 - 6, y0 - 6, x1 - x0 + 12, y1 - y0 + 12);     // marco de plata
            x.strokeStyle = 'rgba(255,255,255,.25)'; x.strokeRect(x0 - 6, y0 - 6, x1 - x0 + 12, y1 - y0 + 12);
            g = x.createLinearGradient(x0, y0, x1, y1);                                           // cristal con reflejo
            g.addColorStop(0, '#202020'); g.addColorStop(.45, '#151515'); g.addColorStop(.5, '#262626'); g.addColorStop(.56, '#141414'); g.addColorStop(1, '#0e0e0e');
            x.fillStyle = g; x.fillRect(x0, y0, x1 - x0, y1 - y0);
            espejos.push([x0, y0, x1, y1]);
            const r = Math.max(2, (x1 - x0) * .028), pasos = 7;
            for (let k = 0; k <= pasos; k++) bombillas.push([x0 + (x1 - x0) * k / pasos, y0 - 6, r]);
            for (let k = 1; k <= 4; k++) { bombillas.push([x0 - 6, y0 + (y1 - y0) * k / 5, r]); bombillas.push([x1 + 6, y0 + (y1 - y0) * k / 5, r]); }
            const rp = P(cx - 1, .9, fondo), rq = P(cx + 1, .9, fondo);                         // repisa
            x.fillStyle = '#2e2e2e'; x.fillRect(rp[0], rp[1], rq[0] - rp[0], 4);
            x.fillStyle = 'rgba(255,255,255,.3)'; x.fillRect(rp[0], rp[1], rq[0] - rp[0], 1);
            focos.push([(x0 + x1) / 2, suelo + (h - suelo) * .18, x1 - x0]);                   // reflejo en el suelo
        }

        // suelo de damero en perspectiva
        const t = .6;
        for (let z = 1; z < fondo; z += t) {
            for (let px = -14; px < 14; px += t) {
                const a = P(px, 0, z), b = P(px + t, 0, z), d = P(px + t, 0, Math.min(z + t, fondo)), e = P(px, 0, Math.min(z + t, fondo));
                const claro = (Math.round(px / t) + Math.round(z / t)) % 2 === 0, k = Math.min(1, 2.2 / z);
                x.fillStyle = claro ? gris(Math.round(34 + 34 * k)) : gris(Math.round(8 + 6 * k));
                x.beginPath(); x.moveTo(a[0], a[1]); x.lineTo(b[0], b[1]); x.lineTo(d[0], d[1]); x.lineTo(e[0], e[1]); x.closePath(); x.fill();
            }
        }
        g = x.createLinearGradient(0, suelo, 0, suelo + h * .2);
        g.addColorStop(0, 'rgba(255,255,255,.08)'); g.addColorStop(1, 'rgba(255,255,255,0)');
        x.fillStyle = g; x.fillRect(0, suelo, w, h * .2);

        // viñeta y velo para que el contenido se lea bien encima
        [velo, x] = capa();
        g = x.createRadialGradient(w / 2, h / 2, Math.min(w, h) * .25, w / 2, h / 2, Math.max(w, h) * .75);
        g.addColorStop(0, 'rgba(0,0,0,0)'); g.addColorStop(1, 'rgba(0,0,0,.75)');
        x.fillStyle = g; x.fillRect(0, 0, w, h);
        x.fillStyle = 'rgba(0,0,0,.42)'; x.fillRect(0, 0, w, h);
    }

    function pintar(ms) {
        const s = ms / 1000;
        c.setTransform(1, 0, 0, 1, 0, 0); c.drawImage(escena, 0, 0);
        c.setTransform(dpr, 0, 0, dpr, 0, 0);

        // destello que cruza cada espejo, uno detrás de otro
        espejos.forEach(function ([x0, y0, x1, y1], i) {
            const p = ((s / 9 + i * .17) % 1) * 3 - 1;             // de -1 a 2: solo cruza el cristal un rato
            if (p < -.3 || p > 1.3) return;
            const bx = x0 + (x1 - x0) * p, ancho = (x1 - x0) * .35;
            const g = c.createLinearGradient(bx - ancho, y0, bx + ancho, y1);
            g.addColorStop(0, 'rgba(255,255,255,0)'); g.addColorStop(.5, 'rgba(255,255,255,.07)'); g.addColorStop(1, 'rgba(255,255,255,0)');
            c.save(); c.beginPath(); c.rect(x0, y0, x1 - x0, y1 - y0); c.clip();
            c.fillStyle = g; c.fillRect(x0, y0, x1 - x0, y1 - y0); c.restore();
        });

        // bombillas que respiran; de vez en cuando una parpadea como el neón del logo
        const parpadea = Math.floor(s / 5) * 7919 % Math.max(1, bombillas.length), enParpadeo = (s % 5) < .35;
        bombillas.forEach(function ([x, y, r], i) {
            let luz = .55 + .45 * Math.sin(s * 1.1 + i * .7);
            if (enParpadeo && i === parpadea) luz = (s * 20 | 0) % 2 ? .05 : .6;
            const g = c.createRadialGradient(x, y, 0, x, y, r * 6);
            g.addColorStop(0, 'rgba(255,255,255,' + (.08 + .14 * luz).toFixed(3) + ')'); g.addColorStop(1, 'rgba(255,255,255,0)');
            c.fillStyle = g; c.fillRect(x - r * 6, y - r * 6, r * 12, r * 12);
            c.fillStyle = gris(Math.round(110 + 110 * luz)); c.beginPath(); c.arc(x, y, r * .8, 0, Math.PI * 2); c.fill();
        });

        // reflejo de los espejos en el suelo pulido, que sigue el pulso de las luces
        focos.forEach(function ([x, y, ancho], i) {
            const luz = .5 + .5 * Math.sin(s * .6 + i);
            c.save(); c.translate(x, y); c.scale(1, .3);
            const g = c.createRadialGradient(0, 0, 0, 0, 0, ancho);
            g.addColorStop(0, 'rgba(255,255,255,' + (.03 + .04 * luz).toFixed(3) + ')'); g.addColorStop(1, 'rgba(255,255,255,0)');
            c.fillStyle = g; c.fillRect(-ancho, -ancho, ancho * 2, ancho * 2); c.restore();
        });

        c.setTransform(1, 0, 0, 1, 0, 0); c.drawImage(velo, 0, 0);
    }

    // unas 30 imágenes por segundo bastan; se para con la pestaña oculta
    let ultimo = 0, corriendo = false;
    function bucle(ms) {
        if (document.hidden) { corriendo = false; return; }
        if (ms - ultimo > 33) { ultimo = ms; pintar(ms); }
        requestAnimationFrame(bucle);
    }
    function arrancar() { if (!corriendo && !quieto) { corriendo = true; requestAnimationFrame(bucle); } }
    document.addEventListener('visibilitychange', arrancar);
    let pendiente = 0;
    addEventListener('resize', function () { cancelAnimationFrame(pendiente); pendiente = requestAnimationFrame(function () { medir(); pintar(performance.now()); }); });
    medir(); pintar(performance.now());
    arrancar();
})();

// ── Carrito móvil ────────────────────────────────────────────────────────────
(function () {
    const panel    = document.getElementById('carrito-panel');
    const backdrop = document.getElementById('carrito-backdrop');
    const abrir    = document.getElementById('carrito-abrir');
    const cerrar   = document.getElementById('carrito-cerrar');
    if (!panel || !backdrop || !abrir || !cerrar) return;
    backdrop.classList.remove('open');
    function open()  { panel.classList.add('open');    backdrop.classList.add('open');    document.body.classList.add('carrito-abierto'); }
    function close() { panel.classList.remove('open'); backdrop.classList.remove('open'); document.body.classList.remove('carrito-abierto'); }
    abrir.addEventListener('click', open);
    cerrar.addEventListener('click', close);
    backdrop.addEventListener('click', close);
})();

// ── Tarjetas: se inclinan hacia el cursor; el carrito también ───────────────
if (matchMedia('(pointer: fine)').matches && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.querySelectorAll('.bl-3d').forEach(function (d) {
        const fuerza = d.classList.contains('item-card') ? 6 : 1.5;
        d.addEventListener('pointermove', function (e) {
            const r = d.getBoundingClientRect();
            const nx = (e.clientX - r.left) / r.width - 0.5, ny = (e.clientY - r.top) / r.height - 0.5;
            d.style.setProperty('--rx', (-ny * fuerza).toFixed(2) + 'deg');
            d.style.setProperty('--ry', (nx * fuerza).toFixed(2) + 'deg');
        });
        d.addEventListener('pointerleave', function () {
            d.style.removeProperty('--rx');
            d.style.removeProperty('--ry');
        });
    });
}

const carrito = {};
const fmt = n => '€' + n.toFixed(2).replace('.', ',');

// Clic en una tarjeta: onda de luz desde el dedo o el cursor, latido y al carrito
document.querySelectorAll('.item-card').forEach(card => {
    card.addEventListener('pointerdown', e => {
        const r = card.getBoundingClientRect();
        const o = document.createElement('span');
        o.className = 'onda';
        o.style.left = (e.clientX - r.left) + 'px';
        o.style.top  = (e.clientY - r.top) + 'px';
        card.appendChild(o);
        o.addEventListener('animationend', () => o.remove());
        setTimeout(() => o.remove(), 800);
    });
    card.addEventListener('click', () => {
        anadir(
            card.dataset.prefix + '_' + card.dataset.id,
            card.dataset.nombre,
            parseFloat(card.dataset.precio),
            card.dataset.tipo,
            parseInt(card.dataset.id, 10),
            card.dataset.stock !== undefined ? parseInt(card.dataset.stock, 10) : null
        );
        card.classList.remove('latido'); void card.offsetWidth; card.classList.add('latido');
    });
});

function anadir(itemId, nombre, precio, tipo, idReal, stock = null) {
    if (!carrito[itemId]) carrito[itemId] = { nombre, precio: parseFloat(precio) || 0, cantidad: 0, tipo, idReal, stock };
    const item = carrito[itemId];
    // Bebidas: no se puede vender más de lo que queda
    if (item.stock !== null && item.cantidad >= item.stock) {
        mostrarToast('Solo quedan ' + item.stock + ' de ' + nombre, 'err');
        if (!item.cantidad) delete carrito[itemId];
        return;
    }
    item.cantidad++;
    renderizar();
}

function quitar(itemId) {
    if (!carrito[itemId]) return;
    carrito[itemId].cantidad--;
    if (carrito[itemId].cantidad <= 0) delete carrito[itemId];
    renderizar();
}

function limpiarCarrito() {
    Object.keys(carrito).forEach(k => delete carrito[k]);
    renderizar();
}

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, c => ({
        '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'
    })[c]);
}

let totalAnterior = 0;
function renderizar() {
    const items = Object.entries(carrito);
    const el = document.getElementById('carrito-items');
    const badge = document.getElementById('carrito-badge');
    const totalCantidad = Object.values(carrito).reduce((a, i) => a + i.cantidad, 0);
    if (badge) {
        if (totalCantidad > 0) { badge.textContent = totalCantidad; badge.classList.remove('hidden'); }
        else { badge.classList.add('hidden'); }
    }
    document.getElementById('carrito-resumen').textContent =
        totalCantidad ? totalCantidad + (totalCantidad === 1 ? ' artículo' : ' artículos') : 'Sin artículos';
    document.getElementById('btn-cobrar').disabled  = !totalCantidad;
    document.getElementById('btn-limpiar').disabled = !totalCantidad;

    // Número de cada tarjeta en el carrito
    document.querySelectorAll('.item-card').forEach(card => {
        const item = carrito[card.dataset.prefix + '_' + card.dataset.id];
        card.classList.toggle('en-carrito', !!item);
        card.querySelector('.item-cuenta').textContent = item ? item.cantidad : '';
    });

    let total = 0;
    el.innerHTML = '';
    if (!items.length) {
        el.innerHTML = '<div class="carrito-vacio"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.4a1.5 1.5 0 0 0 1.5 1.1h8.6a1.5 1.5 0 0 0 1.5-1.1L21 7H6"/></svg>Toca un servicio o una bebida<br>para añadirlo</div>';
    }
    items.forEach(([id, item]) => {
        const sub = item.precio * item.cantidad;
        total += sub;
        // Construimos por DOM en vez de innerHTML para evitar inyección de HTML
        const fila = document.createElement('div');
        fila.className = 'carrito-fila';
        fila.innerHTML = `
            <div class="flex-1 min-w-0">
                <p class="carrito-nombre truncate"></p>
                <p class="text-[11px] text-[#737373] mt-0.5">${fmt(item.precio)} c/u</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" data-act="quitar" class="paso" aria-label="Quitar uno">−</button>
                <span class="text-xs font-semibold text-white min-w-[16px] text-center">${item.cantidad}</span>
                <button type="button" data-act="anadir" class="paso" aria-label="Añadir uno">+</button>
            </div>
            <span class="text-xs font-medium text-[#e5e5e5] min-w-[52px] text-right">${fmt(sub)}</span>`;
        fila.querySelector('.carrito-nombre').textContent = item.nombre;
        fila.querySelector('[data-act="quitar"]').addEventListener('click', () => quitar(id));
        fila.querySelector('[data-act="anadir"]').addEventListener('click',
            () => anadir(id, item.nombre, item.precio, item.tipo, item.idReal, item.stock));
        el.appendChild(fila);
    });
    const t = document.getElementById('total');
    t.textContent = fmt(total);
    if (total !== totalAnterior) { t.classList.remove('salta'); void t.offsetWidth; t.classList.add('salta'); }
    totalAnterior = total;
}
renderizar();

function mostrarToast(msg, tipo = 'ok') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.remove('translate-y-20', 'opacity-0', 'border-[#34d399]', 'text-[#34d399]', 'border-[#f87171]', 'text-[#f87171]', 'border-white/10', 'text-white');
    t.classList.add('translate-y-0', 'opacity-100');
    if (tipo === 'ok') t.classList.add('border-[#34d399]', 'text-[#34d399]');
    else               t.classList.add('border-[#f87171]', 'text-[#f87171]');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
        t.classList.remove('translate-y-0', 'opacity-100', 'border-[#34d399]', 'text-[#34d399]', 'border-[#f87171]', 'text-[#f87171]');
        t.classList.add('translate-y-20', 'opacity-0', 'border-white/10', 'text-white');
    }, 2800);
}

// Tras vender, las bebidas restan lo vendido; si se agotan, la tarjeta desaparece
function descontarStock() {
    Object.entries(carrito).forEach(([id, item]) => {
        if (item.stock === null) return;
        const card = document.querySelector('.item-card[data-prefix="beb"][data-id="' + item.idReal + '"]');
        if (!card) return;
        const queda = Math.max(0, parseInt(card.dataset.stock, 10) - item.cantidad);
        card.dataset.stock = queda;
        if (!queda) { card.remove(); return; }
        let chip = card.querySelector('.item-quedan');
        if (queda <= <?= $POCO ?>) {
            if (!chip) { chip = document.createElement('span'); chip.className = 'item-quedan'; card.querySelector('.item-cuenta').before(chip); }
            chip.textContent = 'Quedan ' + queda;
        }
    });
}

async function registrarVenta(metodoPago) {
    const items = Object.values(carrito).map(i => ({
        tipo: i.tipo, id: i.idReal, cantidad: i.cantidad, precio: i.precio,
        nombre: i.nombre
    }));
    const total = items.reduce((a, i) => a + i.precio * i.cantidad, 0);
    try {
        const res = await fetch('index.php?page=registrar_venta', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items, total: total.toFixed(2), metodo_pago: metodoPago }),
        });
        const data = await res.json();
        if (data.ok) {
            descontarStock();
            limpiarCarrito();
            mostrarToast('Venta registrada correctamente');
            return data;
        } else {
            return { ok: false, error: data.error || 'Error al registrar la venta' };
        }
    } catch (e) {
        return { ok: false, error: 'Error de conexión' };
    }
}
</script>

</body>
</html>
