<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPV – <?= htmlspecialchars($usuario['nombre']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    inter: ['Inter', 'sans-serif'],
                    dm: ["'DM Sans'", 'Arial', 'sans-serif'],
                },
            }
        }
    }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Scrollbar — sin equivalente en Tailwind */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 2px; }
    </style>
</head>
<body class="font-inter flex flex-col h-screen overflow-hidden text-white"
      style="background-color:#1a1a1a;background-image:radial-gradient(ellipse at 10% 20%,rgba(255,255,255,.04) 0%,transparent 50%),radial-gradient(ellipse at 90% 80%,rgba(255,255,255,.03) 0%,transparent 50%),radial-gradient(ellipse at 60% 40%,rgba(200,200,200,.02) 0%,transparent 40%),repeating-linear-gradient(115deg,transparent 0px,transparent 20px,rgba(255,255,255,.015) 20px,rgba(255,255,255,.015) 21px,transparent 21px,transparent 45px,rgba(255,255,255,.008) 45px,rgba(255,255,255,.008) 46px),repeating-linear-gradient(68deg,transparent 0px,transparent 35px,rgba(255,255,255,.01) 35px,rgba(255,255,255,.01) 36px)">

<!-- HEADER -->
<header class="bg-black/60 backdrop-blur-sm border-b border-white/10 h-14 flex items-center justify-between px-6 flex-shrink-0">
    <p class="text-sm font-semibold tracking-widest uppercase text-white">Be Loyal</p>
    <div class="flex items-center gap-3">
        <span class="text-xs text-zinc-400"><?= htmlspecialchars($trabajador['nombre']) ?></span>
        <?php if (!empty($trabajador['logo'])): ?>
        <img src="public/img/logos/<?= htmlspecialchars($trabajador['logo']) ?>"
             alt="<?= htmlspecialchars($trabajador['nombre']) ?>"
             class="w-7 h-7 rounded-full object-cover border border-white/20"
             onerror="this.style.display='none'">
        <?php else: ?>
        <div class="w-7 h-7 rounded-full bg-zinc-700 border border-white/20 flex items-center justify-center text-xs font-semibold text-zinc-300">
            <?= mb_strtoupper(mb_substr($trabajador['nombre'], 0, 1)) ?>
        </div>
        <?php endif; ?>
        <a href="index.php?page=logout" class="text-xs text-zinc-500 hover:text-white transition">Cerrar sesión</a>
    </div>
</header>

<!-- WORKER BAR -->
<div class="bg-black/40 backdrop-blur-sm border-b border-white/10 px-6 py-3 flex-shrink-0 flex items-center gap-3">
    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
    <p class="text-sm font-medium text-white"><?= htmlspecialchars($trabajador['nombre']) ?></p>
    <span class="text-xs text-zinc-500">·</span>
    <p class="text-xs text-zinc-400"><?= htmlspecialchars(ucfirst($especialidad)) ?></p>
</div>

<!-- MAIN -->
<div class="flex flex-1 overflow-hidden">

    <!-- SERVICIOS -->
    <div class="flex-1 overflow-y-auto p-6">

        <p class="text-xs font-medium uppercase tracking-widest text-zinc-400 mb-4"><?= htmlspecialchars($seccionTitulo) ?></p>
        <div class="grid grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-8">
            <?php foreach ($servicios as $s): ?>
            <div class="item-card bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 cursor-pointer hover:bg-zinc-800/90 hover:border-white/20 transition-all duration-200 select-none active:scale-95 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/40 flex flex-col justify-between min-h-[100px]"
                 data-prefix="svc" data-tipo="servicio" data-id="<?= (int)$s['id'] ?>"
                 data-precio="<?= (float)$s['precio'] ?>" data-nombre="<?= htmlspecialchars($s['nombre'], ENT_QUOTES) ?>">
                <p class="text-sm font-semibold text-white mb-3 leading-tight"><?= htmlspecialchars($s['nombre']) ?></p>
                <p class="text-lg font-light text-zinc-300">€<?= number_format($s['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($mostrarBonos && !empty($bonos)): ?>
        <p class="text-xs font-medium uppercase tracking-widest text-zinc-400 mb-4">Bonos</p>
        <div class="grid grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-8">
            <?php foreach ($bonos as $b): ?>
            <div class="item-card bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 cursor-pointer hover:bg-zinc-800/90 hover:border-white/20 transition-all duration-200 select-none active:scale-95 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/40 flex flex-col justify-between min-h-[100px]"
                 data-prefix="svc" data-tipo="servicio" data-id="<?= (int)$b['id'] ?>"
                 data-precio="<?= (float)$b['precio'] ?>" data-nombre="<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>">
                <p class="text-xs text-zinc-500 mb-1">Bono</p>
                <p class="text-sm font-semibold text-white mb-3 leading-tight"><?= htmlspecialchars($b['nombre']) ?></p>
                <p class="text-lg font-light text-zinc-300">€<?= number_format($b['precio'], 2) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p class="text-xs font-medium uppercase tracking-widest text-zinc-400 mb-4">Bebidas</p>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] auto-rows-fr gap-3">
            <?php foreach ($bebidas as $b): ?>
            <div class="item-card bg-zinc-900/80 backdrop-blur-sm border border-white/10 rounded-2xl p-5 cursor-pointer hover:bg-zinc-800/90 hover:border-white/20 transition-all duration-200 select-none active:scale-95 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/40 flex flex-col justify-between min-h-[100px]"
                 data-prefix="beb" data-tipo="producto" data-id="<?= (int)$b['id'] ?>"
                 data-precio="<?= (float)$b['precio'] ?>" data-nombre="<?= htmlspecialchars($b['nombre'], ENT_QUOTES) ?>">
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
            <div class="h-full flex items-center justify-center text-zinc-600 text-xs">Carrito vacío</div>
        </div>
        <div class="px-5 py-4 border-t border-white/10">
            <div class="flex justify-between items-baseline mb-4">
                <span class="text-xs text-zinc-500">Total</span>
                <span class="text-2xl font-light text-white" id="total">€0,00</span>
            </div>
            <button onclick="abrirPasarela(Object.assign({}, carrito), Object.values(carrito).reduce((a,i) => a + i.precio * i.cantidad, 0))"
                    class="w-full bg-white text-zinc-900 text-sm font-semibold py-3 rounded-xl hover:bg-zinc-100 transition mb-2">
                Cobrar
            </button>
            <button onclick="limpiarCarrito()" class="w-full border border-white/10 text-zinc-600 text-xs py-2 rounded-xl hover:text-red-400 hover:border-red-900/50 transition">
                Limpiar
            </button>
        </div>
    </div>

</div>

<!-- TOAST -->
<div id="toast"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 translate-y-20 opacity-0 bg-[#18181b] border border-white/10 text-white px-[1.4rem] py-[0.65rem] rounded-full text-[0.82rem] font-medium shadow-[0_8px_32px_rgba(0,0,0,0.5)] z-[99999] pointer-events-none whitespace-nowrap transition-[transform,opacity] duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)]">
</div>

<?php include __DIR__ . '/Pasarelapago.php'; ?>

<script>
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
    if (!items.length) {
        el.innerHTML = '<div class="h-full flex items-center justify-center text-zinc-600 text-xs">Carrito vacío</div>';
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
        fila.className = 'flex items-center gap-2 py-3 border-b border-white/5 last:border-0';
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
    t.classList.remove('translate-y-20', 'opacity-0', 'border-[#34d399]', 'text-[#34d399]', 'border-[#f87171]', 'text-[#f87171]', 'border-white/10', 'text-white');
    t.classList.add('translate-y-0', 'opacity-100');
    if (tipo === 'ok') t.classList.add('border-[#34d399]', 'text-[#34d399]');
    else               t.classList.add('border-[#f87171]', 'text-[#f87171]');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
        t.classList.remove('translate-y-0', 'opacity-100', 'border-[#34d399]', 'text-[#34d399]', 'border-[#f87171]', 'text-[#f87171]');
        t.classList.add('translate-y-20', 'opacity-0', 'border-white/10', 'text-white');
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
            mostrarToast('Error: ' + data.error, 'err');
        }
    } catch (e) {
        mostrarToast('Error de conexión', 'err');
    }
    return null;
}
</script>

</body>
</html>