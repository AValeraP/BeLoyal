<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 – Be Loyal</title>
    <?php include __DIR__ . '/partials/tema-head.php'; ?>
    <style>
        @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp .5s ease both; }
        .fade-up-d1 { animation: fadeUp .5s ease .1s both; }
        .fade-up-d2 { animation: fadeUp .5s ease .2s both; }
        .fade-up-d3 { animation: fadeUp .5s ease .3s both; }

        /* 404 iluminado que "respira" */
        @keyframes neon {
            0%, 100% { text-shadow: 0 0 2px rgba(255,255,255,0.6), 0 0 18px rgba(255,255,255,0.25), 0 0 48px rgba(255,255,255,0.1); }
            50%      { text-shadow: 0 0 3px rgba(255,255,255,0.8), 0 0 26px rgba(255,255,255,0.4), 0 0 70px rgba(255,255,255,0.16); }
            52%      { text-shadow: 0 0 1px rgba(255,255,255,0.3), 0 0 8px rgba(255,255,255,0.1); }
            54%      { text-shadow: 0 0 3px rgba(255,255,255,0.8), 0 0 26px rgba(255,255,255,0.4), 0 0 70px rgba(255,255,255,0.16); }
        }
        .glitch { animation: neon 5s ease-in-out infinite; }
        @media (prefers-reduced-motion: reduce) { .glitch, .fade-up, .fade-up-d1, .fade-up-d2, .fade-up-d3 { animation: none; } }
    </style>
</head>
<body class="bl-body font-inter min-h-screen flex items-center justify-center px-4 text-[#e5e5e5] relative overflow-hidden">

<?php include __DIR__ . '/partials/tema-fondo.php'; ?>

    <!-- Marca esquina -->
    <div class="absolute z-10 top-5 left-1/2 -translate-x-1/2 sm:top-6 sm:left-6 sm:translate-x-0 flex items-center gap-2.5">
        <img src="public/img/logos/beloyal-logo.svg" alt="Be Loyal Barber" class="logo-glow h-10 w-auto select-none" draggable="false">
        <span class="glow-text text-xs font-semibold tracking-widest uppercase text-[#a3a3a3]">Be Loyal</span>
    </div>

    <div class="relative z-10 text-center max-w-md w-full">

        <p class="fade-up text-xs font-medium uppercase tracking-[0.3em] text-[#8a8a8a] mb-6">Error</p>

        <h1 class="fade-up-d1 glitch text-[7rem] sm:text-[10rem] font-light leading-none tracking-tighter text-[#f5f5f5] mb-2">
            404
        </h1>

        <p class="fade-up-d2 text-base sm:text-lg font-light text-[#d4d4d4] mb-2 glow-text">
            Esta página no existe
        </p>
        <p class="fade-up-d2 text-xs sm:text-sm font-light text-[#8a8a8a] mb-10 max-w-xs mx-auto">
            La ruta a la que intentas acceder no está disponible o ha sido movida.
        </p>

        <div class="fade-up-d3 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="index.php?page=logout"
               class="btn-glow px-6 py-3 bg-[#d4d4d4] text-[#0a0a0a] text-sm font-semibold rounded-xl hover:bg-white">
                Ir al login
            </a>
            <button onclick="history.back()"
                    class="px-6 py-3 border border-[#333] text-[#a3a3a3] text-sm font-light rounded-xl hover:text-white hover:border-[#525252] transition">
                Atrás
            </button>
        </div>

    </div>

    <!-- Footer minimalista -->
    <div class="absolute z-10 bottom-5 left-0 right-0 text-center">
        <p class="text-[0.7rem] font-light text-[#525252] tracking-widest uppercase">BeLoyal · TPV</p>
    </div>

<?php include __DIR__ . '/partials/tema-scripts.php'; ?>

</body>
</html>
