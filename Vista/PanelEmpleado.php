<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV – <?= htmlspecialchars($usuario['nombre']) ?></title>
    <?php include __DIR__ . '/partials/tema-head.php'; ?>
    <style>

        /* Carrito móvil */
        @media (max-width: 767px) {
            #carrito-panel { position: fixed; top: 0; right: 0; bottom: 0; width: 88%; max-width: 320px; transform: translateX(100%); transition: transform .25s ease; z-index: 60; }
            #carrito-panel.open { transform: translateX(0); }
            #carrito-backdrop { display: none; }
            #carrito-backdrop.open { display: block; }
        }
    </style>
</head>
<body class="bl-body font-inter flex flex-col h-screen overflow-hidden text-[#e5e5e5]">

<?php include __DIR__ . '/partials/tema-fondo.php'; ?>

<!-- HEADER -->
<header class="relative z-10 bg-black/60 backdrop-blur-md border-b border-[#2a2a2a] h-16 flex items-center justify-between px-4 sm:px-6 flex-shrink-0">
    <div class="flex items-center gap-3">
        <img src="public/img/logos/beloyal-logo.svg" alt="Be Loyal Barber" class="logo-glow h-11 w-auto select-none" draggable="false">
        <p class="glow-title hidden sm:block text-sm font-medium tracking-[0.2em] uppercase text-[#f5f5f5]">Be Loyal</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="glow-text text-xs text-[#b5b5b5]"><?= htmlspecialchars($trabajador['nombre']) ?></span>
        <?php if (!empty($trabajador['logo'])): ?>
        <img src="public/img/logos/<?= htmlspecialchars($trabajador['logo']) ?>"
             alt="<?= htmlspecialchars($trabajador['nombre']) ?>"
             class="w-8 h-8 rounded-full object-cover border border-[#404040] shadow-[0_0_10px_rgba(255,255,255,0.12)]"
             onerror="this.style.display='none'">
        <?php else: ?>
        <div class="w-8 h-8 rounded-full bg-[#262626] border border-[#404040] flex items-center justify-center text-xs font-semibold text-[#e5e5e5]">
            <?= mb_strtoupper(mb_substr($trabajador['nombre'], 0, 1)) ?>
        </div>
        <?php endif; ?>
        <a href="index.php?page=logout" class="text-xs text-[#8a8a8a] hover:text-white transition">Cerrar sesión</a>
    </div>
</header>

<!-- WORKER BAR -->
<div class="relative z-10 bg-black/40 backdrop-blur-sm border-b border-[#2a2a2a] px-4 sm:px-6 py-3 flex-shrink-0 flex items-center gap-3">
    <div class="w-2 h-2 rounded-full bg-[#e5e5e5] shadow-[0_0_8px_rgba(255,255,255,0.7)]"></div>
    <p class="glow-title text-sm font-medium text-[#f5f5f5]"><?= htmlspecialchars($trabajador['nombre']) ?></p>
    <span class="text-xs text-[#525252]">·</span>
    <p class="text-xs text-[#a3a3a3]"><?= htmlspecialchars(ucfirst($especialidad)) ?></p>
</div>

<!-- MAIN -->
<div class="relative flex flex-1 overflow-hidden">

    <!-- SERVICIOS -->
    <div class="flex-1 overflow-y-auto p-4 sm:p-6">

        <p class="glow-text text-xs font-medium uppercase tracking-widest text-[#b5b5b5] mb-4"><?= htmlspecialchars($seccionTitulo) ?></p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-8">
            <?php foreach ($servicios as $s): ?>
            <div class="item-card bl-card bg-[#171717]/90 backdrop-blur-sm border border-[#2a2a2a] rounded-2xl p-5 cursor-pointer hover:bg-[#1c1c1c] select-none active:scale-95 hover:-translate-y-0.5 flex flex-col justify-between min-h-[100px]"
                 data-prefix="svc" data-tipo="servicio" data-id="<?= (int)$s['id'] ?>"
                 data-precio="<?= (float)$s['precio'] ?>" data-nombre="<?= htmlspecialchars($s['nombre'], ENT_QUOTES) ?>">
                <p class="text-sm font-semibold text-[#f5f5f5] mb-3 leading-tight"><?= htmlspecialchars($s['nombre']) ?></p>
                <p class="glow-text text-lg font-light text-[#d4d4d4]">€<?= number_format($s['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($mostrarBonos && !empty($bonos)): ?>
        <p class="glow-text text-xs font-medium uppercase tracking-widest text-[#b5b5b5] mb-4">Bonos</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-8">
            <?php foreach ($bonos as $b): ?>
            <div class="item-card bl-card bg-[#171717]/90 backdrop-blur-sm border border-[#2a2a2a] rounded-2xl p-5 cursor-pointer hover:bg-[#1c1c1c] select-none active:scale-95 hover:-translate-y-0.5 flex flex-col justify-between min-h-[100px]"
                 data-prefix="svc" data-tipo="servicio" data-id="<?= (int)$b['id'] ?>"
                 data-precio="<?= (float)$b['precio'] ?>" data-nombre="<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>">
                <p class="text-[10px] uppercase tracking-widest text-[#8a8a8a] mb-1">Bono</p>
                <p class="text-sm font-semibold text-[#f5f5f5] mb-3 leading-tight"><?= htmlspecialchars($b['nombre']) ?></p>
                <p class="glow-text text-lg font-light text-[#d4d4d4]">€<?= number_format($b['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p class="glow-text text-xs font-medium uppercase tracking-widest text-[#b5b5b5] mb-4">Productos</p>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] auto-rows-fr gap-3">
            <?php foreach ($bebidas as $b): ?>
            <div class="item-card bl-card bg-[#171717]/90 backdrop-blur-sm border border-[#2a2a2a] rounded-2xl p-5 cursor-pointer hover:bg-[#1c1c1c] select-none active:scale-95 hover:-translate-y-0.5 flex flex-col justify-between min-h-[100px]"
                 data-prefix="beb" data-tipo="producto" data-id="<?= (int)$b['id'] ?>"
                 data-precio="<?= (float)$b['precio'] ?>" data-nombre="<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>">
                <p class="text-sm font-semibold text-[#f5f5f5] mb-3 leading-tight"><?= htmlspecialchars($b['nombre']) ?></p>
                <p class="glow-text text-lg font-light text-[#d4d4d4]">€<?= number_format($b['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- BACKDROP CARRITO MÓVIL -->
    <div id="carrito-backdrop" class="fixed inset-0 bg-black/60 z-50 md:hidden"></div>

    <!-- CARRITO -->
    <div id="carrito-panel" class="w-72 bg-[#0d0d0d]/95 md:bg-black/60 backdrop-blur-md border-l border-[#2a2a2a] flex flex-col flex-shrink-0">
        <div class="px-5 py-4 border-b border-[#2a2a2a] flex items-center justify-between">
            <div>
                <p class="glow-title text-sm font-medium text-[#f5f5f5]">Carrito</p>
                <p class="text-xs text-[#8a8a8a] mt-0.5"><?= htmlspecialchars($usuario['nombre']) ?></p>
            </div>
            <button id="carrito-cerrar" aria-label="Cerrar carrito"
                    class="md:hidden w-7 h-7 rounded-full bg-white/[0.06] text-zinc-400 hover:text-white flex items-center justify-center text-base">×</button>
        </div>
        <div class="flex-1 overflow-y-auto px-5 py-3" id="carrito-items">
            <div class="h-full flex items-center justify-center text-[#737373] text-xs">Carrito vacío</div>
        </div>
        <div class="px-5 py-4 border-t border-[#2a2a2a]">
            <div class="flex justify-between items-baseline mb-4">
                <span class="text-xs uppercase tracking-widest text-[#8a8a8a]">Total</span>
                <span class="glow-title text-2xl font-light text-[#f5f5f5]" id="total">€0,00</span>
            </div>
            <button onclick="abrirPasarela(Object.assign({}, carrito), Object.values(carrito).reduce((a,i) => a + i.precio * i.cantidad, 0))"
                    class="btn-glow w-full bg-[#d4d4d4] text-[#0a0a0a] text-sm font-semibold py-3 rounded-xl hover:bg-white mb-2">
                Cobrar
            </button>
            <button onclick="limpiarCarrito()" class="w-full border border-[#2a2a2a] text-[#8a8a8a] text-xs py-2 rounded-xl hover:text-[#d9a3a3] hover:border-[#5a3434] transition">
                Limpiar
            </button>
        </div>
    </div>

</div>

<!-- BOTÓN FLOTANTE CARRITO (móvil) -->
<button id="carrito-abrir" aria-label="Abrir carrito"
        class="md:hidden fixed bottom-5 right-5 z-40 w-14 h-14 rounded-full bg-[#d4d4d4] text-[#0a0a0a] shadow-[0_0_20px_rgba(255,255,255,0.2)] flex items-center justify-center font-semibold">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.293 1.293A1 1 0 006.414 16H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    <span id="carrito-badge" class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 rounded-full bg-[#0a0a0a] text-[#f5f5f5] border border-[#d4d4d4] text-[10px] font-bold flex items-center justify-center hidden">0</span>
</button>

<!-- TOAST -->
<div id="toast"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 translate-y-20 opacity-0 bg-[#171717] border border-[#333] text-white px-[1.4rem] py-[0.65rem] rounded-full text-[0.82rem] font-medium shadow-[0_8px_32px_rgba(0,0,0,0.5)] z-[99999] pointer-events-none whitespace-nowrap transition-[transform,opacity] duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
</div>

<?php include __DIR__ . '/Pasarelapago.php'; ?>

<script>
// ── Carrito móvil ────────────────────────────────────────────────────────────
(function () {
    const panel    = document.getElementById('carrito-panel');
    const backdrop = document.getElementById('carrito-backdrop');
    const abrir    = document.getElementById('carrito-abrir');
    const cerrar   = document.getElementById('carrito-cerrar');
    if (!panel || !backdrop || !abrir || !cerrar) return;
    backdrop.classList.remove('open');
    function open()  { panel.classList.add('open');    backdrop.classList.add('open');    }
    function close() { panel.classList.remove('open'); backdrop.classList.remove('open'); }
    abrir.addEventListener('click', open);
    cerrar.addEventListener('click', close);
    backdrop.addEventListener('click', close);
})();

const carrito = {};

// Delegación de clic en tarjetas de servicio/bono/bebida
document.querySelectorAll('.item-card').forEach(card => {
    card.addEventListener('click', () => {
        anadir(
            card.dataset.prefix + '_' + card.dataset.id,
            card.dataset.nombre,
            parseFloat(card.dataset.precio),
            card.dataset.tipo,
            parseInt(card.dataset.id, 10)
        );
    });
});

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

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, c => ({
        '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'
    })[c]);
}

function renderizar() {
    const items = Object.entries(carrito);
    const el = document.getElementById('carrito-items');
    const badge = document.getElementById('carrito-badge');
    const totalCantidad = Object.values(carrito).reduce((a, i) => a + i.cantidad, 0);
    if (badge) {
        if (totalCantidad > 0) { badge.textContent = totalCantidad; badge.classList.remove('hidden'); }
        else { badge.classList.add('hidden'); }
    }
    if (!items.length) {
        el.innerHTML = '<div class="h-full flex items-center justify-center text-[#737373] text-xs">Carrito vacío</div>';
        document.getElementById('total').textContent = '€0,00';
        return;
    }
    let total = 0;
    el.innerHTML = '';
    items.forEach(([id, item]) => {
        const sub = item.precio * item.cantidad;
        total += sub;
        // Construimos por DOM en vez de innerHTML para evitar inyección de HTML
        const fila = document.createElement('div');
        fila.className = 'flex items-center gap-2 py-3 border-b border-[#222] last:border-0';
        fila.innerHTML = `
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-white truncate"></p>
                <p class="text-xs text-zinc-500 mt-0.5">€${item.precio.toFixed(2)} c/u</p>
            </div>
            <div class="flex items-center gap-2">
                <button data-act="quitar" class="w-6 h-6 rounded-full border border-white/10 text-zinc-400 hover:border-white/30 hover:text-white flex items-center justify-center transition text-sm">−</button>
                <span class="text-xs font-medium text-white min-w-[16px] text-center">${item.cantidad}</span>
                <button data-act="anadir" class="w-6 h-6 rounded-full border border-white/10 text-zinc-400 hover:border-white/30 hover:text-white flex items-center justify-center transition text-sm">+</button>
            </div>
            <span class="text-xs font-medium text-white min-w-[40px] text-right">€${sub.toFixed(2)}</span>`;
        fila.querySelector('p.truncate').textContent = item.nombre;
        fila.querySelector('[data-act="quitar"]').addEventListener('click', () => quitar(id));
        fila.querySelector('[data-act="anadir"]').addEventListener('click',
            () => anadir(id, item.nombre, item.precio, item.tipo, item.idReal));
        el.appendChild(fila);
    });
    document.getElementById('total').textContent = '€' + total.toFixed(2);
}

function mostrarToast(msg, tipo = 'ok') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.remove('translate-y-20', 'opacity-0', 'border-[#d4d4d4]', 'text-[#f5f5f5]', 'border-[#7a4343]', 'text-[#d9a3a3]', 'border-[#333]', 'text-white');
    t.classList.add('translate-y-0', 'opacity-100');
    if (tipo === 'ok') t.classList.add('border-[#d4d4d4]', 'text-[#f5f5f5]');
    else               t.classList.add('border-[#7a4343]', 'text-[#d9a3a3]');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
        t.classList.remove('translate-y-0', 'opacity-100', 'border-[#d4d4d4]', 'text-[#f5f5f5]', 'border-[#7a4343]', 'text-[#d9a3a3]');
        t.classList.add('translate-y-20', 'opacity-0', 'border-[#333]', 'text-white');
    }, 2800);
}

async function registrarVenta(metodoPago) {
    const items = Object.values(carrito).map(i => ({
        tipo: i.tipo, id: i.idReal, cantidad: i.cantidad, precio: i.precio,
        nombre: i.nombre
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
            limpiarCarrito();
            mostrarToast('Venta registrada correctamente');
            return data;
        } else {
            return { ok: false, error: data.error || 'Error al registrar la venta' };
        }
    } catch (e) {
        return { ok: false, error: 'Error de conexión' };
    }
}
</script>

<?php include __DIR__ . '/partials/tema-scripts.php'; ?>

</body>
</html>