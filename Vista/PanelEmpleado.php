<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV – <?= htmlspecialchars($usuario['nombre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 2px; }

        .bg-marble {
            background-color: #1a1a1a;
            background-image:
                radial-gradient(ellipse at 10% 20%, rgba(255,255,255,0.04) 0%, transparent 50%),
                radial-gradient(ellipse at 90% 80%, rgba(255,255,255,0.03) 0%, transparent 50%),
                radial-gradient(ellipse at 60% 40%, rgba(200,200,200,0.02) 0%, transparent 40%),
                repeating-linear-gradient(
                    115deg,
                    transparent 0px, transparent 20px,
                    rgba(255,255,255,0.015) 20px, rgba(255,255,255,0.015) 21px,
                    transparent 21px, transparent 45px,
                    rgba(255,255,255,0.008) 45px, rgba(255,255,255,0.008) 46px
                ),
                repeating-linear-gradient(
                    68deg,
                    transparent 0px, transparent 35px,
                    rgba(255,255,255,0.01) 35px, rgba(255,255,255,0.01) 36px
                );
        }
    </style>
</head>
<body class="bg-marble flex flex-col h-screen overflow-hidden text-white">

<!-- HEADER -->
<header class="bg-black/60 backdrop-blur-sm border-b border-white/10 h-14 flex items-center justify-between px-6 flex-shrink-0">
    <p class="text-sm font-semibold tracking-widest uppercase text-white">Be Loyal</p>
    <div class="flex items-center gap-4">
        <?php if (!empty($trabajador['logo'])): ?>
            <img src="public/img/logos/<?= htmlspecialchars($trabajador['logo']) ?>"
                 alt="<?= htmlspecialchars($trabajador['nombre']) ?>"
                 class="w-8 h-8 rounded-full object-cover ring-2 ring-white/20">
        <?php endif; ?>
        <span class="text-xs text-zinc-400"><?= htmlspecialchars($usuario['nombre']) ?></span>
        <a href="index.php?page=logout" class="text-xs text-zinc-500 hover:text-white transition">Cerrar sesión</a>
    </div>
</header>

<!-- WORKER BAR -->
<div class="bg-black/40 backdrop-blur-sm border-b border-white/10 px-6 py-3 flex-shrink-0 flex items-center gap-3">
    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
    <p class="text-sm font-medium text-white"><?= htmlspecialchars($usuario['nombre']) ?></p>
    <span class="text-xs text-zinc-500">·</span>
    <p class="text-xs text-zinc-400"><?= htmlspecialchars(ucfirst($especialidad)) ?></p>
</div>

<!-- MAIN -->
<div class="flex flex-1 overflow-hidden">

    <!-- SERVICIOS -->
    <div class="flex-1 overflow-y-auto p-6">

        <!-- Servicios propios -->
        <p class="text-xs font-medium uppercase tracking-widest text-zinc-400 mb-4"><?= $seccionTitulo ?></p>
        <div class="grid grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-8">
            <?php foreach ($servicios as $s): ?>
            <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 cursor-pointer hover:bg-zinc-800/90 hover:border-white/20 transition-all duration-200 select-none active:scale-95 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/40 flex flex-col justify-between min-h-[100px]"
                 onclick="anadir('svc_<?= $s['id'] ?>', '<?= htmlspecialchars($s['nombre'], ENT_QUOTES) ?>', <?= $s['precio'] ?>, 'servicio', <?= $s['id'] ?>)">
                <p class="text-sm font-semibold text-white mb-3 leading-tight"><?= htmlspecialchars($s['nombre']) ?></p>
                <p class="text-lg font-light text-zinc-300">€<?= number_format($s['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Bonos -->
        <?php if ($mostrarBonos && !empty($bonos)): ?>
        <p class="text-xs font-medium uppercase tracking-widest text-zinc-400 mb-4">Bonos</p>
        <div class="grid grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-8">
            <?php foreach ($bonos as $b): ?>
            <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 cursor-pointer hover:bg-zinc-800/90 hover:border-white/20 transition-all duration-200 select-none active:scale-95 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/40 flex flex-col justify-between min-h-[100px]"
                 onclick="anadir('svc_<?= $b['id'] ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= $b['precio'] ?>, 'servicio', <?= $b['id'] ?>)">
                <p class="text-xs text-zinc-500 mb-1">🎟 Bono</p>
                <p class="text-sm font-semibold text-white mb-3 leading-tight"><?= htmlspecialchars($b['nombre']) ?></p>
                <p class="text-lg font-light text-zinc-300">€<?= number_format($b['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Bebidas -->
        <p class="text-xs font-medium uppercase tracking-widest text-zinc-400 mb-4">Bebidas</p>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] auto-rows-fr gap-3">
            <?php foreach ($bebidas as $b): ?>
            <div class="bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 cursor-pointer hover:bg-zinc-800/90 hover:border-white/20 transition-all duration-200 select-none active:scale-95 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/40 flex flex-col justify-between min-h-[100px]"
                 onclick="anadir('beb_<?= $b['id'] ?>', '<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>', <?= $b['precio'] ?>, 'producto', <?= $b['id'] ?>)">
                <p class="text-sm font-semibold text-white mb-3 leading-tight"><?= htmlspecialchars($b['nombre']) ?></p>
                <p class="text-lg font-light text-zinc-300">€<?= number_format($b['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- CARRITO -->
    <div class="w-72 bg-black/50 backdrop-blur-sm border-l border-white/10 flex flex-col flex-shrink-0">

        <div class="px-5 py-4 border-b border-white/10">
            <p class="text-sm font-medium text-white">Carrito</p>
            <p class="text-xs text-zinc-500 mt-0.5"><?= htmlspecialchars($usuario['nombre']) ?></p>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-3" id="carrito-items">
            <div class="h-full flex items-center justify-center text-zinc-600 text-xs">
                Carrito vacío
            </div>
        </div>

        <div class="px-5 py-4 border-t border-white/10">
            <div class="flex justify-between items-baseline mb-4">
                <span class="text-xs text-zinc-500">Total</span>
                <span class="text-2xl font-light text-white" id="total">€0,00</span>
            </div>
            <button onclick="abrirModal()" class="w-full bg-white text-zinc-900 text-sm font-semibold py-3 rounded-xl hover:bg-zinc-100 transition mb-2">
                Cobrar
            </button>
            <button onclick="limpiarCarrito()" class="w-full border border-white/10 text-zinc-600 text-xs py-2 rounded-xl hover:text-red-400 hover:border-red-900/50 transition">
                Limpiar
            </button>
        </div>

    </div>

</div>

<!-- MODAL -->
<div id="modal" style="display:none;" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-zinc-900 border border-white/10 rounded-2xl p-7 w-80 max-w-full mx-4 shadow-2xl">
        <p class="text-base font-semibold text-white mb-0.5">Confirmar cobro</p>
        <p class="text-xs text-zinc-500 mb-6"><?= htmlspecialchars($usuario['nombre']) ?></p>

        <div class="bg-zinc-800/80 border border-white/10 rounded-xl p-5 text-center mb-6">
            <p class="text-xs uppercase tracking-widest text-zinc-500 mb-2">Total</p>
            <p class="text-4xl font-light text-white" id="modal-total">€0,00</p>
        </div>

        <!-- Método de pago — oculto temporalmente -->
        <div style="display:none;" id="metodo-pago" class="flex gap-2 mb-5">
            <button onclick="seleccionarMetodo('efectivo')" id="btn-efectivo" class="flex-1 border border-zinc-700 text-xs text-zinc-400 py-2 rounded-lg hover:border-white hover:text-white transition">Efectivo</button>
            <button onclick="seleccionarMetodo('tarjeta')"  id="btn-tarjeta"  class="flex-1 border border-zinc-700 text-xs text-zinc-400 py-2 rounded-lg hover:border-white hover:text-white transition">Tarjeta</button>
            <button onclick="seleccionarMetodo('bizum')"   id="btn-bizum"    class="flex-1 border border-zinc-700 text-xs text-zinc-400 py-2 rounded-lg hover:border-white hover:text-white transition">Bizum</button>
        </div>

        <div class="flex gap-3">
            <button onclick="cerrarModal()" class="flex-1 border border-white/10 text-xs text-zinc-400 py-3 rounded-xl hover:text-white hover:border-white/30 transition">
                Cancelar
            </button>
            <button onclick="confirmarCobro()" id="btn-confirmar" class="flex-1 bg-white text-zinc-900 text-xs font-semibold py-3 rounded-xl hover:bg-zinc-100 transition disabled:opacity-30">
                ✓ Confirmar
            </button>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="toast" style="display:none;" class="fixed bottom-5 right-5 text-xs font-medium px-4 py-3 rounded-xl z-50 shadow-lg"></div>

<script>
const carrito = {};
let metodoPago = 'efectivo';

function seleccionarMetodo(metodo) {
    metodoPago = metodo;
    ['efectivo', 'tarjeta', 'bizum'].forEach(m => {
        const btn = document.getElementById('btn-' + m);
        if (m === metodo) {
            btn.classList.add('border-white', 'text-white');
            btn.classList.remove('border-zinc-700', 'text-zinc-400');
        } else {
            btn.classList.remove('border-white', 'text-white');
            btn.classList.add('border-zinc-700', 'text-zinc-400');
        }
    });
}

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
        el.innerHTML = '<div class="h-full flex items-center justify-center text-zinc-600 text-xs">Carrito vacío</div>';
        document.getElementById('total').textContent = '€0,00';
        return;
    }
    let total = 0, html = '';
    items.forEach(([id, item]) => {
        const sub = item.precio * item.cantidad;
        total += sub;
        html += `<div class="flex items-center gap-2 py-3 border-b border-white/5 last:border-0">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-white truncate">${item.nombre}</p>
                <p class="text-xs text-zinc-500 mt-0.5">€${item.precio.toFixed(2)} c/u</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="quitar('${id}')" class="w-6 h-6 rounded-full border border-white/10 text-zinc-400 hover:border-white/30 hover:text-white flex items-center justify-center transition text-sm">−</button>
                <span class="text-xs font-medium text-white min-w-[16px] text-center">${item.cantidad}</span>
                <button onclick="anadir('${id}', '${item.nombre.replace(/'/g,"\\'")}', ${item.precio}, '${item.tipo}', ${item.idReal})" class="w-6 h-6 rounded-full border border-white/10 text-zinc-400 hover:border-white/30 hover:text-white flex items-center justify-center transition text-sm">+</button>
            </div>
            <span class="text-xs font-medium text-white min-w-[40px] text-right">€${sub.toFixed(2)}</span>
        </div>`;
    });
    el.innerHTML = html;
    document.getElementById('total').textContent = '€' + total.toFixed(2);
}

function abrirModal() {
    const items = Object.values(carrito);
    if (!items.length) { alert('El carrito está vacío'); return; }
    const total = items.reduce((a, i) => a + i.precio * i.cantidad, 0);
    document.getElementById('modal-total').textContent = '€' + total.toFixed(2);
    document.getElementById('modal').style.display = 'flex';
}

function cerrarModal() { document.getElementById('modal').style.display = 'none'; }

function mostrarToast(msg, error = false) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = `fixed bottom-5 right-5 text-xs font-medium px-4 py-3 rounded-xl z-50 shadow-lg ${error ? 'bg-red-900 text-red-200 border border-red-800' : 'bg-emerald-900 text-emerald-200 border border-emerald-800'}`;
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
            body: JSON.stringify({ items, total: total.toFixed(2), metodo_pago: metodoPago }),
        });
        const data = await res.json();
        if (data.ok) { cerrarModal(); limpiarCarrito(); mostrarToast('Venta registrada correctamente'); }
        else mostrarToast('Error: ' + data.error, true);
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