<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 – Be Loyal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: { extend: { fontFamily: { inter: ['Inter', 'sans-serif'], dm: ["'DM Sans'", 'Arial', 'sans-serif'] } } }
    }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp .5s ease both; }
        .fade-up-d1 { animation: fadeUp .5s ease .1s both; }
        .fade-up-d2 { animation: fadeUp .5s ease .2s both; }
        .fade-up-d3 { animation: fadeUp .5s ease .3s both; }

        /* Glitch sutil sobre el 404 */
        @keyframes glitch {
            0%, 100% { text-shadow: none; }
            20%      { text-shadow: 2px 0 rgba(248,113,113,0.35), -2px 0 rgba(110,231,183,0.25); }
            40%      { text-shadow: -1px 0 rgba(110,231,183,0.25), 1px 0 rgba(248,113,113,0.35); }
            60%      { text-shadow: 1px 0 rgba(248,113,113,0.25); }
        }
        .glitch { animation: glitch 4.5s infinite; }
    </style>
</head>
<body class="font-inter min-h-screen flex items-center justify-center px-4 text-white relative overflow-hidden"
      style="background-color:#1a1a1a;background-image:radial-gradient(ellipse at 10% 20%,rgba(255,255,255,.04) 0%,transparent 50%),radial-gradient(ellipse at 90% 80%,rgba(255,255,255,.03) 0%,transparent 50%),radial-gradient(ellipse at 60% 40%,rgba(200,200,200,.02) 0%,transparent 40%),repeating-linear-gradient(115deg,transparent 0px,transparent 20px,rgba(255,255,255,.015) 20px,rgba(255,255,255,.015) 21px,transparent 21px,transparent 45px,rgba(255,255,255,.008) 45px,rgba(255,255,255,.008) 46px),repeating-linear-gradient(68deg,transparent 0px,transparent 35px,rgba(255,255,255,.01) 35px,rgba(255,255,255,.01) 36px)">

    <!-- Marca esquina -->
    <div class="absolute top-5 left-1/2 -translate-x-1/2 sm:top-6 sm:left-6 sm:translate-x-0 text-xs font-semibold tracking-widest uppercase text-zinc-500">
        Be Loyal
    </div>

    <div class="text-center max-w-md w-full">

        <p class="fade-up text-xs font-medium uppercase tracking-[0.3em] text-zinc-500 mb-6">Error</p>

        <h1 class="fade-up-d1 glitch text-[7rem] sm:text-[10rem] font-light leading-none tracking-tighter text-white mb-2">
            404
        </h1>

        <p class="fade-up-d2 text-base sm:text-lg font-light text-zinc-300 mb-2">
            Esta página no existe
        </p>
        <p class="fade-up-d2 text-xs sm:text-sm font-light text-zinc-500 mb-10 max-w-xs mx-auto">
            La ruta a la que intentas acceder no está disponible o ha sido movida.
        </p>

        <div class="fade-up-d3 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="index.php?page=logout"
               class="px-6 py-3 bg-white text-zinc-900 text-sm font-semibold rounded-xl hover:bg-zinc-100 transition">
                Ir al login
            </a>
            <button onclick="history.back()"
                    class="px-6 py-3 border border-white/10 text-zinc-400 text-sm font-light rounded-xl hover:text-white hover:border-white/30 transition">
                Atrás
            </button>
        </div>

    </div>

    <!-- Footer minimalista -->
    <div class="absolute bottom-5 left-0 right-0 text-center">
        <p class="text-[0.7rem] font-light text-zinc-700 tracking-widest uppercase">BeLoyal · TPV</p>
    </div>

</body>
</html>
