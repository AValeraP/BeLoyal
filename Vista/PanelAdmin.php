<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – TPV Peluquería</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar { width: 220px; background: #222; min-height: 100vh; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-brand { padding: 1.2rem 1.5rem; background: #111; color: #d4a017; font-size: 1.1rem; font-weight: bold; border-bottom: 2px solid #d4a017; }
        .sidebar-user { padding: 1rem 1.5rem; color: #aaa; font-size: 0.82rem; border-bottom: 1px solid #333; }
        .sidebar-user strong { color: #fff; display: block; margin-bottom: 0.2rem; }
        .sidebar nav { flex: 1; padding: 0.5rem 0; }
        .nav-link { display: block; padding: 0.75rem 1.5rem; color: #ccc; text-decoration: none; font-size: 0.9rem; transition: background 0.15s; }
        .nav-link:hover { background: #333; color: #fff; }
        .nav-link.active { background: #d4a017; color: #000; font-weight: bold; }
        .sidebar-logout { padding: 1rem 1.5rem; }
        .sidebar-logout a { color: #888; font-size: 0.82rem; text-decoration: none; }
        .sidebar-logout a:hover { color: #fff; }

        /* CONTENT */
        .content { flex: 1; padding: 1.5rem; overflow-y: auto; }
        .page-title { font-size: 1.4rem; font-weight: bold; margin-bottom: 1.2rem; color: #222; }

        /* MENSAJE */
        .msg { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 0.7rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }

        /* DASHBOARD CARDS */
        .cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .card { background: #fff; border-radius: 10px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .card-label { font-size: 0.78rem; text-transform: uppercase; color: #888; letter-spacing: 0.08em; margin-bottom: 0.4rem; }
        .card-value { font-size: 1.8rem; font-weight: bold; color: #222; }
        .card-value.gold { color: #d4a017; }

        /* CHARTS */
        .charts { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
        .chart-box { background: #fff; border-radius: 10px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .chart-box h3 { font-size: 0.9rem; color: #555; margin-bottom: 1rem; }
        .chart-box.full { grid-column: 1 / -1; }

        /* TABLES */
        .table-box { background: #fff; border-radius: 10px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 1rem; }
        .table-box h3 { font-size: 0.95rem; font-weight: bold; margin-bottom: 1rem; color: #333; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th { text-align: left; padding: 0.5rem 0.7rem; background: #f5f5f5; color: #666; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.06em; }
        td { padding: 0.55rem 0.7rem; border-bottom: 1px solid #f0f0f0; color: #333; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }

        /* CRUD */
        .crud-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .btn { padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer; font-size: 0.88rem; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-gold { background: #d4a017; color: #000; }
        .btn-gold:hover { background: #b8880f; }
        .btn-danger { background: #c0392b; color: #fff; font-size: 0.78rem; padding: 0.3rem 0.7rem; }
        .btn-danger:hover { background: #a93226; }
        .btn-edit { background: #3498db; color: #fff; font-size: 0.78rem; padding: 0.3rem 0.7rem; }
        .btn-edit:hover { background: #2980b9; }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.78rem; }

        /* FORM */
        .form-box { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 1rem; }
        .form-box h3 { font-size: 0.95rem; font-weight: bold; margin-bottom: 1rem; color: #333; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.8rem; margin-bottom: 1rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.3rem; }
        .form-group label { font-size: 0.78rem; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .form-group input { padding: 0.5rem 0.7rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.9rem; }
        .form-group input:focus { outline: none; border-color: #d4a017; }
        .form-actions { display: flex; gap: 0.5rem; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-green { background: #d4edda; color: #155724; }
        .badge-red { background: #f8d7da; color: #721c24; }

        @media (max-width: 900px) {
            .charts { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">✂️ BeLoyal Admin</div>
    <div class="sidebar-user">
        <strong><?= htmlspecialchars($usuario['nombre']) ?></strong>
        Administrador
    </div>
    <nav>
        <a href="index.php?page=admin&seccion=dashboard" class="nav-link <?= $seccion === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="index.php?page=admin&seccion=servicios" class="nav-link <?= $seccion === 'servicios' ? 'active' : '' ?>">✂️ Servicios</a>
        <a href="index.php?page=admin&seccion=productos" class="nav-link <?= $seccion === 'productos' ? 'active' : '' ?>">🛍️ Productos</a>
    </nav>
    <div class="sidebar-logout">
        <a href="index.php?page=logout">← Cerrar sesión</a>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <?php if ($mensaje): ?>
    <div class="msg"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <?php if ($seccion === 'dashboard'): ?>
    <!-- ── DASHBOARD ──────────────────────────────────────────────────── -->
    <div class="page-title">📊 Dashboard</div>

    <div class="cards">
        <div class="card">
            <div class="card-label">Total ventas</div>
            <div class="card-value"><?= $resumen['total_ventas'] ?></div>
        </div>
        <div class="card">
            <div class="card-label">Ingresos totales</div>
            <div class="card-value gold">€<?= number_format($resumen['ingresos_totales'], 2) ?></div>
        </div>
        <div class="card">
            <div class="card-label">Servicios activos</div>
            <div class="card-value"><?= count($servicios) ?></div>
        </div>
        <div class="card">
            <div class="card-label">Productos activos</div>
            <div class="card-value"><?= count($productos) ?></div>
        </div>
    </div>

    <div class="charts">
        <div class="chart-box full">
            <h3>Ingresos últimos 30 días</h3>
            <canvas id="chartDias" height="80"></canvas>
        </div>
        <div class="chart-box">
            <h3>Ingresos por empleado</h3>
            <canvas id="chartEmpleados"></canvas>
        </div>
        <div class="chart-box">
            <h3>Servicios más vendidos</h3>
            <canvas id="chartServicios"></canvas>
        </div>
    </div>

    <div class="table-box">
        <h3>Ventas por empleado</h3>
        <table>
            <thead><tr><th>Empleado</th><th>Ventas</th><th>Ingresos</th></tr></thead>
            <tbody>
            <?php foreach ($porEmpleado as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['nombre']) ?></td>
                <td><?= $e['ventas'] ?></td>
                <td>€<?= number_format($e['ingresos'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($porEmpleado)): ?>
            <tr><td colspan="3" style="color:#aaa;text-align:center;padding:1rem">Sin datos todavía</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div class="table-box">
            <h3>Servicios más vendidos</h3>
            <table>
                <thead><tr><th>Servicio</th><th>Uds.</th><th>Ingresos</th></tr></thead>
                <tbody>
                <?php foreach ($svcVendidos as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['nombre']) ?></td>
                    <td><?= $s['unidades'] ?></td>
                    <td>€<?= number_format($s['ingresos'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($svcVendidos)): ?>
                <tr><td colspan="3" style="color:#aaa;text-align:center;padding:1rem">Sin datos todavía</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-box">
            <h3>Productos más vendidos</h3>
            <table>
                <thead><tr><th>Producto</th><th>Uds.</th><th>Ingresos</th></tr></thead>
                <tbody>
                <?php foreach ($prodVendidos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= $p['unidades'] ?></td>
                    <td>€<?= number_format($p['ingresos'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($prodVendidos)): ?>
                <tr><td colspan="3" style="color:#aaa;text-align:center;padding:1rem">Sin datos todavía</td></tr>
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
        data: {
            labels: diasLabels,
            datasets: [{ label: 'Ingresos €', data: diasData, borderColor: '#d4a017', backgroundColor: 'rgba(212,160,23,0.1)', tension: 0.3, fill: true }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    const empLabels = <?= json_encode(array_column($porEmpleado, 'nombre')) ?>;
    const empData   = <?= json_encode(array_map(fn($r) => (float)$r['ingresos'], $porEmpleado)) ?>;
    new Chart(document.getElementById('chartEmpleados'), {
        type: 'doughnut',
        data: {
            labels: empLabels,
            datasets: [{ data: empData, backgroundColor: ['#d4a017','#3498db','#27ae60','#e74c3c','#9b59b6','#f39c12','#1abc9c'] }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    const svcLabels = <?= json_encode(array_column($svcVendidos, 'nombre')) ?>;
    const svcData   = <?= json_encode(array_map(fn($r) => (int)$r['unidades'], $svcVendidos)) ?>;
    new Chart(document.getElementById('chartServicios'), {
        type: 'bar',
        data: {
            labels: svcLabels,
            datasets: [{ label: 'Unidades', data: svcData, backgroundColor: '#d4a017' }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
    </script>

    <?php elseif ($seccion === 'servicios'): ?>
    <!-- ── CRUD SERVICIOS ─────────────────────────────────────────────── -->
    <div class="page-title">✂️ Servicios</div>

    <div class="form-box">
        <h3><?= $editServicio ? 'Editar servicio' : 'Nuevo servicio' ?></h3>
        <form method="POST" action="index.php?page=<?= $editServicio ? 'admin_actualizar_servicio' : 'admin_crear_servicio' ?>">
            <?php if ($editServicio): ?>
            <input type="hidden" name="id" value="<?= $editServicio['id_servicio'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($editServicio['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Precio (€)</label>
                    <input type="number" name="precio" step="0.01" min="0" required value="<?= $editServicio['precio'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Duración (min)</label>
                    <input type="number" name="duracion" min="0" required value="<?= $editServicio['duracion'] ?? '' ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-gold"><?= $editServicio ? 'Guardar cambios' : '+ Añadir servicio' ?></button>
                <?php if ($editServicio): ?>
                <a href="index.php?page=admin&seccion=servicios" class="btn" style="background:#eee">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-box">
        <h3>Todos los servicios</h3>
        <table>
            <thead><tr><th>Nombre</th><th>Precio</th><th>Duración</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($servicios as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['nombre']) ?></td>
                <td>€<?= number_format($s['precio'], 2) ?></td>
                <td><?= $s['duracion'] ?> min</td>
                <td><span class="badge <?= $s['activo'] ? 'badge-green' : 'badge-red' ?>"><?= $s['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
                <td>
                    <a href="index.php?page=admin&seccion=servicios&editar=<?= $s['id_servicio'] ?>" class="btn btn-edit">Editar</a>
                    <a href="index.php?page=admin_eliminar_servicio&id=<?= $s['id_servicio'] ?>"
                       class="btn btn-danger"
                       onclick="return confirm('¿Eliminar este servicio?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($servicios)): ?>
            <tr><td colspan="5" style="color:#aaa;text-align:center;padding:1rem">No hay servicios</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php elseif ($seccion === 'productos'): ?>
    <!-- ── CRUD PRODUCTOS ─────────────────────────────────────────────── -->
    <div class="page-title">🛍️ Productos</div>

    <div class="form-box">
        <h3><?= $editProducto ? 'Editar producto' : 'Nuevo producto' ?></h3>
        <form method="POST" action="index.php?page=<?= $editProducto ? 'admin_actualizar_producto' : 'admin_crear_producto' ?>">
            <?php if ($editProducto): ?>
            <input type="hidden" name="id" value="<?= $editProducto['id_producto'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($editProducto['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Precio (€)</label>
                    <input type="number" name="precio" step="0.01" min="0" required value="<?= $editProducto['precio'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" min="0" required value="<?= $editProducto['stock'] ?? '' ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-gold"><?= $editProducto ? 'Guardar cambios' : '+ Añadir producto' ?></button>
                <?php if ($editProducto): ?>
                <a href="index.php?page=admin&seccion=productos" class="btn" style="background:#eee">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-box">
        <h3>Todos los productos</h3>
        <table>
            <thead><tr><th>Nombre</th><th>Precio</th><th>Stock</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($productos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td>€<?= number_format($p['precio'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td><span class="badge <?= $p['activo'] ? 'badge-green' : 'badge-red' ?>"><?= $p['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
                <td>
                    <a href="index.php?page=admin&seccion=productos&editar=<?= $p['id_producto'] ?>" class="btn btn-edit">Editar</a>
                    <a href="index.php?page=admin_eliminar_producto&id=<?= $p['id_producto'] ?>"
                       class="btn btn-danger"
                       onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($productos)): ?>
            <tr><td colspan="5" style="color:#aaa;text-align:center;padding:1rem">No hay productos</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>

</div>
</body>
</html>