<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV – <?= htmlspecialchars($usuario['nombre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-zinc-50 flex flex-col h-screen overflow-hidden text-zinc-800">

<!-- HEADER -->
<header class="bg-white border-b border-zinc-100 h-12 flex items-center justify-between px-6 flex-shrink-0">
    <p class="text-xs font-light tracking-widest uppercase text-zinc-900">Be Loyal</p>
    <div class="flex items-center gap-5 text-xs font-light text-zinc-400">
        <span><?= htmlspecialchars($usuario['nombre']) ?></span>
        <a href="index.php?page=logout" class="hover:text-zinc-600 transition">Cerrar sesion</a>
    </div>
</header>

<!-- WORKER BAR -->
<div class="bg-white border-b border-zinc-100 px-6 py-2.5 flex-shrink-0">
    <p class="text-xs font-normal text-zinc-700"><?= htmlspecialchars($usuario['nombre']) ?></p>
    <p class="text-xs font-light text-zinc-300 mt-0.5"><?= htmlspecialchars(ucfirst($especialidad)) ?></p>
</div>

<!-- MAIN -->
<div class="flex flex-1 overflow-hidden">

    <!-- SERVICIOS -->
    <div class="flex-1 overflow-y-auto p-5">

        <p class="text-xs font-light uppercase tracking-widest text-zinc-300 border-b border-zinc-100 pb-2 mb-3"><?= $seccionTitulo ?></p>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))] gap-2 mb-6">
            <?php foreach ($servicios as $s): ?>
            <div class="bg-white border border-zinc-100 rounded-lg p-3 cursor-pointer hover:border-zinc-300 transition-colors select-none active:scale-95"
                 onclick="anadir('svc_<?= $s['id'] ?>', '<?= htmlspecialchars($s['nombre'], ENT_QUOTES) ?>', <?= $s['precio'] ?>, 'servicio', <?= $s['id'] ?>)">
                <p class="text-xs font-normal text-zinc-700 mb-1"><?= htmlspecialchars($s['nombre']) ?></p>
                <p class="text-xs font-light text-zinc-400">€<?= $s['precio'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($mostrarBonos && !empty($bonos)): ?>
        <p class="text-xs font-light uppercase tracking-widest text-zinc-300 border-b border-zinc-100 pb-2 mb-3">Bonos</p>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))] gap-2 mb-6">
            <?php foreach ($bonos as $b): ?>
            <div class="bg-white border border-zinc-200 rounded-lg p-3 cursor-pointer hover:border-zinc-400 transition-colors select-none active:scale-95"
                 onclick="anadir('svc_<?= $b['id'] ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= $b['precio'] ?>, 'servicio', <?= $b['id'] ?>)">
                <p class="text-xs font-normal text-zinc-700 mb-1"><?= htmlspecialchars($b['nombre']) ?></p>
                <p class="text-xs font-light text-zinc-400">€<?= $b['precio'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p class="text-xs font-light uppercase tracking-widest text-zinc-300 border-b border-zinc-100 pb-2 mb-3">Bebidas</p>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))] gap-2">
            <?php foreach ($bebidas as $b): ?>
            <div class="bg-white border border-zinc-100 rounded-lg p-3 cursor-pointer hover:border-zinc-300 transition-colors select-none active:scale-95"
                 onclick="anadir('beb_<?= $b['id'] ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= $b['precio'] ?>, 'producto', <?= $b['id'] ?>)">
                <p class="text-xs font-normal text-zinc-700 mb-1"><?= htmlspecialchars($b['nombre']) ?></p>
                <p class="text-xs font-light text-zinc-400">€<?= $b['precio'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- CARRITO -->
    <div class="w-72 bg-white border-l border-zinc-100 flex flex-col flex-shrink-0">

        <div class="px-5 py-4 border-b border-zinc-100">
            <p class="text-xs font-light text-zinc-700">Carrito</p>
            <p class="text-xs font-light text-zinc-300 mt-0.5"><?= htmlspecialchars($usuario['nombre']) ?></p>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-3" id="carrito-items">
            <div class="h-full flex items-center justify-center text-zinc-300 text-xs font-light">
                Carrito vacio
            </div>
        </div>

        <div class="px-5 py-4 border-t border-zinc-100">
            <div class="flex justify-between items-baseline mb-4">
                <span class="text-xs font-light text-zinc-400">Total</span>
                <span class="text-xl font-light text-zinc-800" id="total">€0,00</span>
            </div>
            <button onclick="abrirModal()" class="w-full bg-zinc-900 text-white text-xs font-light py-2.5 rounded-lg hover:bg-zinc-700 transition mb-2">
                Cobrar
            </button>
            <button onclick="limpiarCarrito()" class="w-full border border-zinc-100 text-zinc-300 text-xs font-light py-2 rounded-lg hover:text-red-400 hover:border-red-100 transition">
                Limpiar
            </button>
        </div>

    </div>

</div>

<!-- MODAL -->
<div id="modal" style="display:none;" class="fixed inset-0 bg-black/20 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl border border-zinc-100 p-7 w-80 max-w-full mx-4">
        <p class="text-sm font-light text-zinc-700 mb-0.5">Confirmar cobro</p>
        <p class="text-xs font-light text-zinc-300 mb-5"><?= htmlspecialchars($usuario['nombre']) ?></p>

        <div class="bg-zinc-50 border border-zinc-100 rounded-lg p-4 text-center mb-5">
            <p class="text-xs font-light uppercase tracking-widest text-zinc-300 mb-1">Total</p>
            <p class="text-3xl font-light text-zinc-800" id="modal-total">€0,00</p>
        </div>

        <div class="flex gap-2">
            <button onclick="cerrarModal()" class="flex-1 border border-zinc-100 text-xs font-light text-zinc-400 py-2.5 rounded-lg hover:text-zinc-600 transition">
                Cancelar
            </button>
            <button onclick="confirmarCobro()" id="btn-confirmar" class="flex-1 bg-zinc-900 text-white text-xs font-light py-2.5 rounded-lg hover:bg-zinc-700 transition disabled:opacity-40">
                Confirmar
            </button>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="toast" style="display:none;" class="fixed bottom-5 right-5 text-xs font-light px-4 py-2.5 rounded-lg z-50"></div>

<script>
const carrito = {};

function anadir(itemId, nombre, precio, tipo, idReal) {
    if (!carrito[itemId]) carrito[itemId] = { nombre, precio: parseFloat(precio) || 0, cantidad: 0, tipo, idReal };
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
        el.innerHTML = '<div class="h-full flex items-center justify-center text-zinc-300 text-xs font-light">Carrito vacio</div>';
        document.getElementById('total').textContent = '€0,00';
        return;
    }
    let total = 0, html = '';
    items.forEach(([id, item]) => {
        const sub = item.precio * item.cantidad;
        total += sub;
        html += `<div class="flex items-center gap-2 py-2.5 border-b border-zinc-50 last:border-0">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-light text-zinc-600 truncate">${item.nombre}</p>
                <p class="text-xs font-light text-zinc-300">€${item.precio.toFixed(2)} c/u</p>
            </div>
            <div class="flex items-center gap-1.5">
                <button onclick="quitar('${id}')" class="w-5 h-5 rounded-full border border-zinc-100 text-zinc-400 text-xs hover:border-zinc-300 flex items-center justify-center leading-none">-</button>
                <span class="text-xs font-light text-zinc-600 min-w-[14px] text-center">${item.cantidad}</span>
                <button onclick="anadir('${id}', '${item.nombre.replace(/'/g,"\\'")}', ${item.precio}, '${item.tipo}', ${item.idReal})" class="w-5 h-5 rounded-full border border-zinc-100 text-zinc-400 text-xs hover:border-zinc-300 flex items-center justify-center leading-none">+</button>
            </div>
            <span class="text-xs font-light text-zinc-500 min-w-[36px] text-right">€${sub.toFixed(2)}</span>
        </div>`;
    });
    el.innerHTML = html;
    document.getElementById('total').textContent = '€' + total.toFixed(2);
}

function abrirModal() {
    const items = Object.values(carrito);
    if (!items.length) { alert('El carrito esta vacio'); return; }
    const total = items.reduce((a, i) => a + i.precio * i.cantidad, 0);
    document.getElementById('modal-total').textContent = '€' + total.toFixed(2);
    document.getElementById('modal').style.display = 'flex';
}

function cerrarModal() { document.getElementById('modal').style.display = 'none'; }

function mostrarToast(msg, error = false) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = `fixed bottom-5 right-5 text-xs font-light px-4 py-2.5 rounded-lg z-50 ${error ? 'bg-red-50 text-red-500 border border-red-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100'}`;
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3000);
}

async function confirmarCobro() {
    const btn = document.getElementById('btn-confirmar');
    btn.disabled = true;
    const items = Object.values(carrito).map(i => ({ tipo: i.tipo, id: i.idReal, cantidad: i.cantidad, precio: i.precio }));
    const total = items.reduce((a, i) => a + i.precio * i.cantidad, 0);
    try {
        const res = await fetch('index.php?page=registrar_venta', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items, total: total.toFixed(2), metodo_pago: 'efectivo' }),
        });
        const data = await res.json();
        if (data.ok) { cerrarModal(); limpiarCarrito(); mostrarToast('Venta registrada correctamente'); }
        else mostrarToast('Error: ' + data.error, true);
    } catch (e) {
        mostrarToast('Error de conexion', true);
    }
    btn.disabled = false;
}

document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>

</body>
</html>