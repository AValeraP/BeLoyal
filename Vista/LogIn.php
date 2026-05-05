<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be Loyal – Login</title>
<!--Import de tailwind-->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 bg-gray-400">
    <div class="bg-zinc-900 border border-zinc-800  p-10 w-full max-w-sm">

        <div class="mb-10">
            <p class="text-lg  tracking-widest text-zinc-100 mb-1">Be Loyal</p>
            <p class="text-sm  text-zinc-500">Accede a tu cuenta</p>
        </div>

        <?php if (!empty($mensajeerror)): ?>
            <p class="text-sm text-red-400 mb-6"><?= htmlspecialchars($mensajeerror) ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php?page=login_post">

            <div class="mb-5">
                <label class="block text-xs tracking-wide text-zinc-500 mb-2">Email</label>
                <input type="email" name="email" placeholder="nombredeempleado@beloyal.com"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2.5 text-sm font-light text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition">
            </div>

            <div class="mb-8">
                <label class="block text-xs tracking-wide text-zinc-500 mb-2">Contraseña</label>
                <input type="password" name="password" placeholder="••••••••"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-2.5 text-sm font-light text-zinc-100 placeholder-zinc-600 focus:outline-none focus:border-zinc-500 transition">
            </div>

            <button type="submit"
                class="w-full bg-zinc-100 text-zinc-900 rounded-lg py-2.5 text-sm tracking-wide font-400 hover:bg-white transition">
                Entrar
            </button>

        </form>

        <div class="mt-8 pt-6 border-t border-zinc-800">
            <p class="text-xs font-light text-zinc-600 mb-1.5">Usuarios de prueba</p>
            <p class="text-xs font-light text-zinc-500">Admin: alejandro@peluqueria.com | 123</p>
            <p class="text-xs font-light text-zinc-500">Empleado: luis@beloyal.com | 123</p>
        </div>

    </div>
</body>

</html>