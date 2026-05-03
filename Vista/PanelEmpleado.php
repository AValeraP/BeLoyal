<?php
function extraerPrecio(string $texto): float {
    return (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $texto));
}
$twJson = json_encode(
    array_map(fn($t) => ['id' => $t['id'], 'nombre' => $t['nombre'], 'rol' => $t['rol']], $trabajadores),
    JSON_UNESCAPED_UNICODE
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV Peluquería</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }

        .header { background: #222; color: #fff; padding: 0 1.5rem; height: 56px; display: flex; align-items: center; justify-content: space-between; }
        .header h1 { font-size: 1.1rem; }
        .header a { color: #ccc; font-size: 0.85rem; text-decoration: none; }
        .header a:hover { color: #fff; }

        .tabs { background: #333; display: flex; gap: 0.5rem; padding: 0.6rem 1.5rem; overflow-x: auto; }
        .tab-btn { background: transparent; border: 1px solid #555; color: #ccc; padding: 0.4rem 1.1rem; border-radius: 20px; cursor: pointer; font-size: 0.9rem; white-space: nowrap; position: relative; }
        .tab-btn:hover { border-color: #aaa; color: #fff; }
        .tab-btn.active { background: #d4a017; border-color: #d4a017; color: #000; font-weight: bold; }
        .tab-badge { display: none; position: absolute; top: -6px; right: -6px; background: red; color: #fff; border-radius: 50%; font-size: 0.7rem; width: 18px; height: 18px; line-height: 18px; text-align: center; }

        .main { display: grid; grid-template-columns: 1fr 320px; height: calc(100vh - 100px); }

        .servicios { overflow-y: auto; padding: 1.2rem; }
        .worker-panel { display: none; }
        .worker-panel.active { display: block; }
        .worker-title { font-size: 1.3rem; font-weight: bold; margin-bottom: 0.3rem; }
        .worker-rol { display: inline-block; background: #eee; color: #555; font-size: 0.78rem; padding: 0.2rem 0.6rem; border-radius: 20px; margin-bottom: 1.2rem; }
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
        .modal-btns { display: flex; gap: 0.6rem; }
        .btn-cancelar { flex: 1; padding: 0.7rem; border: 1px solid #ddd; background: transparent; border-radius: 8px; cursor: pointer; }
        .btn-confirmar { flex: 2; padding: 0.7rem; border: none; background: #27ae60; color: #fff; border-radius: 8px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h1>✂️ TPV Peluquería</h1>
    <span>👤 <?= htmlspecialchars($usuario['nombre']) ?> &nbsp;|&nbsp;
        <a href="index.php?page=logout">Cerrar sesión</a>
    </span>
</div>

<div class="tabs">
    <?php foreach ($trabajadores as $i => $t): ?>
    <button class="tab-btn <?= $i === 0 ? 'active' : '' ?>"
            id="tab-<?= $t['id'] ?>"
            onclick="seleccionarTrabajador('<?= $t['id'] ?>')">
        <?= htmlspecialchars($t['nombre']) ?>
        <span class="tab-badge" id="badge-<?= $t['id'] ?>">0</span>
    </button>
    <?php endforeach; ?>
</div>

<div class="main">

    <div class="servicios">
        <?php foreach ($trabajadores as $i => $t): ?>
        <div class="worker-panel <?= $i === 0 ? 'active' : '' ?>" id="panel-<?= $t['id'] ?>">

            <div class="worker-title"><?= htmlspecialchars($t['nombre']) ?></div>
            <span class="worker-rol"><?= htmlspecialchars($t['rol']) ?></span>

            <?php if ($t['bonos'] && !empty($bonos)): ?>
            <div class="seccion-titulo">🎟 Bonos</div>
            <div class="grid">
                <?php foreach ($bonos as $bi => $b): ?>
                <div class="card bono" onclick="anadir('bono_<?= $bi ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= $b['precio'] ?>)">
                    <div class="card-nombre"><?= htmlspecialchars($b['nombre']) ?></div>
                    <div class="card-precio">Precio a acordar</div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="seccion-titulo">☕ Bebidas</div>
            <div class="grid">
                <?php foreach ($bebidas as $b): ?>
                <div class="card bebida" onclick="anadir('beb_<?= $b['id'] ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= extraerPrecio($b['precio']) ?>)">
                    <div class="card-nombre"><?= htmlspecialchars($b['nombre']) ?></div>
                    <div class="card-precio"><?= htmlspecialchars($b['precio']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <div class="carrito">
        <div class="carrito-header">
            <h2>🛒 Carrito</h2>
            <div class="carrito-worker" id="carrito-nombre">-</div>
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

<div class="modal-overlay" id="modal">
    <div class="modal">
        <h2>Confirmar cobro</h2>
        <div class="modal-sub" id="modal-nombre"></div>
        <div class="modal-importe">
            <div class="modal-importe-label">Total</div>
            <div class="modal-importe-num" id="modal-total">€0,00</div>
        </div>
        <div class="modal-btns">
            <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-confirmar" onclick="confirmarCobro()">✓ Confirmar</button>
        </div>
    </div>
</div>

<script>
const trabajadores = <?php echo $twJson; ?>;
const carritos = {};
trabajadores.forEach(t => carritos[t.id] = {});
let activo = trabajadores[0].id;

function seleccionarTrabajador(id) {
    activo = id;
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    document.querySelectorAll('.worker-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + id).classList.add('active');
    const t = trabajadores.find(x => x.id === id);
    document.getElementById('carrito-nombre').textContent = t.nombre + ' · ' + t.rol;
    renderizar();
}

function anadir(itemId, nombre, precio) {
    if (!carritos[activo][itemId]) {
        carritos[activo][itemId] = { nombre: nombre, precio: parseFloat(precio) || 0, cantidad: 0 };
    }
    carritos[activo][itemId].cantidad++;
    renderizar();
    actualizarBadge(activo);
}

function quitar(itemId) {
    if (!carritos[activo][itemId]) return;
    carritos[activo][itemId].cantidad--;
    if (carritos[activo][itemId].cantidad <= 0) delete carritos[activo][itemId];
    renderizar();
    actualizarBadge(activo);
}

function limpiarCarrito() {
    carritos[activo] = {};
    renderizar();
    actualizarBadge(activo);
}

function renderizar() {
    const items = Object.entries(carritos[activo]);
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
            + '<div class="item-precio">' + (item.precio > 0 ? '€' + item.precio.toFixed(2) + ' c/u' : 'Precio a acordar') + '</div>'
            + '</div>'
            + '<div class="item-controles">'
            + '<button class="btn-qty" onclick="quitar(\'' + id + '\')">−</button>'
            + '<span class="item-qty">' + item.cantidad + '</span>'
            + '<button class="btn-qty" onclick="anadir(\'' + id + '\', \'' + item.nombre + '\', ' + item.precio + ')">+</button>'
            + '</div>'
            + '<div class="item-total">' + (item.precio > 0 ? '€' + sub.toFixed(2) : '-') + '</div>'
            + '</div>';
    });
    el.innerHTML = html;
    document.getElementById('total').textContent = '€' + total.toFixed(2);
}

function actualizarBadge(id) {
    const n = Object.values(carritos[id]).reduce((a, i) => a + i.cantidad, 0);
    const badge = document.getElementById('badge-' + id);
    badge.style.display = n > 0 ? 'inline-block' : 'none';
    badge.textContent = n;
}

function abrirModal() {
    const items = Object.values(carritos[activo]);
    if (!items.length) { alert('El carrito está vacío'); return; }
    const total = items.reduce((a, i) => a + i.precio * i.cantidad, 0);
    const t = trabajadores.find(x => x.id === activo);
    document.getElementById('modal-nombre').textContent = t.nombre + ' · ' + t.rol;
    document.getElementById('modal-total').textContent = '€' + total.toFixed(2);
    document.getElementById('modal').classList.add('open');
}

function cerrarModal() { document.getElementById('modal').classList.remove('open'); }

function confirmarCobro() {
    cerrarModal();
    limpiarCarrito();
}

document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

seleccionarTrabajador(trabajadores[0].id);
</script>
</body>
</html>
