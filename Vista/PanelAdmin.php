<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Be Loyal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-zinc-50 flex min-h-screen text-zinc-800">

<!-- SIDEBAR -->
<aside class="w-48 bg-white border-r border-zinc-100 flex flex-col flex-shrink-0 min-h-screen">

    <div class="px-5 py-5 border-b border-zinc-100">
        <p class="text-xs font-light tracking-widest uppercase text-zinc-900">Be Loyal</p>
        <p class="text-xs font-light text-zinc-300 mt-0.5">Admin</p>
    </div>

    <div class="px-5 py-4 border-b border-zinc-100">
        <p class="text-xs font-normal text-zinc-600"><?= htmlspecialchars($usuario['nombre']) ?></p>
        <p class="text-xs font-light text-zinc-300 mt-0.5">Administrador</p>
    </div>

    <nav class="flex-1 py-2">
        <a href="index.php?page=admin&seccion=dashboard" class="block px-5 py-2.5 text-xs font-light transition <?= $seccion === 'dashboard' ? 'text-zinc-900 font-normal' : 'text-zinc-400 hover:text-zinc-800' ?>">Dashboard</a>
        <a href="index.php?page=admin&seccion=servicios"  class="block px-5 py-2.5 text-xs font-light transition <?= $seccion === 'servicios'  ? 'text-zinc-900 font-normal' : 'text-zinc-400 hover:text-zinc-800' ?>">Servicios</a>
        <a href="index.php?page=admin&seccion=productos"  class="block px-5 py-2.5 text-xs font-light transition <?= $seccion === 'productos'  ? 'text-zinc-900 font-normal' : 'text-zinc-400 hover:text-zinc-800' ?>">Productos</a>
        <a href="index.php?page=admin&seccion=empleados" class="block px-5 py-2.5 text-xs font-light transition <?= $seccion === 'empleados' ? 'text-zinc-900 font-normal' : 'text-zinc-400 hover:text-zinc-800' ?>">Empleados</a>
    </nav>

    <div class="px-5 py-4 border-t border-zinc-100">
        <a href="index.php?page=logout" class="text-xs font-light text-zinc-300 hover:text-zinc-500 transition">Cerrar sesion</a>
    </div>

</aside>

<!-- CONTENT -->
<main class="flex-1 p-7 overflow-y-auto">

    <?php if ($mensaje): ?>
    <div class="text-xs font-light text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg px-4 py-2.5 mb-5">
        <?= htmlspecialchars($mensaje) ?>
    </div>
    <?php endif; ?>

    <?php if ($seccion === 'dashboard'): ?>
    <!-- DASHBOARD -->
    <p class="text-xs font-light text-zinc-400 uppercase tracking-widest mb-6">Dashboard</p>

    <div class="grid grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-zinc-100 rounded-lg p-4">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-300 mb-1.5">Total ventas</p>
            <p class="text-2xl font-light text-zinc-800"><?= $resumen['total_ventas'] ?></p>
        </div>
        <div class="bg-white border border-zinc-100 rounded-lg p-4">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-300 mb-1.5">Ingresos</p>
            <p class="text-2xl font-light text-zinc-800">€<?= number_format($resumen['ingresos_totales'], 2) ?></p>
        </div>
        <div class="bg-white border border-zinc-100 rounded-lg p-4">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-300 mb-1.5">Servicios activos</p>
            <p class="text-2xl font-light text-zinc-800"><?= count($servicios) ?></p>
        </div>
        <div class="bg-white border border-zinc-100 rounded-lg p-4">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-300 mb-1.5">Productos activos</p>
            <p class="text-2xl font-light text-zinc-800"><?= count($productos) ?></p>
        </div>
    </div>

    <div class="bg-white border border-zinc-100 rounded-lg p-5 mb-3">
        <p class="text-xs font-light text-zinc-400 mb-4">Ventas por empleado</p>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Empleado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Ventas</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Ingresos</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($porEmpleado as $e): ?>
                <tr class="border-b border-zinc-50 last:border-0">
                    <td class="py-2.5 text-xs font-light text-zinc-500"><?= htmlspecialchars($e['nombre']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-500"><?= $e['ventas'] ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-500">€<?= number_format($e['ingresos'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($porEmpleado)): ?>
                <tr><td colspan="3" class="py-4 text-center text-xs font-light text-zinc-300">Sin datos</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white border border-zinc-100 rounded-lg p-5">
            <p class="text-xs font-light text-zinc-400 mb-4">Servicios mas vendidos</p>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Servicio</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Uds.</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($svcVendidos as $s): ?>
                    <tr class="border-b border-zinc-50 last:border-0">
                        <td class="py-2.5 text-xs font-light text-zinc-500"><?= htmlspecialchars($s['nombre']) ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-500"><?= $s['unidades'] ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-500">€<?= number_format($s['ingresos'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($svcVendidos)): ?>
                    <tr><td colspan="3" class="py-4 text-center text-xs font-light text-zinc-300">Sin datos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="bg-white border border-zinc-100 rounded-lg p-5">
            <p class="text-xs font-light text-zinc-400 mb-4">Productos mas vendidos</p>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Producto</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Uds.</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prodVendidos as $p): ?>
                    <tr class="border-b border-zinc-50 last:border-0">
                        <td class="py-2.5 text-xs font-light text-zinc-500"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-500"><?= $p['unidades'] ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-500">€<?= number_format($p['ingresos'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($prodVendidos)): ?>
                    <tr><td colspan="3" class="py-4 text-center text-xs font-light text-zinc-300">Sin datos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    const diasLabels = <?= json_encode(array_column($porDia, 'dia')) ?>;
    const diasData   = <?= json_encode(array_map(fn($r) => (float)$r['ingresos'], $porDia)) ?>;
    new Chart(document.getElementById('chartDias'), {
        type: 'line',
        data: { labels: diasLabels, datasets: [{ label: 'Ingresos', data: diasData, borderColor: '#a1a1aa', backgroundColor: 'rgba(161,161,170,0.06)', tension: 0.3, fill: true, pointRadius: 2 }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f4f4f5' }, ticks: { font: { family: 'Inter', size: 10, weight: '300' }, color: '#d4d4d8' } }, x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10, weight: '300' }, color: '#d4d4d8' } } } }
    });
    const empLabels = <?= json_encode(array_column($porEmpleado, 'nombre')) ?>;
    const empData   = <?= json_encode(array_map(fn($r) => (float)$r['ingresos'], $porEmpleado)) ?>;
    new Chart(document.getElementById('chartEmpleados'), {
        type: 'doughnut',
        data: { labels: empLabels, datasets: [{ data: empData, backgroundColor: ['#e4e4e7','#a1a1aa','#71717a','#52525b','#3f3f46','#27272a'] }] },
        options: { plugins: { legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 10, weight: '300' }, color: '#a1a1aa', boxWidth: 10 } } } }
    });
    const svcLabels = <?= json_encode(array_column($svcVendidos, 'nombre')) ?>;
    const svcData   = <?= json_encode(array_map(fn($r) => (int)$r['unidades'], $svcVendidos)) ?>;
    new Chart(document.getElementById('chartServicios'), {
        type: 'bar',
        data: { labels: svcLabels, datasets: [{ label: 'Unidades', data: svcData, backgroundColor: '#e4e4e7', borderRadius: 4 }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f4f4f5' }, ticks: { font: { family: 'Inter', size: 10, weight: '300' }, color: '#d4d4d8' } }, x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10, weight: '300' }, color: '#d4d4d8' } } } }
    });
    </script>

    <?php elseif ($seccion === 'servicios'): ?>
    <!-- CRUD SERVICIOS -->
    <p class="text-xs font-light text-zinc-400 uppercase tracking-widest mb-6">Servicios</p>

    <div class="bg-white border border-zinc-100 rounded-lg p-5 mb-3">
        <p class="text-xs font-light text-zinc-400 mb-4"><?= $editServicio ? 'Editar servicio' : 'Nuevo servicio' ?></p>
        <form method="POST" action="index.php?page=<?= $editServicio ? 'admin_actualizar_servicio' : 'admin_crear_servicio' ?>">
            <?php if ($editServicio): ?>
            <input type="hidden" name="id" value="<?= $editServicio['id_servicio'] ?>">
            <?php endif; ?>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Nombre</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($editServicio['nombre'] ?? '') ?>"
                        class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Precio (€)</label>
                    <input type="number" name="precio" step="0.01" min="0" required value="<?= $editServicio['precio'] ?? '' ?>"
                        class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Duracion (min)</label>
                    <input type="number" name="duracion" min="0" required value="<?= $editServicio['duracion'] ?? '' ?>"
                        class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-zinc-900 text-white text-xs font-light rounded-lg hover:bg-zinc-700 transition">
                    <?= $editServicio ? 'Guardar cambios' : '+ Anadir servicio' ?>
                </button>
                <?php if ($editServicio): ?>
                <a href="index.php?page=admin&seccion=servicios" class="px-4 py-2 border border-zinc-100 text-xs font-light text-zinc-400 rounded-lg hover:text-zinc-600 transition">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="bg-white border border-zinc-100 rounded-lg p-5">
        <p class="text-xs font-light text-zinc-400 mb-4">Todos los servicios</p>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Nombre</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Precio</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Duracion</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Estado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($servicios as $s): ?>
                <tr class="border-b border-zinc-50 last:border-0">
                    <td class="py-2.5 text-xs font-light text-zinc-500"><?= htmlspecialchars($s['nombre']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-500">€<?= number_format($s['precio'], 2) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-500"><?= $s['duracion'] ?> min</td>
                    <td class="py-2.5">
                        <span class="text-xs font-light px-2 py-0.5 rounded-full <?= $s['activo'] ? 'bg-emerald-50 text-emerald-600' : 'bg-zinc-100 text-zinc-400' ?>">
                            <?= $s['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td class="py-2.5 flex gap-3">
                        <a href="index.php?page=admin&seccion=servicios&editar=<?= $s['id_servicio'] ?>" class="text-xs font-light text-zinc-400 hover:text-zinc-700 transition">Editar</a>
                        <a href="index.php?page=admin_eliminar_servicio&id=<?= $s['id_servicio'] ?>" onclick="return confirm('Eliminar este servicio?')" class="text-xs font-light text-red-300 hover:text-red-500 transition">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($servicios)): ?>
                <tr><td colspan="5" class="py-4 text-center text-xs font-light text-zinc-300">No hay servicios</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($seccion === 'productos'): ?>
    <!-- CRUD PRODUCTOS -->
    <p class="text-xs font-light text-zinc-400 uppercase tracking-widest mb-6">Productos</p>

    <div class="bg-white border border-zinc-100 rounded-lg p-5 mb-3">
        <p class="text-xs font-light text-zinc-400 mb-4"><?= $editProducto ? 'Editar producto' : 'Nuevo producto' ?></p>
        <form method="POST" action="index.php?page=<?= $editProducto ? 'admin_actualizar_producto' : 'admin_crear_producto' ?>">
            <?php if ($editProducto): ?>
            <input type="hidden" name="id" value="<?= $editProducto['id_producto'] ?>">
            <?php endif; ?>
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Nombre</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($editProducto['nombre'] ?? '') ?>"
                        class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Precio (€)</label>
                    <input type="number" name="precio" step="0.01" min="0" required value="<?= $editProducto['precio'] ?? '' ?>"
                        class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Stock</label>
                    <input type="number" name="stock" min="0" required value="<?= $editProducto['stock'] ?? '' ?>"
                        class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-zinc-900 text-white text-xs font-light rounded-lg hover:bg-zinc-700 transition">
                    <?= $editProducto ? 'Guardar cambios' : '+ Anadir producto' ?>
                </button>
                <?php if ($editProducto): ?>
                <a href="index.php?page=admin&seccion=productos" class="px-4 py-2 border border-zinc-100 text-xs font-light text-zinc-400 rounded-lg hover:text-zinc-600 transition">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php elseif ($seccion === 'empleados'): ?>
        <p class="text-xs font-light text-zinc-400 uppercase tracking-widest mb-6">Empleados</p>
 
<!-- FORMULARIO -->
<div class="bg-white border border-zinc-100 rounded-lg p-5 mb-3">
    <p class="text-xs font-light text-zinc-400 mb-4"><?= $editEmpleado ? 'Editar empleado' : 'Nuevo empleado' ?></p>
    <form method="POST" action="index.php?page=<?= $editEmpleado ? 'admin_actualizar_empleado' : 'admin_crear_empleado' ?>">
        <?php if ($editEmpleado): ?>
        <input type="hidden" name="id" value="<?= $editEmpleado['id_usuario'] ?>">
        <?php endif; ?>
 
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Nombre</label>
                <input type="text" name="nombre" required value="<?= htmlspecialchars($editEmpleado['nombre'] ?? '') ?>"
                    class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
            </div>
            <div>
                <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($editEmpleado['email'] ?? '') ?>"
                    class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
            </div>
            <div>
                <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">
                    <?= $editEmpleado ? 'Nueva contrasena (dejar vacio para no cambiar)' : 'Contrasena' ?>
                </label>
                <input type="password" name="password" <?= $editEmpleado ? '' : 'required' ?> placeholder="••••••••"
                    class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
            </div>
            <div>
                <label class="block text-xs font-light text-zinc-300 uppercase tracking-widest mb-1.5">Especialidad</label>
                <select name="especialidad"
                    class="w-full bg-zinc-50 border border-zinc-100 rounded-lg px-3 py-2 text-xs font-light text-zinc-700 focus:outline-none focus:border-zinc-300 transition">
                    <?php foreach (['peluqueria', 'trenzas', 'unas', 'todas'] as $esp): ?>
                    <option value="<?= $esp ?>" <?= ($editEmpleado['especialidad'] ?? '') === $esp ? 'selected' : '' ?>>
                        <?= ucfirst($esp) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
 
        <?php if ($editEmpleado): ?>
        <div class="mb-4">
            <label class="flex items-center gap-2 text-xs font-light text-zinc-500 cursor-pointer">
                <input type="checkbox" name="activo" value="1" <?= $editEmpleado['activo'] ? 'checked' : '' ?>
                    class="rounded border-zinc-200">
                Empleado activo
            </label>
        </div>
        <?php endif; ?>
 
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-zinc-900 text-white text-xs font-light rounded-lg hover:bg-zinc-700 transition">
                <?= $editEmpleado ? 'Guardar cambios' : '+ Anadir empleado' ?>
            </button>
            <?php if ($editEmpleado): ?>
            <a href="index.php?page=admin&seccion=empleados" class="px-4 py-2 border border-zinc-100 text-xs font-light text-zinc-400 rounded-lg hover:text-zinc-600 transition">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>
</div>
 
<!-- TABLA -->
<div class="bg-white border border-zinc-100 rounded-lg p-5">
    <p class="text-xs font-light text-zinc-400 mb-4">Todos los empleados</p>
    <table class="w-full">
        <thead>
            <tr>
                <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Nombre</th>
                <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Email</th>
                <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Especialidad</th>
                <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Estado</th>
                <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $emp): ?>
            <tr class="border-b border-zinc-50 last:border-0">
                <td class="py-2.5 text-xs font-light text-zinc-500"><?= htmlspecialchars($emp['nombre']) ?></td>
                <td class="py-2.5 text-xs font-light text-zinc-500"><?= htmlspecialchars($emp['email']) ?></td>
                <td class="py-2.5 text-xs font-light text-zinc-500"><?= ucfirst(htmlspecialchars($emp['especialidad'])) ?></td>
                <td class="py-2.5">
                    <span class="text-xs font-light px-2 py-0.5 rounded-full <?= $emp['activo'] ? 'bg-emerald-50 text-emerald-600' : 'bg-zinc-100 text-zinc-400' ?>">
                        <?= $emp['activo'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </td>
                <td class="py-2.5 flex gap-3">
                    <a href="index.php?page=admin&seccion=empleados&editar=<?= $emp['id_usuario'] ?>" class="text-xs font-light text-zinc-400 hover:text-zinc-700 transition">Editar</a>
                    <a href="index.php?page=admin_eliminar_empleado&id=<?= $emp['id_usuario'] ?>"
                       onclick="return confirm('Eliminar este empleado?')"
                       class="text-xs font-light text-red-300 hover:text-red-500 transition">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($empleados)): ?>
            <tr><td colspan="5" class="py-4 text-center text-xs font-light text-zinc-300">No hay empleados</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
    <?php endif; ?>

    <div class="bg-white border border-zinc-100 rounded-lg p-5">
        <p class="text-xs font-light text-zinc-400 mb-4">Todos los productos</p>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Nombre</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Precio</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Stock</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Estado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-300 pb-2 border-b border-zinc-50">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr class="border-b border-zinc-50 last:border-0">
                    <td class="py-2.5 text-xs font-light text-zinc-500"><?= htmlspecialchars($p['nombre']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-500">€<?= number_format($p['precio'], 2) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-500"><?= $p['stock'] ?></td>
                    <td class="py-2.5">
                        <span class="text-xs font-light px-2 py-0.5 rounded-full <?= $p['activo'] ? 'bg-emerald-50 text-emerald-600' : 'bg-zinc-100 text-zinc-400' ?>">
                            <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td class="py-2.5 flex gap-3">
                        <a href="index.php?page=admin&seccion=productos&editar=<?= $p['id_producto'] ?>" class="text-xs font-light text-zinc-400 hover:text-zinc-700 transition">Editar</a>
                        <a href="index.php?page=admin_eliminar_producto&id=<?= $p['id_producto'] ?>" onclick="return confirm('Eliminar este producto?')" class="text-xs font-light text-red-300 hover:text-red-500 transition">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($productos)): ?>
                <tr><td colspan="5" class="py-4 text-center text-xs font-light text-zinc-300">No hay productos</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

</body>
</html>