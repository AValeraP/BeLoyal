<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Be Loyal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: { extend: { fontFamily: { inter: ['Inter', 'sans-serif'] } } }
    }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 2px; }

        /* Custom select dropdown */
        .custom-select-dropdown { transition: opacity .15s, transform .15s; transform-origin: top; }
        .custom-select-dropdown.hidden { opacity: 0; pointer-events: none; transform: scaleY(.96); }
        .custom-select-dropdown:not(.hidden) { opacity: 1; transform: scaleY(1); }

        /* Ocultar flechas de inputs numéricos */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Tablas con scroll horizontal en móvil */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-wrap table { min-width: 500px; }

        /* Sidebar móvil */
        @media (max-width: 767px) {
            #sidebar { position: fixed; top: 0; left: 0; bottom: 0; transform: translateX(-100%); transition: transform .25s ease; z-index: 60; }
            #sidebar.open { transform: translateX(0); }
            #sidebar-backdrop { display: none; }
            #sidebar-backdrop.open { display: block; }
        }
    </style>
</head>
<body class="font-inter flex h-screen overflow-hidden text-white"
      style="background-color:#262626;background-image:radial-gradient(ellipse at 10% 20%,rgba(255,255,255,.10) 0%,transparent 50%),radial-gradient(ellipse at 90% 80%,rgba(255,255,255,.07) 0%,transparent 50%),radial-gradient(ellipse at 60% 40%,rgba(220,220,220,.05) 0%,transparent 40%),repeating-linear-gradient(115deg,transparent 0px,transparent 20px,rgba(255,255,255,.05) 20px,rgba(255,255,255,.05) 21px,transparent 21px,transparent 45px,rgba(255,255,255,.025) 45px,rgba(255,255,255,.025) 46px),repeating-linear-gradient(68deg,transparent 0px,transparent 35px,rgba(255,255,255,.03) 35px,rgba(255,255,255,.03) 36px)">

<!-- MOBILE HAMBURGER -->
<button id="sidebar-toggle" aria-label="Menú"
        class="md:hidden fixed top-3 left-3 z-50 w-10 h-10 rounded-xl bg-black/70 border border-white/10 flex items-center justify-center text-white">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
</button>

<!-- BACKDROP MÓVIL -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-black/60 z-50 md:hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="w-48 bg-black/50 backdrop-blur-sm border-r border-white/10 flex flex-col flex-shrink-0 h-screen md:sticky md:top-0 overflow-y-auto">

    <div class="px-5 py-5 border-b border-white/10">
        <img src="public/img/logos/beloyallogo.png" alt="Be Loyal" class="w-12 h-12 object-contain mb-2">
        <p class="text-xs font-light text-zinc-500">Admin</p>
    </div>

    <div class="px-5 py-4 border-b border-white/10">
        <p class="text-xs font-medium text-white"><?= htmlspecialchars($usuario['nombre']) ?></p>
        <p class="text-xs font-light text-zinc-600 mt-0.5">Administrador</p>
    </div>

    <nav class="flex-1 py-2">
        <a href="index.php?page=admin&seccion=dashboard" class="block px-5 py-2.5 text-xs transition <?= $seccion === 'dashboard' ? 'text-white font-medium' : 'text-zinc-500 hover:text-zinc-200 font-light' ?>">Dashboard</a>
        <a href="index.php?page=admin&seccion=servicios"  class="block px-5 py-2.5 text-xs transition <?= $seccion === 'servicios'  ? 'text-white font-medium' : 'text-zinc-500 hover:text-zinc-200 font-light' ?>">Servicios</a>
        <a href="index.php?page=admin&seccion=productos"  class="block px-5 py-2.5 text-xs transition <?= $seccion === 'productos'  ? 'text-white font-medium' : 'text-zinc-500 hover:text-zinc-200 font-light' ?>">Productos</a>
        <a href="index.php?page=admin&seccion=empleados"  class="block px-5 py-2.5 text-xs transition <?= $seccion === 'empleados'  ? 'text-white font-medium' : 'text-zinc-500 hover:text-zinc-200 font-light' ?>">Empleados</a>
    </nav>

    <div class="px-5 py-4 border-t border-white/10">
        <a href="index.php?page=logout" class="text-xs font-light text-zinc-600 hover:text-white transition">Cerrar sesión</a>
    </div>

</aside>

<!-- CONTENT -->
<main class="flex-1 p-4 sm:p-7 overflow-y-auto pt-16 md:pt-7">

    <?php if ($mensaje): ?>
    <div class="text-xs font-light text-emerald-400 bg-emerald-900/30 border border-emerald-800/50 rounded-xl px-4 py-2.5 mb-5">
        <?= htmlspecialchars($mensaje) ?>
    </div>
    <?php endif; ?>

    <?php if ($seccion === 'dashboard'): ?>
    <!-- DASHBOARD -->
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
        <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest">Dashboard</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="abrirResetModal()"
                    class="px-3 py-1.5 text-xs rounded-lg border border-red-500/30 text-red-400 hover:bg-red-900/30 hover:border-red-500/60 transition">
                Resetear ventas
            </button>
            <div class="flex flex-wrap gap-1.5">
            <?php foreach (['todo' => 'Todo', 'dia' => 'Hoy', 'semana' => 'Semana', 'mes' => 'Mes', 'ano' => 'Año'] as $val => $label): ?>
            <a href="index.php?page=admin&seccion=dashboard&periodo=<?= $val ?>"
               class="px-3 py-1.5 text-xs rounded-lg transition <?= $periodo === $val ? 'bg-white text-zinc-900 font-semibold' : 'border border-white/10 text-zinc-500 hover:text-white' ?>">
                <?= $label ?>
            </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-600 mb-2">Total ventas</p>
            <p class="text-3xl font-light text-white"><?= $resumen['total_ventas'] ?></p>
        </div>
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-600 mb-2">Ingresos</p>
            <p class="text-3xl font-light text-white">€<?= number_format($resumen['ingresos_totales'], 2) ?></p>
        </div>
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 border-emerald-900/40">
            <p class="text-xs font-light uppercase tracking-widest text-emerald-700 mb-2">Efectivo</p>
            <p class="text-3xl font-light text-emerald-400">€<?= number_format($porMetodo['efectivo']['ingresos'], 2) ?></p>
            <p class="text-xs font-light text-zinc-600 mt-1"><?= $porMetodo['efectivo']['ventas'] ?> ventas</p>
        </div>
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 border-blue-900/40">
            <p class="text-xs font-light uppercase tracking-widest text-blue-700 mb-2">Tarjeta</p>
            <p class="text-3xl font-light text-blue-400">€<?= number_format($porMetodo['tarjeta']['ingresos'], 2) ?></p>
            <p class="text-xs font-light text-zinc-600 mt-1"><?= $porMetodo['tarjeta']['ventas'] ?> ventas</p>
        </div>
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-600 mb-2">Servicios activos</p>
            <p class="text-3xl font-light text-white"><?= count($servicios) ?></p>
        </div>
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-600 mb-2">Productos activos</p>
            <p class="text-3xl font-light text-white"><?= count($productos) ?></p>
        </div>
    </div>

    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 mb-3">
        <p class="text-xs font-light text-zinc-500 mb-4">Ventas por empleado</p>
        <div class="table-wrap"><table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Empleado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Ventas</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Ingresos</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-emerald-400 pb-2 border-b border-white/5">Efectivo</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-blue-400 pb-2 border-b border-white/5">Tarjeta</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($porEmpleado as $e): ?>
                <tr class="border-b border-white/5 last:border-0">
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= htmlspecialchars($e['nombre']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= $e['ventas'] ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400">€<?= number_format($e['ingresos'], 2) ?></td>
                    <td class="py-2.5 text-xs font-light text-emerald-400">€<?= number_format($e['ingresos_efectivo'], 2) ?></td>
                    <td class="py-2.5 text-xs font-light text-blue-400">€<?= number_format($e['ingresos_tarjeta'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($porEmpleado)): ?>
                <tr><td colspan="5" class="py-4 text-center text-xs font-light text-zinc-600">Sin datos</td></tr>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
            <p class="text-xs font-light text-zinc-500 mb-4">Servicios más vendidos</p>
            <div class="table-wrap"><table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Servicio</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Uds.</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($svcVendidos as $s): ?>
                    <tr class="border-b border-white/5 last:border-0">
                        <td class="py-2.5 text-xs font-light text-zinc-400"><?= htmlspecialchars($s['nombre']) ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-400"><?= $s['unidades'] ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-400">€<?= number_format($s['ingresos'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($svcVendidos)): ?>
                    <tr><td colspan="3" class="py-4 text-center text-xs font-light text-zinc-600">Sin datos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table></div>
        </div>
        <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
            <p class="text-xs font-light text-zinc-500 mb-4">Productos más vendidos</p>
            <div class="table-wrap"><table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Producto</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Uds.</th>
                        <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prodVendidos as $p): ?>
                    <tr class="border-b border-white/5 last:border-0">
                        <td class="py-2.5 text-xs font-light text-zinc-400"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-400"><?= $p['unidades'] ?></td>
                        <td class="py-2.5 text-xs font-light text-zinc-400">€<?= number_format($p['ingresos'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($prodVendidos)): ?>
                    <tr><td colspan="3" class="py-4 text-center text-xs font-light text-zinc-600">Sin datos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table></div>
        </div>
    </div>

    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 mt-3">
        <p class="text-xs font-light text-zinc-500 mb-4">Ventas recientes</p>
        <div class="table-wrap"><table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">#</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Fecha</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Hora</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Empleado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Método</th>
                    <th class="text-right text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventasRecientes as $v):
                    $dt = new DateTime($v['fecha']);
                ?>
                <tr class="border-b border-white/5 last:border-0">
                    <td class="py-2 text-xs font-light text-zinc-600">#<?= $v['id_venta'] ?></td>
                    <td class="py-2 text-xs font-light text-zinc-400"><?= $dt->format('d/m/Y') ?></td>
                    <td class="py-2 text-xs font-light text-zinc-400"><?= $dt->format('H:i') ?></td>
                    <td class="py-2 text-xs font-light text-zinc-400"><?= htmlspecialchars($v['empleado'] ?? '—') ?></td>
                    <td class="py-2 text-xs font-light text-zinc-400"><?= htmlspecialchars(ucfirst($v['metodo_pago'] ?? '—')) ?></td>
                    <td class="py-2 text-xs font-light text-white text-right">€<?= number_format($v['total'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($ventasRecientes)): ?>
                <tr><td colspan="6" class="py-4 text-center text-xs font-light text-zinc-600">Sin ventas registradas</td></tr>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>

    <?php elseif ($seccion === 'servicios'): ?>
    <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-6">Servicios</p>

    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 mb-3">
        <p class="text-xs font-light text-zinc-500 mb-4"><?= $editServicio ? 'Editar servicio' : 'Nuevo servicio' ?></p>
        <form method="POST" action="index.php?page=<?= $editServicio ? 'admin_actualizar_servicio' : 'admin_crear_servicio' ?>">
            <?= csrf_field() ?>
            <?php if ($editServicio): ?>
            <input type="hidden" name="id" value="<?= $editServicio['id_servicio'] ?>">
            <?php endif; ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Nombre</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($editServicio['nombre'] ?? '') ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Precio (€)</label>
                    <input type="number" name="precio" step="0.01" min="0" required value="<?= $editServicio['precio'] ?? '' ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Duración (min)</label>
                    <input type="number" name="duracion" min="0" required value="<?= $editServicio['duracion'] ?? '' ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Tipo</label>
                    <?php $optsS = ['peluqueria'=>'Peluquería','trenzas'=>'Trenzas','uñas'=>'Uñas','bono'=>'Bono']; $selS = $editServicio['especialidad'] ?? 'peluqueria'; ?>
                    <div class="custom-select relative">
                        <input type="hidden" name="especialidad" value="<?= $selS ?>">
                        <button type="button" class="custom-select-btn w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition flex items-center justify-between">
                            <span class="custom-select-label"><?= $optsS[$selS] ?></span>
                            <svg class="w-3.5 h-3.5 text-zinc-500" style="transition:transform .2s" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="custom-select-dropdown hidden absolute top-full left-0 right-0 mt-1 bg-zinc-800 border border-white/10 rounded-xl overflow-hidden z-50 shadow-xl shadow-black/40 py-1">
                            <?php foreach ($optsS as $v => $l): ?>
                            <div class="custom-select-option px-3 py-2 text-xs font-light cursor-pointer transition <?= $v === $selS ? 'text-white bg-white/5' : 'text-zinc-400 hover:bg-zinc-700/50 hover:text-zinc-200' ?>" data-value="<?= $v ?>"><?= $l ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-white text-zinc-900 text-xs font-semibold rounded-xl hover:bg-zinc-100 transition">
                    <?= $editServicio ? 'Guardar cambios' : '+ Añadir servicio' ?>
                </button>
                <?php if ($editServicio): ?>
                <a href="index.php?page=admin&seccion=servicios" class="px-5 py-2.5 border border-white/10 text-xs font-light text-zinc-500 rounded-xl hover:text-white transition">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php
    $grupos = ['peluqueria' => 'Peluquería', 'trenzas' => 'Trenzas', 'uñas' => ' Uñas', 'bono' => 'Bonos'];
    $serviciosPorGrupo = [];
    foreach ($servicios as $s) {
        $esp = $s['especialidad'] ?? 'peluqueria';
        $serviciosPorGrupo[$esp][] = $s;
    }
    ?>
    <?php foreach ($grupos as $esp => $titulo): ?>
    <?php if (!empty($serviciosPorGrupo[$esp])): ?>
    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 mb-3">
        <p class="text-xs font-light text-zinc-500 mb-4"><?= $titulo ?></p>
        <div class="table-wrap"><table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Nombre</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Precio</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Duración</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Estado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($serviciosPorGrupo[$esp] as $s): ?>
                <tr class="border-b border-white/5 last:border-0">
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= htmlspecialchars($s['nombre']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400">€<?= number_format($s['precio'], 2) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= $s['duracion'] ?> min</td>
                    <td class="py-2.5">
                        <span class="text-xs font-light px-2 py-0.5 rounded-full <?= $s['activo'] ? 'bg-emerald-900/40 text-emerald-400' : 'bg-zinc-800 text-zinc-500' ?>">
                            <?= $s['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td class="py-2.5 flex gap-3">
                        <a href="index.php?page=admin&seccion=servicios&editar=<?= $s['id_servicio'] ?>" class="text-xs font-light text-zinc-500 hover:text-white transition">Editar</a>
                        <?php if ($s['activo']): ?>
                        <a href="#" onclick="abrirConfirm('¿Eliminar este servicio? Esta acción no se puede deshacer.', 'index.php?page=admin_eliminar_servicio&id=<?= $s['id_servicio'] ?>', 'Eliminar servicio')" class="text-xs font-light text-red-500/60 hover:text-red-400 transition">Eliminar</a>
                        <?php else: ?>
                        <a href="index.php?page=admin_activar_servicio&id=<?= $s['id_servicio'] ?>" class="text-xs font-light text-emerald-500/70 hover:text-emerald-400 transition">Activar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <?php elseif ($seccion === 'productos'): ?>
    <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-6">Productos</p>

    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 mb-3">
        <p class="text-xs font-light text-zinc-500 mb-4"><?= $editProducto ? 'Editar producto' : 'Nuevo producto' ?></p>
        <form method="POST" action="index.php?page=<?= $editProducto ? 'admin_actualizar_producto' : 'admin_crear_producto' ?>">
            <?= csrf_field() ?>
            <?php if ($editProducto): ?>
            <input type="hidden" name="id" value="<?= $editProducto['id_producto'] ?>">
            <?php endif; ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Nombre</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($editProducto['nombre'] ?? '') ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Precio (€)</label>
                    <input type="number" name="precio" step="0.01" min="0" required value="<?= $editProducto['precio'] ?? '' ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Stock</label>
                    <input type="number" name="stock" min="0" required value="<?= $editProducto['stock'] ?? '' ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-white text-zinc-900 text-xs font-semibold rounded-xl hover:bg-zinc-100 transition">
                    <?= $editProducto ? 'Guardar cambios' : '+ Añadir producto' ?>
                </button>
                <?php if ($editProducto): ?>
                <a href="index.php?page=admin&seccion=productos" class="px-5 py-2.5 border border-white/10 text-xs font-light text-zinc-500 rounded-xl hover:text-white transition">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
        <p class="text-xs font-light text-zinc-500 mb-4">Todos los productos</p>
        <div class="table-wrap"><table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Nombre</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Precio</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Stock</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Estado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $p): ?>
                <tr class="border-b border-white/5 last:border-0">
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= htmlspecialchars($p['nombre']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400">€<?= number_format($p['precio'], 2) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= $p['stock'] ?></td>
                    <td class="py-2.5">
                        <span class="text-xs font-light px-2 py-0.5 rounded-full <?= $p['activo'] ? 'bg-emerald-900/40 text-emerald-400' : 'bg-zinc-800 text-zinc-500' ?>">
                            <?= $p['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td class="py-2.5 flex gap-3">
                        <a href="index.php?page=admin&seccion=productos&editar=<?= $p['id_producto'] ?>" class="text-xs font-light text-zinc-500 hover:text-white transition">Editar</a>
                        <?php if ($p['activo']): ?>
                        <a href="#" onclick="abrirConfirm('¿Eliminar este producto? Esta acción no se puede deshacer.', 'index.php?page=admin_eliminar_producto&id=<?= $p['id_producto'] ?>', 'Eliminar producto')" class="text-xs font-light text-red-500/60 hover:text-red-400 transition">Eliminar</a>
                        <?php else: ?>
                        <a href="index.php?page=admin_activar_producto&id=<?= $p['id_producto'] ?>" class="text-xs font-light text-emerald-500/70 hover:text-emerald-400 transition">Activar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($productos)): ?>
                <tr><td colspan="5" class="py-4 text-center text-xs font-light text-zinc-600">No hay productos</td></tr>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>

    <?php elseif ($seccion === 'empleados'): ?>
    <p class="text-xs font-medium text-zinc-500 uppercase tracking-widest mb-6">Empleados</p>

    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 mb-3">
        <p class="text-xs font-light text-zinc-500 mb-4"><?= $editEmpleado ? 'Editar empleado' : 'Nuevo empleado' ?></p>
        <form method="POST" enctype="multipart/form-data" action="index.php?page=<?= $editEmpleado ? 'admin_actualizar_empleado' : 'admin_crear_empleado' ?>">
            <?= csrf_field() ?>
            <?php if ($editEmpleado): ?>
            <input type="hidden" name="id" value="<?= $editEmpleado['id_usuario'] ?>">
            <?php endif; ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Nombre</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($editEmpleado['nombre'] ?? '') ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Email</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($editEmpleado['email'] ?? '') ?>"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">
                        <?= $editEmpleado ? 'Nueva contraseña (vacío = no cambiar)' : 'Contraseña' ?>
                    </label>
                    <input type="password" name="password" <?= $editEmpleado ? '' : 'required' ?> placeholder="••••••••"
                        class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition placeholder-zinc-600">
                </div>
                <div>
                    <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-1.5">Especialidad</label>
                    <?php $optsE = ['peluqueria'=>'Peluquería','trenzas'=>'Trenzas','uñas'=>'Uñas']; $selE = $editEmpleado['especialidad'] ?? 'peluqueria'; ?>
                    <div class="custom-select relative">
                        <input type="hidden" name="especialidad" value="<?= $selE ?>">
                        <button type="button" class="custom-select-btn w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-white/30 transition flex items-center justify-between">
                            <span class="custom-select-label"><?= $optsE[$selE] ?></span>
                            <svg class="w-3.5 h-3.5 text-zinc-500" style="transition:transform .2s" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="custom-select-dropdown hidden absolute top-full left-0 right-0 mt-1 bg-zinc-800 border border-white/10 rounded-xl overflow-hidden z-50 shadow-xl shadow-black/40 py-1">
                            <?php foreach ($optsE as $v => $l): ?>
                            <div class="custom-select-option px-3 py-2 text-xs font-light cursor-pointer transition <?= $v === $selE ? 'text-white bg-white/5' : 'text-zinc-400 hover:bg-zinc-700/50 hover:text-zinc-200' ?>" data-value="<?= $v ?>"><?= $l ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($editEmpleado): ?>
            <div class="mb-3">
                <label class="flex items-center gap-2 text-xs font-light text-zinc-500 cursor-pointer">
                    <input type="checkbox" name="activo" value="1" <?= $editEmpleado['activo'] ? 'checked' : '' ?>
                        class="rounded border-zinc-700 bg-zinc-800">
                    Empleado activo
                </label>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-light text-zinc-400 uppercase tracking-widest mb-2">Foto del empleado</label>
                <?php if (!empty($editEmpleado['logo'])): ?>
                <div class="flex items-center gap-3 mb-2">
                    <img src="public/img/logos/<?= htmlspecialchars($editEmpleado['logo']) ?>"
                         class="w-12 h-12 rounded-full object-cover border border-white/10"
                         onerror="this.style.display='none'">
                    <label class="flex items-center gap-2 text-xs font-light text-zinc-500 cursor-pointer">
                        <input type="checkbox" name="quitar_foto" value="1" class="rounded border-zinc-700 bg-zinc-800">
                        Quitar foto actual
                    </label>
                </div>
                <?php endif; ?>
                <input type="file" name="foto_empleado" accept="image/*"
                       class="block w-full text-xs text-zinc-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-zinc-700 file:text-zinc-200 hover:file:bg-zinc-600 cursor-pointer">
                <p class="text-[0.7rem] text-zinc-600 mt-1">JPG, PNG, WEBP — se muestra en la cabecera del panel del empleado.</p>
            </div>
            <?php endif; ?>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-white text-zinc-900 text-xs font-semibold rounded-xl hover:bg-zinc-100 transition">
                    <?= $editEmpleado ? 'Guardar cambios' : '+ Añadir empleado' ?>
                </button>
                <?php if ($editEmpleado): ?>
                <a href="index.php?page=admin&seccion=empleados" class="px-5 py-2.5 border border-white/10 text-xs font-light text-zinc-500 rounded-xl hover:text-white transition">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
        <p class="text-xs font-light text-zinc-500 mb-4">Todos los empleados</p>
        <div class="table-wrap"><table class="w-full">
            <thead>
                <tr>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5 w-8"></th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Nombre</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Email</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Especialidad</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Estado</th>
                    <th class="text-left text-xs font-light uppercase tracking-widest text-zinc-400 pb-2 border-b border-white/5">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $emp): ?>
                <tr class="border-b border-white/5 last:border-0">
                    <td class="py-2.5">
                        <?php if (!empty($emp['logo'])): ?>
                        <img src="public/img/logos/<?= htmlspecialchars($emp['logo']) ?>"
                             class="w-7 h-7 rounded-full object-cover border border-white/10"
                             onerror="this.style.display='none'">
                        <?php else: ?>
                        <div class="w-7 h-7 rounded-full bg-zinc-800 border border-white/10 flex items-center justify-center text-[10px] font-semibold text-zinc-500">
                            <?= mb_strtoupper(mb_substr($emp['nombre'], 0, 1)) ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= htmlspecialchars($emp['nombre']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= htmlspecialchars($emp['email']) ?></td>
                    <td class="py-2.5 text-xs font-light text-zinc-400"><?= ucfirst(htmlspecialchars($emp['especialidad'])) ?></td>
                    <td class="py-2.5">
                        <span class="text-xs font-light px-2 py-0.5 rounded-full <?= $emp['activo'] ? 'bg-emerald-900/40 text-emerald-400' : 'bg-zinc-800 text-zinc-500' ?>">
                            <?= $emp['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td class="py-2.5 flex gap-3">
                        <a href="index.php?page=admin&seccion=empleados&editar=<?= $emp['id_usuario'] ?>" class="text-xs font-light text-zinc-500 hover:text-white transition">Editar</a>
                        <?php if ($emp['activo']): ?>
                        <a href="#" onclick="abrirConfirm('¿Desactivar este empleado? Podrás eliminarlo definitivamente cuando esté inactivo.', 'index.php?page=admin_eliminar_empleado&id=<?= $emp['id_usuario'] ?>', 'Desactivar empleado')" class="text-xs font-light text-amber-500/70 hover:text-amber-400 transition">Desactivar</a>
                        <?php else: ?>
                        <a href="index.php?page=admin_activar_empleado&id=<?= $emp['id_usuario'] ?>" class="text-xs font-light text-emerald-500/70 hover:text-emerald-400 transition">Activar</a>
                        <a href="#" onclick="abrirConfirm('¿Eliminar definitivamente este empleado? Esta acción no se puede deshacer. Solo es posible si no tiene ventas registradas.', 'index.php?page=admin_eliminar_definitivo_empleado&id=<?= $emp['id_usuario'] ?>', 'Eliminar definitivamente')" class="text-xs font-light text-red-500/60 hover:text-red-400 transition">Eliminar definitivamente</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($empleados)): ?>
                <tr><td colspan="5" class="py-4 text-center text-xs font-light text-zinc-600">No hay empleados</td></tr>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>

    <?php endif; ?>

</main>


<!-- MODAL CONFIRMACIÓN GENÉRICO -->
<div id="confirm-modal" style="display:none;" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-zinc-900 border border-white/10 rounded-2xl p-7 w-80 max-w-full mx-4 shadow-2xl">
        <p class="text-base font-semibold text-white mb-1" id="confirm-title">¿Estás seguro?</p>
        <p class="text-xs text-zinc-500 mb-6" id="confirm-msg"></p>
        <div class="flex gap-3">
            <button onclick="cerrarConfirm()" class="flex-1 border border-white/10 text-xs text-zinc-400 py-3 rounded-xl hover:text-white hover:border-white/30 transition">
                Cancelar
            </button>
            <a id="confirm-btn" href="#" class="flex-1 text-center bg-red-600 text-white text-xs font-semibold py-3 rounded-xl hover:bg-red-500 transition">
                Confirmar
            </a>
        </div>
    </div>
</div>

<!-- MODAL RESETEAR VENTAS (con contraseña) -->
<div id="reset-modal" style="display:none;" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-zinc-900 border border-white/10 rounded-2xl p-7 w-80 max-w-full mx-4 shadow-2xl">
        <p class="text-base font-semibold text-white mb-1">Resetear ventas</p>
        <p class="text-xs text-zinc-500 mb-5">Esta acción eliminará <strong class="text-red-400">TODAS</strong> las ventas y no se puede deshacer. Introduce tu contraseña para confirmar.</p>
        <form method="POST" action="index.php?page=admin_resetear_ventas">
            <?= csrf_field() ?>
            <input type="password" name="password_confirm" id="reset-pw-input"
                   placeholder="Tu contraseña de administrador"
                   autocomplete="current-password"
                   class="w-full bg-zinc-800/80 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-light text-zinc-200 focus:outline-none focus:border-red-500/50 transition mb-4 placeholder-zinc-600">
            <div class="flex gap-3">
                <button type="button" onclick="cerrarResetModal()" class="flex-1 border border-white/10 text-xs text-zinc-400 py-3 rounded-xl hover:text-white hover:border-white/30 transition">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 bg-red-600 text-white text-xs font-semibold py-3 rounded-xl hover:bg-red-500 transition">
                    Confirmar y eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CONTADOR DE INACTIVIDAD -->
<div id="inactivity-badge" style="display:none;"
     class="fixed bottom-4 right-4 z-50 bg-zinc-900 border border-white/10 rounded-xl px-3 py-2 text-xs text-zinc-500 shadow-lg select-none">
    Sesión expira en <span id="inactivity-secs" class="text-white font-medium"></span>s
</div>

<script>
// ── Sidebar móvil ────────────────────────────────────────────────────────────
(function () {
    const sidebar  = document.getElementById('sidebar');
    const toggle   = document.getElementById('sidebar-toggle');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!sidebar || !toggle || !backdrop) return;
    backdrop.classList.remove('open');
    function open()  { sidebar.classList.add('open');    backdrop.classList.add('open');    }
    function close() { sidebar.classList.remove('open'); backdrop.classList.remove('open'); }
    toggle.addEventListener('click', open);
    backdrop.addEventListener('click', close);
    sidebar.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
})();

function abrirConfirm(msg, url, titulo) {
    document.getElementById('confirm-title').textContent = titulo || '¿Estás seguro?';
    document.getElementById('confirm-msg').textContent = msg;
    document.getElementById('confirm-btn').href = url;
    document.getElementById('confirm-modal').style.display = 'flex';
}
function cerrarConfirm() {
    document.getElementById('confirm-modal').style.display = 'none';
}
document.getElementById('confirm-modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarConfirm();
});

function abrirResetModal() {
    document.getElementById('reset-pw-input').value = '';
    document.getElementById('reset-modal').style.display = 'flex';
    setTimeout(() => document.getElementById('reset-pw-input').focus(), 50);
}
function cerrarResetModal() {
    document.getElementById('reset-modal').style.display = 'none';
}
document.getElementById('reset-modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarResetModal();
});

// ── Custom selects ──────────────────────────────────────────────────────────
(function () {
    function findPanel(el) {
        var node = el.parentElement;
        while (node && node !== document.body) {
            if (node.classList && node.classList.contains('rounded-2xl') && node.classList.contains('backdrop-blur-sm')) {
                return node;
            }
            node = node.parentElement;
        }
        return null;
    }

    function closeAll() {
        document.querySelectorAll('.custom-select-dropdown').forEach(function(d) {
            d.classList.add('hidden');
        });
        document.querySelectorAll('.custom-select-btn svg').forEach(function(s) {
            s.style.transform = '';
        });
        document.querySelectorAll('[data-cs-bumped]').forEach(function(p) {
            p.style.zIndex = '';
            p.style.position = '';
            p.removeAttribute('data-cs-bumped');
        });
    }

    document.querySelectorAll('.custom-select').forEach(function(wrap) {
        var btn      = wrap.querySelector('.custom-select-btn');
        var dropdown = wrap.querySelector('.custom-select-dropdown');
        var hidden   = wrap.querySelector('input[type=hidden]');
        var label    = wrap.querySelector('.custom-select-label');
        var chevron  = btn.querySelector('svg');

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = !dropdown.classList.contains('hidden');
            closeAll();
            if (!isOpen) {
                var panel = findPanel(wrap);
                if (panel) {
                    panel.style.position = 'relative';
                    panel.style.zIndex = '40';
                    panel.setAttribute('data-cs-bumped', '1');
                }
                dropdown.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            }
        });

        wrap.querySelectorAll('.custom-select-option').forEach(function(opt) {
            opt.addEventListener('click', function() {
                hidden.value = opt.dataset.value;
                label.textContent = opt.textContent.trim();
                wrap.querySelectorAll('.custom-select-option').forEach(function(o) {
                    o.classList.remove('text-white', 'bg-white/5');
                    o.classList.add('text-zinc-400');
                    o.classList.remove('hover:bg-zinc-700/50', 'hover:text-zinc-200');
                });
                opt.classList.add('text-white', 'bg-white/5');
                opt.classList.remove('text-zinc-400');
                dropdown.classList.add('hidden');
                chevron.style.transform = '';
            });
        });
    });

    document.addEventListener('click', closeAll);
})();

// ── Contador de inactividad ──────────────────────────────────────────────────
(function () {
    const TIMEOUT   = 300;
    const WARN_AT   = 60;
    const badge     = document.getElementById('inactivity-badge');
    const secsEl    = document.getElementById('inactivity-secs');
    let remaining   = TIMEOUT;
    let interval;

    function resetTimer() {
        remaining = TIMEOUT;
        badge.style.display = 'none';
    }

    function tick() {
        remaining--;
        if (remaining <= 0) {
            clearInterval(interval);
            window.location.href = 'index.php?page=logout';
            return;
        }
        if (remaining <= WARN_AT) {
            badge.style.display = 'block';
            secsEl.textContent  = remaining;
        } else {
            badge.style.display = 'none';
        }
    }

    ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function(evt) {
        document.addEventListener(evt, resetTimer, true);
    });

    interval = setInterval(tick, 1000);
})();
</script>

</body>
</html>