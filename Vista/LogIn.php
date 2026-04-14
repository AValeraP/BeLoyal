<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login – TPV Peluquería</title>
</head>
<body>

    <h1>Iniciar sesión</h1>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

    <form method="POST" action="index.php?page=login_post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Entrar</button>

        <p>Usuarios de prueba:<br>
            Admin: alejandro@peluqueria.com | 123 <br>
            Empleado: empleado1@peluqueria.com | 123
        </p>
    </form>

</body>
</html>