<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Be Loyal – Login</title>

  <!-- Tipografía: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Tailwind CSS vía CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] }
        }
      }
    };
  </script>

  <style>
    /* Fondo: degradado de gris (arriba) a negro (abajo) */
    body {
      background-color: #000;
      background-image:
        radial-gradient(ellipse 120% 70% at 50% -10%, #3a3a3a 0%, #1f1f1f 38%, transparent 70%),
        linear-gradient(180deg, #262626 0%, #121212 45%, #050505 80%, #000 100%);
      background-attachment: fixed;
    }

    /* Escena 3D animada (solo CSS, detrás de la tarjeta) */
    .scene {
      position: fixed;
      inset: 0;
      overflow: hidden;
      perspective: 1400px;
      pointer-events: none;
      z-index: 0;
      --g: min(max(110vmin, 85vmax), 1100px); /* diámetro de la esfera principal */
    }

    /* Esferas de alambre: anillos rotados en 3D formando un globo */
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
      border: 1px solid rgba(255, 255, 255, 0.07);
      transform: rotateY(calc(var(--i) * 22.5deg));
    }
    .globe span.lat {
      border-color: rgba(255, 255, 255, 0.05);
      transform: rotateX(90deg) translateZ(var(--z)) scale(var(--s));
    }
    @keyframes globe-spin {
      from { transform: rotateX(-22deg) rotateZ(8deg) rotateY(0deg); }
      to   { transform: rotateX(-22deg) rotateZ(8deg) rotateY(360deg); }
    }

    /* Luces difusas que flotan en profundidad */
    .orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(60px);
      background: radial-gradient(circle, rgba(255,255,255,0.10), transparent 70%);
      animation: orb-float 26s ease-in-out infinite alternate;
    }
    .orb--1 { width: 46vmax; height: 46vmax; top: -18vmax; left: -12vmax; }
    .orb--2 { width: 34vmax; height: 34vmax; bottom: -14vmax; right: -10vmax; animation-duration: 32s; animation-delay: -8s; }
    .orb--3 { width: 22vmax; height: 22vmax; top: 40%; left: 60%; opacity: .6; animation-duration: 20s; animation-delay: -4s; }
    @keyframes orb-float {
      from { transform: translate3d(0, 0, 0) scale(1); }
      to   { transform: translate3d(6vmax, 4vmax, 0) scale(1.15); }
    }

    /* Viñeta para hundir los bordes hacia el negro */
    .scene::after {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,0.75) 100%);
    }

    /* Accesibilidad: sin animación si el usuario lo prefiere */
    @media (prefers-reduced-motion: reduce) {
      .globe, .orb, .logo-3d, .logo-stage::before, .logo-giro, .logo-brillo, .pedestal-tapa::before,
      .card-glow, .card-glow::before, .card-glow::after, .glow-title,
      .login-scale, .reveal, .reveal-group > * { animation: none; }
      .cursor-light, .card-sheen { display: none; }
    }

    /* Bordes luminosos de la tarjeta: destello que recorre el contorno */
    @property --angle {
      syntax: "<angle>";
      initial-value: 0deg;
      inherits: false;
    }
    .card-glow {
      position: relative;
      box-shadow:
        0 0 0 1px rgba(255,255,255,0.04),
        0 0 28px rgba(255,255,255,0.05),
        0 30px 60px -20px rgba(0,0,0,0.8);
      animation: card-breathe 6s ease-in-out infinite;
    }
    .card-glow::before,
    .card-glow::after {
      content: "";
      position: absolute;
      inset: -1px;
      border-radius: inherit;
      pointer-events: none;
      background: conic-gradient(from var(--angle),
        transparent 0deg,
        rgba(255,255,255,0.9) 60deg,
        transparent 120deg,
        transparent 180deg,
        rgba(255,255,255,0.45) 240deg,
        transparent 300deg);
      animation: border-spin 9s linear infinite;
    }
    /* Línea fina del borde (solo el contorno, el interior se recorta) */
    .card-glow::before {
      padding: 1px;
      -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
      -webkit-mask-composite: xor;
              mask-composite: exclude;
    }
    /* Halo difuso hacia fuera, detrás de la tarjeta */
    .card-glow::after {
      inset: -2px;
      z-index: -1;
      filter: blur(14px);
      opacity: .45;
    }
    @keyframes border-spin {
      to { --angle: 360deg; }
    }
    @keyframes card-breathe {
      0%, 100% { box-shadow: 0 0 0 1px rgba(255,255,255,0.04), 0 0 24px rgba(255,255,255,0.04), 0 30px 60px -20px rgba(0,0,0,0.8); }
      50%      { box-shadow: 0 0 0 1px rgba(255,255,255,0.07), 0 0 36px rgba(255,255,255,0.08), 0 30px 60px -20px rgba(0,0,0,0.8); }
    }

    /* Letras iluminadas: el título respira con la tarjeta, el resto con un halo suave */
    .glow-title {
      text-shadow:
        0 0 1px rgba(255,255,255,0.55),
        0 0 10px rgba(255,255,255,0.30),
        0 0 24px rgba(255,255,255,0.12);
      animation: text-breathe 6s ease-in-out infinite;
    }
    .glow-text {
      text-shadow: 0 0 8px rgba(255,255,255,0.22);
    }
    @keyframes text-breathe {
      50% {
        text-shadow:
          0 0 1px rgba(255,255,255,0.7),
          0 0 14px rgba(255,255,255,0.42),
          0 0 32px rgba(255,255,255,0.18);
      }
    }

    /* Escala uniforme de la tarjeta: mantiene proporciones y evita el scroll */
    .login-scale { zoom: .88; }
    @media (max-height: 760px) { .login-scale { zoom: .78; } }
    @media (max-height: 640px) { .login-scale { zoom: .7; } }
    /* Móvil: escala más suave para que el texto siga siendo legible */
    @media (max-width: 640px) { .login-scale { zoom: .96; } }
    @media (max-width: 640px) and (max-height: 720px) { .login-scale { zoom: .83; } }

    /* ---------- Animaciones ---------- */
    /* Entrada: la tarjeta sube y aparece; su contenido, de forma escalonada */
    .login-scale { animation: rise .9s cubic-bezier(.2,.7,.2,1) backwards; }
    .reveal { animation: fade-up .7s cubic-bezier(.2,.7,.2,1) backwards; animation-delay: var(--d, 0s); }
    .reveal-group > * { animation: fade-up .7s cubic-bezier(.2,.7,.2,1) backwards; }
    .reveal-group > :nth-child(1) { animation-delay: .35s; }
    .reveal-group > :nth-child(2) { animation-delay: .43s; }
    .reveal-group > :nth-child(3) { animation-delay: .51s; }
    .reveal-group > :nth-child(4) { animation-delay: .59s; }
    @keyframes rise    { from { opacity: 0; transform: translateY(24px) scale(.98); } }
    @keyframes fade-up { from { opacity: 0; transform: translateY(10px); } }

    /* Luz que sigue al cursor por el fondo */
    .cursor-light {
      position: absolute;
      left: 0; top: 0;
      width: 640px; height: 640px;
      border-radius: 50%;
      background: radial-gradient(closest-side, rgba(255,255,255,0.085), rgba(255,255,255,0.03) 50%, transparent 100%);
      opacity: 0;
      transition: opacity .6s ease;
      will-change: transform;
    }
    .scene-depth { position: absolute; inset: 0; will-change: transform; }

    /* Postes de barbería animados (plata y grafito), dentro de la escena con profundidad */
    .poste-barbero { position: absolute; inset: 0; width: 100%; height: 100%; display: block; }

    /* Reflejo dentro de la tarjeta, colocado donde está el cursor */
    .card-sheen {
      position: absolute;
      inset: 0;
      border-radius: inherit;
      pointer-events: none;
      opacity: var(--sheen, 0);
      transition: opacity .5s ease;
      background: radial-gradient(380px circle at var(--sx, 50%) var(--sy, 0%), rgba(255,255,255,0.055), transparent 65%);
    }

    /* Botón: se eleva y brilla al pasar el cursor */
    .btn-glow:hover {
      transform: translateY(-1px);
      box-shadow: 0 0 18px rgba(255,255,255,0.22), 0 8px 18px -8px rgba(0,0,0,0.8);
    }

    /* Logo 3D retroiluminado */
    .logo-stage {
      position: relative;
      perspective: 700px;
    }
    /* Luz trasera: halo que "ilumina la pared" detrás del logo */
    .logo-stage::before {
      content: "";
      position: absolute;
      inset: -28% -38%;
      border-radius: 50%;
      background: radial-gradient(closest-side, rgba(255,255,255,0.11), rgba(255,255,255,0.04) 55%, transparent 100%);
      filter: blur(14px);
      animation: backlight 6s ease-in-out infinite;
      pointer-events: none;
    }
    /* El balanceo 3D va en un envoltorio para que el logo y su destello se muevan juntos */
    .logo-giro {
      position: relative;
      transform-origin: 50% 50%;
      animation: logo-tilt 12s ease-in-out infinite;
    }
    .logo-3d {
      position: relative;
      display: block;
      /* Neón: resplandor que late y, de vez en cuando, parpadea como un letrero de verdad */
      filter:
        drop-shadow(0 0 1px rgba(255,255,255,0.6))
        drop-shadow(0 0 7px rgba(255,255,255,0.28))
        drop-shadow(0 0 18px rgba(255,255,255,0.12))
        drop-shadow(0 6px 10px rgba(0,0,0,0.55));
      animation: neon 8s ease-in-out infinite;
    }
    @keyframes neon {
      0%, 100%, 60%, 63.5%, 66% {
        filter: drop-shadow(0 0 1px rgba(255,255,255,0.6)) drop-shadow(0 0 7px rgba(255,255,255,0.28))
                drop-shadow(0 0 18px rgba(255,255,255,0.12)) drop-shadow(0 6px 10px rgba(0,0,0,0.55));
      }
      30%, 45% {
        filter: drop-shadow(0 0 2px rgba(255,255,255,0.9)) drop-shadow(0 0 12px rgba(255,255,255,0.5))
                drop-shadow(0 0 30px rgba(255,255,255,0.25)) drop-shadow(0 6px 10px rgba(0,0,0,0.55));
      }
      62%, 65% {
        filter: brightness(.55) drop-shadow(0 0 1px rgba(255,255,255,0.3)) drop-shadow(0 6px 10px rgba(0,0,0,0.55));
      }
    }
    /* Destello que cruza el logo, recortado a su silueta */
    .logo-brillo {
      position: absolute; inset: 0; pointer-events: none;
      -webkit-mask: var(--logo) center / contain no-repeat; mask: var(--logo) center / contain no-repeat;
      background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.95) 50%, transparent 60%);
      background-size: 300% 100%; background-repeat: no-repeat;
      animation: logo-destello 6s ease-in-out infinite;
    }
    @keyframes logo-destello { 0%, 55% { background-position: 150% 0; } 85%, 100% { background-position: -50% 0; } }
    /* Pedestal de plata bajo el logo: disco metálico con una luz que recorre su filo */
    .pedestal { position: absolute; left: 50%; top: 100%; width: 120%; height: 26px; margin: 4px 0 0 -60%; pointer-events: none; }
    .pedestal-luz { position: absolute; left: 10%; right: 10%; top: 24px; height: 18px; border-radius: 50%;
      background: radial-gradient(closest-side, rgba(255,255,255,.25), transparent); filter: blur(4px); }
    .pedestal-lado { position: absolute; left: 0; right: 0; top: 9px; height: 12px; border-radius: 0 0 50% 50% / 0 0 8px 8px;
      background: linear-gradient(90deg, #1a1a1a, #9a9a9a 30%, #e5e5e5 45%, #6b6b6b 70%, #141414); }
    .pedestal-tapa { position: absolute; left: 0; right: 0; top: 0; height: 18px; border-radius: 50%;
      background: radial-gradient(ellipse at 40% 35%, #4a4a4a, #222 60%, #161616);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 0 0 1px rgba(255,255,255,.18); }
    .pedestal-tapa::after { content: ""; position: absolute; inset: 3px 10%; border-radius: 50%; border: 1px solid rgba(255,255,255,.12); }
    @property --filo { syntax: "<angle>"; initial-value: 0deg; inherits: false; }
    .pedestal-tapa::before {
      content: ""; position: absolute; inset: 0; border-radius: 50%; pointer-events: none;
      background: conic-gradient(from var(--filo), transparent 0deg, rgba(255,255,255,.55) 30deg, transparent 70deg);
      -webkit-mask: radial-gradient(closest-side, transparent 88%, #000 90%); mask: radial-gradient(closest-side, transparent 88%, #000 90%);
      animation: filo-luz 5s linear infinite;
    }
    @keyframes filo-luz { to { --filo: 360deg; } }
    @keyframes logo-tilt {
      0%, 100% { transform: rotateY(-5deg) rotateX(2deg); }
      50%      { transform: rotateY(5deg)  rotateX(-1deg); }
    }
    @keyframes backlight {
      0%, 100% { opacity: .75; transform: scale(1); }
      50%      { opacity: 1;   transform: scale(1.06); }
    }

    /* Checkbox monocromo: marca negra sobre gris claro al activarse */
    .check-bn:checked {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none' stroke='%230a0a0a' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3.5 8.5l3 3 6-6.5'/%3E%3C/svg%3E");
      background-size: 100% 100%;
    }
  </style>
</head>

<body class="min-h-screen font-sans antialiased text-[#e5e5e5] flex items-center justify-center px-4 py-10 sm:px-6 [@media(max-height:760px)]:py-5">

  <!-- Fondo 3D animado (decorativo) -->
  <div class="scene" aria-hidden="true">
    <div class="orb orb--1"></div>
    <div class="orb orb--2"></div>
    <div class="orb orb--3"></div>

    <!-- Luz que sigue al cursor -->
    <div id="cursor-light" class="cursor-light"></div>

    <!-- Capa con profundidad: se desplaza con el cursor -->
    <div id="scene-depth" class="scene-depth">
    <div class="globe globe--main">
      <span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span>
      <span style="--i:4"></span><span style="--i:5"></span><span style="--i:6"></span><span style="--i:7"></span>
      <span class="lat" style="--z:0;--s:1"></span>
      <span class="lat" style="--z:calc(var(--g) * .25);--s:.866"></span>
      <span class="lat" style="--z:calc(var(--g) * -.25);--s:.866"></span>
      <span class="lat" style="--z:calc(var(--g) * .4);--s:.6"></span>
      <span class="lat" style="--z:calc(var(--g) * -.4);--s:.6"></span>
    </div>

    <div class="globe globe--small">
      <span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span>
      <span style="--i:4"></span><span style="--i:5"></span><span style="--i:6"></span><span style="--i:7"></span>
      <span class="lat" style="--z:0;--s:1"></span>
    </div>

    <!-- Postes de barbería a los dos lados: las franjas suben en espiral -->
    <canvas id="poste-barbero" class="poste-barbero"></canvas>
    </div>
  </div>

  <!-- Contenedor principal -->
  <main class="login-scale relative z-10 w-full max-w-[420px]">

    <!-- Tarjeta de login -->
    <section
      class="card-glow bg-[#171717] border border-[#2a2a2a] rounded-xl px-6 py-10 sm:px-10 sm:py-12"
      aria-labelledby="login-title">

      <!-- Reflejo de luz que sigue al cursor -->
      <div class="card-sheen" aria-hidden="true"></div>

      <!-- Logo 3D retroiluminado (cambia la ruta en src) -->
      <div class="reveal flex justify-center mb-9 pt-2" style="--d:.15s">
        <div class="logo-stage" style="--logo: url('public/img/logos/beloyal-logo.svg')">
          <div class="logo-giro">
            <img
              src="public/img/logos/beloyal-logo.svg"
              alt="Be Loyal Barber"
              class="logo-3d w-[135px] sm:w-[155px] h-auto select-none"
              draggable="false">
            <span class="logo-brillo" aria-hidden="true"></span>
          </div>
          <div class="pedestal" aria-hidden="true"><span class="pedestal-luz"></span><span class="pedestal-lado"></span><span class="pedestal-tapa"></span></div>
        </div>
      </div>

      <!-- Encabezado -->
      <header class="reveal text-center mb-8" style="--d:.25s">
        <h1 id="login-title" class="glow-title text-2xl font-medium tracking-tight text-[#f5f5f5]">
          Iniciar sesión
        </h1>
        <p class="glow-text mt-2 text-sm font-light text-[#b5b5b5]">
          Accede a tu punto de venta
        </p>
      </header>

      <!-- Mensaje de error enviado desde PHP ($error) -->
      <?php if (!empty($mensajeerror)): ?>
      <div
        role="alert"
        class="mb-6 flex items-start gap-3 rounded-lg border border-[#404040] bg-[#1f1f1f] px-4 py-3 text-sm text-[#e5e5e5]">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#a3a3a3]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span><?= htmlspecialchars($mensajeerror, ENT_QUOTES, 'UTF-8') ?></span>
      </div>
      <?php endif; ?>

      <!-- Formulario -->
      <form method="POST" action="index.php?page=login_post" class="reveal-group space-y-5" novalidate>
        <?= csrf_field() ?>

        <!-- Usuario / email -->
        <div>
          <label for="email" class="glow-text block text-xs font-medium uppercase tracking-[0.08em] text-[#b5b5b5] mb-2">
            Email
          </label>
          <input
            id="email"
            name="email"
            type="email"
            autocomplete="username"
            required
            placeholder="nombre@beloyal.com"
            value="<?= htmlspecialchars($emailRecordado ?? '', ENT_QUOTES, 'UTF-8') ?>"
            class="block w-full h-12 rounded-lg bg-[#0f0f0f] border border-[#333] px-4 text-[15px] text-[#e5e5e5] placeholder-[#737373]
                   transition-colors duration-200 outline-none
                   focus:border-[#737373] focus-visible:ring-2 focus-visible:ring-[#737373]/40">
        </div>

        <!-- Contraseña con mostrar/ocultar -->
        <div>
          <label for="password" class="glow-text block text-xs font-medium uppercase tracking-[0.08em] text-[#b5b5b5] mb-2">
            Contraseña
          </label>
          <div class="relative">
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              placeholder="••••••••"
              class="block w-full h-12 rounded-lg bg-[#0f0f0f] border border-[#333] pl-4 pr-12 text-[15px] text-[#e5e5e5] placeholder-[#737373]
                     transition-colors duration-200 outline-none
                     focus:border-[#737373] focus-visible:ring-2 focus-visible:ring-[#737373]/40">
            <button
              type="button"
              id="toggle-password"
              aria-label="Mostrar contraseña"
              aria-controls="password"
              aria-pressed="false"
              class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-lg text-[#a3a3a3]
                     transition-colors duration-200 hover:text-[#e5e5e5]
                     outline-none focus-visible:ring-2 focus-visible:ring-[#a3a3a3]">
              <!-- Icono: ojo abierto -->
              <svg id="icon-show" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <!-- Icono: ojo tachado -->
              <svg id="icon-hide" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-6.5 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94"></path>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c6.5 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19"></path>
                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                <line x1="2" y1="2" x2="22" y2="22"></line>
              </svg>
            </button>
          </div>
        </div>

        <!-- Recordarme + recuperar contraseña -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
          <label for="recordarme" class="glow-text inline-flex cursor-pointer items-center gap-2.5 text-sm text-[#b5b5b5] select-none">
            <input
              id="recordarme"
              name="recordarme"
              type="checkbox"
              value="1"
              <?= !empty($emailRecordado) ? 'checked' : '' ?>
              class="check-bn h-[18px] w-[18px] shrink-0 cursor-pointer appearance-none rounded border border-[#525252] bg-[#0f0f0f]
                     transition-colors duration-200 hover:border-[#737373] checked:border-[#d4d4d4] checked:bg-[#d4d4d4]
                     outline-none focus-visible:ring-2 focus-visible:ring-[#a3a3a3] focus-visible:ring-offset-2 focus-visible:ring-offset-[#171717]">
            Recordarme
          </label>
          <!-- No hay recuperación automática: las contraseñas las gestiona el administrador -->
          <span
            tabindex="0"
            title="Pide al administrador que restablezca tu contraseña"
            class="glow-text cursor-help text-sm text-[#b5b5b5] underline-offset-4 transition-colors duration-200 hover:text-[#e5e5e5] hover:underline
                   rounded outline-none focus-visible:ring-2 focus-visible:ring-[#a3a3a3] focus-visible:ring-offset-2 focus-visible:ring-offset-[#171717]">
            ¿Olvidaste tu contraseña?
          </span>
        </div>

        <!-- Botón principal -->
        <button
          type="submit"
          class="mt-2 h-12 w-full rounded-lg bg-[#d4d4d4] text-[15px] font-medium tracking-wide text-[#0a0a0a]
                 btn-glow transition-all duration-300 hover:bg-white active:bg-[#bfbfbf]
                 outline-none focus-visible:ring-2 focus-visible:ring-[#e5e5e5] focus-visible:ring-offset-2 focus-visible:ring-offset-[#171717]">
          Entrar
        </button>
      </form>
    </section>

    <!-- Pie discreto -->
    <p class="glow-text mt-8 text-center text-xs tracking-wide text-[#8a8a8a]">
      &copy; BeLoyal · Sistema de punto de venta
    </p>

    <!-- Usuarios de prueba (entorno de desarrollo) -->
    <p class="mt-2 text-center text-[11px] font-light text-[#737373]">
      Admin: samuel@beloyal.com | 123 &nbsp;·&nbsp; Empleado: luis@beloyal.com | 123
    </p>
  </main>

  <!-- JS: mostrar / ocultar contraseña -->
  <script>
    (function () {
      const input  = document.getElementById('password');
      const toggle = document.getElementById('toggle-password');
      const show   = document.getElementById('icon-show');
      const hide   = document.getElementById('icon-hide');

      toggle.addEventListener('click', function () {
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        show.classList.toggle('hidden', visible);
        hide.classList.toggle('hidden', !visible);
        toggle.setAttribute('aria-pressed', String(visible));
        toggle.setAttribute('aria-label', visible ? 'Ocultar contraseña' : 'Mostrar contraseña');
      });
    })();
  </script>

  <!-- JS: postes de barbería animados, uno a cada lado de la tarjeta -->
  <script>
    (function () {
      const lienzo = document.getElementById('poste-barbero');
      const c = lienzo.getContext('2d');
      const quieto = matchMedia('(prefers-reduced-motion: reduce)').matches;
      let w = 0, h = 0, postes = [];

      function medir() {
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        w = innerWidth; h = innerHeight;
        lienzo.width = Math.round(w * dpr); lienzo.height = Math.round(h * dpr);
        c.setTransform(dpr, 0, 0, dpr, 0, 0);
        const movil = w < 640;
        // En el ordenador van a los lados de la tarjeta; en el móvil asoman por los bordes y más tenues
        const r = movil ? Math.min(w, h) * .2 : Math.max(40, Math.min(110, Math.min(w, h) * .11));
        const base = { r: r, arriba: h * .14, abajo: h * .86, alfa: movil ? .45 : 1 };
        postes = [
          Object.assign({ cx: movil ? r * .35 : w * .2, espejo: true }, base),    // izquierda
          Object.assign({ cx: movil ? w - r * .35 : w * .8, espejo: false }, base) // derecha
        ];
      }

      function metal(x1, x2) {
        const t = c.createLinearGradient(x1, 0, x2, 0);
        t.addColorStop(0, '#2a2a2a'); t.addColorStop(.35, '#f5f5f5'); t.addColorStop(.6, '#8a8a8a'); t.addColorStop(1, '#1a1a1a');
        return t;
      }

      // espejo: el de la izquierda es el reflejo del de la derecha (franjas y brillo hacia la tarjeta)
      function dibujarPoste(p, t) {
        const { r, cx, arriba, abajo, alfa, espejo } = p, paso = r * 2.6, lado = espejo ? -1 : 1;
        c.save();
        c.globalAlpha = alfa;

        // halo de luz detrás y reflejo en el suelo
        let g = c.createRadialGradient(cx, (arriba + abajo) / 2, 0, cx, (arriba + abajo) / 2, r * 4);
        g.addColorStop(0, 'rgba(255,255,255,.08)'); g.addColorStop(1, 'rgba(255,255,255,0)');
        c.fillStyle = g; c.fillRect(cx - r * 4, arriba - r, r * 8, abajo - arriba + r * 2);
        g = c.createLinearGradient(0, abajo + r * .4, 0, abajo + r * 2.4);
        g.addColorStop(0, 'rgba(255,255,255,.07)'); g.addColorStop(1, 'rgba(255,255,255,0)');
        c.fillStyle = g; c.fillRect(cx - r * 1.2, abajo + r * .4, r * 2.4, r * 2);

        // franjas en espiral: el desplazamiento con el tiempo las hace subir
        const avance = (t * paso * .35) % paso;
        c.fillStyle = '#2a2a2a'; c.fillRect(cx - r, arriba, r * 2, abajo - arriba);
        c.fillStyle = '#d4d4d4';
        for (let px = -r; px < r; px += 1) {
          const fase = lado * Math.asin(Math.max(-1, Math.min(1, px / r))) / (Math.PI * 2) * paso - avance;
          for (let y = arriba - paso * 2; y < abajo + paso; y += paso) {
            const y1 = Math.max(arriba, y + fase), y2 = Math.min(abajo, y + fase + paso / 2);
            if (y2 > y1) c.fillRect(cx + px, y1, 1.5, y2 - y1);
          }
        }

        // volumen del cilindro y brillo del cristal
        g = c.createLinearGradient(cx - r * lado, 0, cx + r * lado, 0);
        g.addColorStop(0, 'rgba(0,0,0,.8)'); g.addColorStop(.25, 'rgba(0,0,0,.15)'); g.addColorStop(.38, 'rgba(255,255,255,.35)');
        g.addColorStop(.45, 'rgba(255,255,255,0)'); g.addColorStop(.8, 'rgba(0,0,0,.45)'); g.addColorStop(1, 'rgba(0,0,0,.9)');
        c.fillStyle = g; c.fillRect(cx - r, arriba, r * 2, abajo - arriba);

        // destello que sube por el cristal cada pocos segundos
        const ciclo = (t % 5) / 5, yb = abajo - (abajo - arriba + r * 2) * ciclo, xb = cx - r * .25 * lado;
        g = c.createRadialGradient(xb, yb, 0, xb, yb, r * 1.2);
        g.addColorStop(0, 'rgba(255,255,255,.35)'); g.addColorStop(1, 'rgba(255,255,255,0)');
        c.fillStyle = g; c.fillRect(cx - r, Math.max(arriba, yb - r * 1.2), r * 2, Math.min(abajo, yb + r * 1.2) - Math.max(arriba, yb - r * 1.2));

        // remates metálicos: cúpula arriba y base abajo
        c.fillStyle = espejo ? metal(cx + r * 1.2, cx - r * 1.2) : metal(cx - r * 1.2, cx + r * 1.2);
        c.fillRect(cx - r * 1.2, arriba - r * .3, r * 2.4, r * .3);
        c.beginPath(); c.ellipse(cx, arriba - r * .3, r * 1.2, r * .7, 0, Math.PI, 0); c.fill();
        c.fillRect(cx - r * 1.2, abajo, r * 2.4, r * .4);
        c.beginPath(); c.ellipse(cx, abajo + r * .4, r * 1.2, r * .2, 0, 0, Math.PI); c.fill();
        c.strokeStyle = 'rgba(255,255,255,.5)'; c.lineWidth = 1;
        c.beginPath(); c.moveTo(cx - r * 1.2, arriba); c.lineTo(cx + r * 1.2, arriba); c.moveTo(cx - r * 1.2, abajo); c.lineTo(cx + r * 1.2, abajo); c.stroke();
        c.restore();
      }

      function dibujar(t) {
        c.clearRect(0, 0, w, h);
        postes.forEach(function (p) { dibujarPoste(p, t); });
      }

      medir();
      addEventListener('resize', function () { medir(); if (quieto) dibujar(0); });
      if (quieto) { dibujar(0); return; }
      (function bucle(ms) { dibujar((ms || 0) / 1000); requestAnimationFrame(bucle); })();
    })();
  </script>

  <!-- JS: animación con el cursor (luz, inclinación 3D de la tarjeta y profundidad del fondo) -->
  <script>
    (function () {
      // Solo con ratón y si el usuario no ha pedido reducir el movimiento
      if (!matchMedia('(pointer: fine)').matches || matchMedia('(prefers-reduced-motion: reduce)').matches) return;

      const light = document.getElementById('cursor-light');
      const depth = document.getElementById('scene-depth');
      const main  = document.querySelector('main');
      const card  = document.querySelector('.card-glow');

      let tx = innerWidth / 2, ty = innerHeight / 2, x = tx, y = ty;

      addEventListener('pointermove', function (e) {
        tx = e.clientX; ty = e.clientY;
        light.style.opacity = '1';
        card.style.setProperty('--sheen', '1');
      });
      document.documentElement.addEventListener('pointerleave', function () {
        light.style.opacity = '0';
        card.style.setProperty('--sheen', '0');
      });

      // Movimiento suavizado en cada fotograma
      (function frame() {
        x += (tx - x) * 0.1;
        y += (ty - y) * 0.1;
        const nx = x / innerWidth - 0.5, ny = y / innerHeight - 0.5;

        light.style.transform = 'translate3d(' + (x - 320) + 'px,' + (y - 320) + 'px,0)';
        depth.style.transform = 'translate3d(' + (-nx * 36).toFixed(1) + 'px,' + (-ny * 36).toFixed(1) + 'px,0)';
        main.style.transform  = 'perspective(1400px) rotateX(' + (-ny * 3).toFixed(2) + 'deg) rotateY(' + (nx * 4).toFixed(2) + 'deg)';

        const r = card.getBoundingClientRect();
        card.style.setProperty('--sx', ((x - r.left) / r.width * 100).toFixed(1) + '%');
        card.style.setProperty('--sy', ((y - r.top) / r.height * 100).toFixed(1) + '%');

        requestAnimationFrame(frame);
      })();
    })();
  </script>
</body>
</html>
