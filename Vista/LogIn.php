<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be Loyal – Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: { extend: { fontFamily: { inter: ['Inter', 'sans-serif'] } } }
    }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-inter min-h-screen flex items-center justify-center px-4"
      style="background-color:#1a1a1a;background-image:radial-gradient(ellipse at 10% 20%,rgba(255,255,255,.04) 0%,transparent 50%),radial-gradient(ellipse at 90% 80%,rgba(255,255,255,.03) 0%,transparent 50%),radial-gradient(ellipse at 60% 40%,rgba(200,200,200,.02) 0%,transparent 40%),repeating-linear-gradient(115deg,transparent 0px,transparent 20px,rgba(255,255,255,.015) 20px,rgba(255,255,255,.015) 21px,transparent 21px,transparent 45px,rgba(255,255,255,.008) 45px,rgba(255,255,255,.008) 46px),repeating-linear-gradient(68deg,transparent 0px,transparent 35px,rgba(255,255,255,.01) 35px,rgba(255,255,255,.01) 36px)">

    <div class="bg-black/50 backdrop-blur-sm border border-white/10 rounded-2xl p-10 w-full max-w-sm shadow-2xl">

        <!-- LOGO -->
        <div class="flex flex-col items-center mb-10">
            <img src="public/img/logos/Beloyallogo.png"
                 alt="Be Loyal Barber"
                 class="w-32 h-32 object-contain rounded-full mb-4 ring-2 ring-white/10">
            <p class="text-xs text-zinc-500 tracking-widest uppercase">Accede a tu cuenta</p>
        </div>

        <p class="text-xs text-red-400 mb-4 text-center min-h-[1rem]">
            <?= !empty($mensajeerror) ? htmlspecialchars($mensajeerror) : '' ?>
        </p>

        <form method="POST" action="index.php?page=login_post">
            <?= csrf_field() ?>


                             <div class="mb-5">
                <label class="block text-xs tracking-wide text-zinc-500 mb-2">Email</label>
                <input type="email" name="email" placeholder="nombre@beloyal.com"
                    class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-4 py-3 text-sm font-light text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-white/30 transition">
            
            
    </div>

            <div class="mb-8">





            <label class="block text-xs tracking-wide text-zinc-500 mb-2">Contraseña</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-4 py-3 text-sm font-light text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-white/30 transition">
            </div>

            <button type="submit"
                class="w-full bg-white text-zinc-900 rounded-xl py-3 text-sm font-semibold hover:bg-zinc-100 transition">
                Entrar
            </button>

        </form>

        <div class="mt-8 pt-6 border-t border-white/10">
            <p class="text-xs font-light text-zinc-600 mb-1.5">Usuarios de prueba</p>
            <p class="text-xs font-light text-zinc-500">Admin: samuel@beloyal.com | 123</p>
            <p class="text-xs font-light text-zinc-500">Empleado: luis@beloyal.com | 123</p>
        </div>

    </div>

</body>
</html>
