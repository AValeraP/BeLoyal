<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empleado – TPV Peluquería</title>
</head>
<body>

<h1>Bienvenido <?= htmlspecialchars($usuario['nombre']) ?></h1>
<p><a href="index.php?page=logout">Cerrar sesión</a></p>

<hr>

<?php
// ── Carga de JSONs ────────────────────────────────────────────────────────────
$rutaBase = __DIR__ . '/../Data/';

$bebidas   = json_decode(file_get_contents($rutaBase . 'bebidas.json'), true) ?? [];
$bonosData = json_decode(file_get_contents($rutaBase . 'bonos.json'), true);
$bonos     = $bonosData['bonos'] ?? [];
$productos = json_decode(file_get_contents($rutaBase . 'productos.json'), true) ?? [];
$servicios = json_decode(file_get_contents($rutaBase . 'servicios.json'), true) ?? [];
?>

<!-- ── SERVICIOS ─────────────────────────────────────────────────────────── -->
<h2>Servicios</h2>

<h3>Barbería</h3>
<table border="1">
    <tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Añadir</th></tr>
    <?php foreach ($servicios['barberia'] ?? [] as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['nombre']) ?></td>
        <td><?= htmlspecialchars($item['descripcion']) ?></td>
        <td><?= $item['precio'] ?> €</td>
        <td><button onclick="añadirAlCarrito(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>', <?= $item['precio'] ?>)">Añadir</button></td>
    </tr>
    <?php endforeach; ?>
</table>

<h3>Uñas</h3>
<table border="1">
    <tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Añadir</th></tr>
    <?php foreach ($servicios['unas'] ?? [] as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['nombre']) ?></td>
        <td><?= htmlspecialchars($item['descripcion']) ?></td>
        <td><?= $item['precio'] ?> €</td>
        <td><button onclick="añadirAlCarrito(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>', <?= $item['precio'] ?>)">Añadir</button></td>
    </tr>
    <?php endforeach; ?>
</table>

<h3>Peluquería</h3>
<table border="1">
    <tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Añadir</th></tr>
    <?php foreach ($servicios['peluqueria'] ?? [] as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['nombre']) ?></td>
        <td><?= htmlspecialchars($item['descripcion']) ?></td>
        <td><?= $item['precio'] ?> €</td>
        <td><button onclick="añadirAlCarrito(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>', <?= $item['precio'] ?>)">Añadir</button></td>
    </tr>
    <?php endforeach; ?>
</table>

<hr>

<!-- ── BEBIDAS ───────────────────────────────────────────────────────────── -->
<h2>Bebidas</h2>
<table border="1">
    <tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Añadir</th></tr>
    <?php foreach ($bebidas as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['nombre']) ?></td>
        <td><?= htmlspecialchars($item['descripcion']) ?></td>
        <td><?= htmlspecialchars($item['precio']) ?></td>
        <td><button onclick="añadirAlCarrito('beb_<?= $item['id'] ?>', '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['precio'], ENT_QUOTES) ?>')">Añadir</button></td>
    </tr>
    <?php endforeach; ?>
</table>

<hr>

<!-- ── BONOS ─────────────────────────────────────────────────────────────── -->
<h2>Bonos</h2>
<table border="1">
    <tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Añadir</th></tr>
    <?php foreach ($bonos as $i => $item): ?>
    <tr>
        <td><?= htmlspecialchars($item['nombre']) ?></td>
        <td><?= htmlspecialchars($item['descripcion']) ?></td>
        <td><?= $item['precio'] !== '' ? htmlspecialchars($item['precio']) : '—' ?></td>
        <td><button onclick="añadirAlCarrito('bono_<?= $i ?>', '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>', 0)">Añadir</button></td>
    </tr>
    <?php endforeach; ?>
</table>

<hr>

<!-- ── PRODUCTOS ─────────────────────────────────────────────────────────── -->
<h2>Productos</h2>
<?php if (empty($productos)): ?>
    <p>No hay productos disponibles.</p>
<?php else: ?>
    <table border="1">
        <tr><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Añadir</th></tr>
        <?php foreach ($productos as $i => $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['nombre']) ?></td>
            <td><?= htmlspecialchars($item['descripcion']) ?></td>
            <td><?= htmlspecialchars($item['precio']) ?></td>
            <td><button onclick="añadirAlCarrito('prod_<?= $i ?>', '<?= htmlspecialchars($item['nombre'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['precio'], ENT_QUOTES) ?>')">Añadir</button></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<hr>

<!-- ── CARRITO ───────────────────────────────────────────────────────────── -->
<h2>Carrito</h2>
<table border="1" id="tablaCarrito">
    <tr><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>Eliminar</th></tr>
</table>
<p>Total: <strong id="total">0.00</strong> €</p>
<button onclick="vaciarCarrito()">Vaciar carrito</button>
<button onclick="finalizarVenta()">Finalizar venta</button>

<script>
    let carrito = [];

    function añadirAlCarrito(id, nombre, precio) {
        precio = parseFloat(precio) || 0;
        const existente = carrito.find(i => i.id === id);
        if (existente) {
            existente.cantidad++;
        } else {
            carrito.push({ id, nombre, precio, cantidad: 1 });
        }
        renderCarrito();
    }

    function eliminarDelCarrito(index) {
        carrito.splice(index, 1);
        renderCarrito();
    }

    function vaciarCarrito() {
        carrito = [];
        renderCarrito();
    }

    function renderCarrito() {
        const tabla = document.getElementById('tablaCarrito');
        while (tabla.rows.length > 1) tabla.deleteRow(1);

        let total = 0;

        carrito.forEach((item, i) => {
            const subtotal = item.precio * item.cantidad;
            total += subtotal;

            const fila = tabla.insertRow();
            fila.insertCell().textContent = item.nombre;
            fila.insertCell().textContent = item.precio.toFixed(2) + ' €';
            fila.insertCell().textContent = item.cantidad;
            fila.insertCell().textContent = subtotal.toFixed(2) + ' €';

            const btnEliminar = document.createElement('button');
            btnEliminar.textContent = 'Eliminar';
            btnEliminar.onclick = () => eliminarDelCarrito(i);
            fila.insertCell().appendChild(btnEliminar);
        });

        document.getElementById('total').textContent = total.toFixed(2);
    }

    function finalizarVenta() {
        if (carrito.length === 0) {
            alert('El carrito está vacío.');
            return;
        }

        const total = carrito.reduce((acc, i) => acc + i.precio * i.cantidad, 0);

        fetch('index.php?page=guardar_venta', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ carrito, total })
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                alert('Venta registrada correctamente. ID: ' + data.id_venta);
                vaciarCarrito();
            } else {
                alert('Error al guardar la venta: ' + data.error);
            }
        })
        .catch(() => alert('Error de conexión al guardar la venta.'));
    }
</script>

</body>
</html>