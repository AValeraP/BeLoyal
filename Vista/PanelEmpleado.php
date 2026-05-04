<?php
$twJson = json_encode(
    ['id' => $usuario['id'], 'nombre' => $usuario['nombre'], 'rol' => $usuario['rol']],
    JSON_UNESCAPED_UNICODE
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV – <?= htmlspecialchars($usuario['nombre']) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }

        .header { background: #222; color: #fff; padding: 0 1.5rem; height: 56px; display: flex; align-items: center; justify-content: space-between; }
        .header h1 { font-size: 1.1rem; }
        .header-user { display: flex; align-items: center; gap: 1rem; font-size: 0.85rem; color: #ccc; }
        .header a { color: #ccc; text-decoration: none; }
        .header a:hover { color: #fff; }

        .worker-bar { background: #333; padding: 0.8rem 1.5rem; border-bottom: 2px solid #d4a017; }
        .worker-bar h2 { color: #d4a017; font-size: 1rem; }
        .worker-bar span { color: #999; font-size: 0.85rem; }

        .main { display: grid; grid-template-columns: 1fr 320px; height: calc(100vh - 100px); }

        .servicios { overflow-y: auto; padding: 1.2rem; }
        .seccion-titulo { font-size: 0.78rem; font-weight: bold; text-transform: uppercase; color: #888; letter-spacing: 0.08em; border-bottom: 1px solid #ddd; padding-bottom: 0.3rem; margin-bottom: 0.7rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.6rem; margin-bottom: 1.5rem; }

        .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 0.8rem; cursor: pointer; transition: box-shadow 0.15s, border-color 0.15s; user-select: none; }
        .card:hover { border-color: #d4a017; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .card:active { transform: scale(0.97); }
        .card-nombre { font-weight: bold; font-size: 0.88rem; margin-bottom: 0.25rem; }
        .card-precio { color: #d4a017; font-weight: bold; font-size: 0.95rem; }
        .card.bono { background: #2a2a2a; border-color: #d4a017; }
        .card.bono .card-nombre { color: #f0d080; }
        .card.bono .card-precio { color: #d4a017; }
        .card.bebida { border-left: 3px solid #4a90d9; }
        .card.bebida .card-precio { color: #4a90d9; }

        .carrito { background: #fff; border-left: 1px solid #ddd; display: flex; flex-direction: column; height: 100%; }
        .carrito-header { padding: 1rem; border-bottom: 1px solid #eee; background: #fafafa; }
        .carrito-header h2 { font-size: 1rem; }
        .carrito-worker { font-size: 0.82rem; color: #888; margin-top: 0.2rem; }
        .carrito-items { flex: 1; overflow-y: auto; padding: 0.8rem 1rem; }
        .carrito-vacio { height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #aaa; font-size: 0.9rem; gap: 0.4rem; }

        .item { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0; }
        .item:last-child { border-bottom: none; }
        .item-info { flex: 1; }
        .item-nombre { font-size: 0.87rem; font-weight: 600; }
        .item-precio { font-size: 0.8rem; color: #aaa; }
        .item-controles { display: flex; align-items: center; gap: 0.3rem; }
        .btn-qty { width: 24px; height: 24px; border-radius: 50%; border: 1px solid #ddd; background: #f5f5f5; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; }
        .btn-qty:hover { background: #d4a017; border-color: #d4a017; color: #fff; }
        .item-qty { font-weight: bold; min-width: 18px; text-align: center; font-size: 0.9rem; }
        .item-total { font-weight: bold; font-size: 0.88rem; min-width: 46px; text-align: right; }

        .carrito-footer { padding: 1rem; border-top: 2px solid #eee; background: #fafafa; }
        .total-row { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.8rem; }
        .total-label { font-size: 1rem; font-weight: bold; }
        .total-importe { font-size: 1.6rem; font-weight: bold; }
        .btn-cobrar { width: 100%; background: #d4a017; color: #000; border: none; padding: 0.8rem; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; margin-bottom: 0.4rem; }
        .btn-cobrar:hover { background: #b8880f; }
        .btn-limpiar { width: 100%; background: transparent; border: 1px solid #ddd; color: #999; padding: 0.4rem; border-radius: 6px; font-size: 0.82rem; cursor: pointer; }
        .btn-limpiar:hover { border-color: #c00; color: #c00; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 12px; padding: 1.8rem; width: 360px; max-width: 95vw; }
        .modal h2 { font-size: 1.2rem; margin-bottom: 0.3rem; }
        .modal-sub { color: #888; font-size: 0.85rem; margin-bottom: 1.2rem; }
        .modal-importe { background: #f5f5f5; border-radius: 8px; padding: 1rem; text-align: center; margin-bottom: 1.2rem; }
        .modal-importe-label { font-size: 0.8rem; color: #888; text-transform: uppercase; }
        .modal-importe-num { font-size: 2.2rem; font-weight: bold; color: #d4a017; }
        /* .modal-metodo oculto temporalmente, se habilitará más adelante */
        .modal-metodo { display: none; gap: 0.5rem; margin-bottom: 1.2rem; }
        .btn-metodo { flex: 1; padding: 0.6rem; border: 2px solid #ddd; background: #fff; border-radius: 8px; cursor: pointer; font-size: 0.88rem; font-weight: 600; transition: all 0.15s; }
        .btn-metodo.active { border-color: #d4a017; background: #d4a017; color: #000; }
        .modal-btns { display: flex; gap: 0.6rem; }
        .btn-cancelar { flex: 1; padding: 0.7rem; border: 1px solid #ddd; background: transparent; border-radius: 8px; cursor: pointer; }
        .btn-confirmar { flex: 2; padding: 0.7rem; border: none; background: #27ae60; color: #fff; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .btn-confirmar:disabled { background: #aaa; cursor: not-allowed; }
        .toast { position: fixed; bottom: 1.5rem; right: 1.5rem; background: #27ae60; color: #fff; padding: 0.8rem 1.4rem; border-radius: 8px; font-weight: bold; font-size: 0.9rem; z-index: 200; display: none; }
        .toast.error { background: #c0392b; }
    </style>
</head>
<body>

<div class="header">
    <h1>✂️ TPV Peluquería</h1>
    <div class="header-user">
        <span>👤 <?= htmlspecialchars($usuario['nombre']) ?></span>
        <a href="index.php?page=logout">Cerrar sesión</a>
    </div>
</div>

<div class="worker-bar">
    <h2><?= htmlspecialchars($usuario['nombre']) ?></h2>
    <span><?= htmlspecialchars(ucfirst($especialidad)) ?></span>
</div>

<div class="main">

    <div class="servicios">

        <!-- Servicios propios -->
        <div class="seccion-titulo"><?= $seccionTitulo ?></div>
        <div class="grid">
            <?php foreach ($servicios as $s): ?>
            <div class="card" onclick="anadir('svc_<?= $s['id'] ?>', '<?= htmlspecialchars($s['nombre'], ENT_QUOTES) ?>', <?= $s['precio'] ?>, 'servicio', <?= $s['id'] ?>)">
                <div class="card-nombre"><?= htmlspecialchars($s['nombre']) ?></div>
                <div class="card-precio">€<?= $s['precio'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Bonos solo para peluqueros -->
        <?php if ($mostrarBonos && !empty($bonos)): ?>
        <div class="seccion-titulo">🎟 Bonos</div>
        <div class="grid">
            <?php foreach ($bonos as $b): ?>
            <div class="card bono" onclick="anadir('svc_<?= $b['id'] ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= $b['precio'] ?>, 'servicio', <?= $b['id'] ?>)">
                <div class="card-nombre"><?= htmlspecialchars($b['nombre']) ?></div>
                <div class="card-precio">€<?= $b['precio'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Bebidas para todos -->
        <div class="seccion-titulo">☕ Bebidas</div>
        <div class="grid">
            <?php foreach ($bebidas as $b): ?>
            <div class="card bebida" onclick="anadir('beb_<?= $b['id'] ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= $b['precio'] ?>, 'producto', <?= $b['id'] ?>)">
                <div class="card-nombre"><?= htmlspecialchars($b['nombre']) ?></div>
                <div class="card-precio">€<?= $b['precio'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="carrito">
        <div class="carrito-header">
            <h2>🛒 Carrito</h2>
            <div class="carrito-worker"><?= htmlspecialchars($usuario['nombre']) ?></div>
        </div>
        <div class="carrito-items" id="carrito-items">
            <div class="carrito-vacio">
                <span style="font-size:2rem">🛒</span>
                <span>Carrito vacío</span>
            </div>
        </div>
        <div class="carrito-footer">
            <div class="total-row">
                <span class="total-label">Total</span>
                <span class="total-importe" id="total">€0,00</span>
            </div>
            <button class="btn-cobrar" onclick="abrirModal()">Cobrar</button>
            <button class="btn-limpiar" onclick="limpiarCarrito()">✕ Limpiar</button>
        </div>
    </div>

</div>

<!-- MODAL COBRO -->
<div class="modal-overlay" id="modal">
    <div class="modal">
        <h2>Confirmar cobro</h2>
        <div class="modal-sub"><?= htmlspecialchars($usuario['nombre']) ?></div>
        <div class="modal-importe">
            <div class="modal-importe-label">Total</div>
            <div class="modal-importe-num" id="modal-total">€0,00</div>
        </div>
        <div class="modal-metodo">
            <button class="btn-metodo active" id="btn-efectivo" onclick="seleccionarMetodo('efectivo')">💵 Efectivo</button>
            <button class="btn-metodo" id="btn-tarjeta" onclick="seleccionarMetodo('tarjeta')">💳 Tarjeta</button>
            <button class="btn-metodo" id="btn-bizum" onclick="seleccionarMetodo('bizum')">📱 Bizum</button>
        </div>
        <div class="modal-btns">
            <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-confirmar" id="btn-confirmar" onclick="confirmarCobro()">✓ Confirmar</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
const carrito = {};
let metodoPago = 'efectivo';

function seleccionarMetodo(metodo) {
    metodoPago = metodo;
    ['efectivo', 'tarjeta', 'bizum'].forEach(m => {
        document.getElementById('btn-' + m).classList.toggle('active', m === metodo);
    });
}

function anadir(itemId, nombre, precio, tipo, idReal) {
    if (!carrito[itemId]) {
        carrito[itemId] = { nombre, precio: parseFloat(precio) || 0, cantidad: 0, tipo, idReal };
    }
    carrito[itemId].cantidad++;
    renderizar();
}

function quitar(itemId) {
    if (!carrito[itemId]) return;
    carrito[itemId].cantidad--;
    if (carrito[itemId].cantidad <= 0) delete carrito[itemId];
    renderizar();
}

function limpiarCarrito() {
    Object.keys(carrito).forEach(k => delete carrito[k]);
    renderizar();
}

function renderizar() {
    const items = Object.entries(carrito);
    const el = document.getElementById('carrito-items');
    if (!items.length) {
        el.innerHTML = '<div class="carrito-vacio"><span style="font-size:2rem">🛒</span><span>Carrito vacío</span></div>';
        document.getElementById('total').textContent = '€0,00';
        return;
    }
    let total = 0, html = '';
    items.forEach(([id, item]) => {
        const sub = item.precio * item.cantidad;
        total += sub;
        html += '<div class="item">'
            + '<div class="item-info">'
            + '<div class="item-nombre">' + item.nombre + '</div>'
            + '<div class="item-precio">€' + item.precio.toFixed(2) + ' c/u</div>'
            + '</div>'
            + '<div class="item-controles">'
            + '<button class="btn-qty" onclick="quitar(\'' + id + '\')">−</button>'
            + '<span class="item-qty">' + item.cantidad + '</span>'
            + '<button class="btn-qty" onclick="anadir(\'' + id + '\', \'' + item.nombre.replace(/'/g, "\\'") + '\', ' + item.precio + ', \'' + item.tipo + '\', ' + item.idReal + ')">+</button>'
            + '</div>'
            + '<div class="item-total">€' + sub.toFixed(2) + '</div>'
            + '</div>';
    });
    el.innerHTML = html;
    document.getElementById('total').textContent = '€' + total.toFixed(2);
}

function abrirModal() {
    const items = Object.values(carrito);
    if (!items.length) { alert('El carrito está vacío'); return; }
    const total = items.reduce((a, i) => a + i.precio * i.cantidad, 0);
    document.getElementById('modal-total').textContent = '€' + total.toFixed(2);
    seleccionarMetodo('efectivo');
    document.getElementById('modal').classList.add('open');
}

function cerrarModal() { document.getElementById('modal').classList.remove('open'); }

function mostrarToast(msg, error = false) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast' + (error ? ' error' : '');
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3000);
}

async function confirmarCobro() {
    const btn = document.getElementById('btn-confirmar');
    btn.disabled = true;

    const items = Object.values(carrito).map(i => ({
        tipo:     i.tipo,
        id:       i.idReal,
        cantidad: i.cantidad,
        precio:   i.precio,
    }));
    const total = items.reduce((a, i) => a + i.precio * i.cantidad, 0);

    try {
        const res = await fetch('index.php?page=registrar_venta', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items, total: total.toFixed(2), metodo_pago: metodoPago }),
        });
        const data = await res.json();

        if (data.ok) {
            cerrarModal();
            limpiarCarrito();
            mostrarToast('✓ Venta registrada correctamente');
        } else {
            mostrarToast('Error: ' + data.error, true);
        }
    } catch (e) {
        mostrarToast('Error de conexión', true);
    }

    btn.disabled = false;
}

document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
</body>
</html>