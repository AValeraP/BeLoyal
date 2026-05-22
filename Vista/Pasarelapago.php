<style>
@keyframes fadeIn  { from { opacity: 0 }                            to { opacity: 1 } }
@keyframes slideUp { from { transform: translateY(20px); opacity:0 } to { transform: translateY(0); opacity: 1 } }
@keyframes popIn   { from { transform: scale(0) }                   to { transform: scale(1) } }
@keyframes spin    { to   { transform: rotate(360deg) } }

#pago-overlay.open { display: flex; animation: fadeIn .18s ease; }
.pago-modal        { animation: slideUp .22s ease; }
.exito-pop         { animation: popIn .3s cubic-bezier(.17,.67,.28,1.3); }
.spin-anim         { animation: spin .7s linear infinite; }

/* Divisor "o" con líneas laterales */
.pago-o-sep { position: relative; }
.pago-o-sep::before,
.pago-o-sep::after { content: ''; position: absolute; top: 50%; width: 40%; height: 1px; background: rgba(255,255,255,0.06); }
.pago-o-sep::before { left: 0; }
.pago-o-sep::after  { right: 0; }

/* Focus en los campos de tarjeta */
.stripe-field-box:focus-within { border-color: rgba(255,255,255,0.35); }

/* Overlay solo sobre el campo de número para tapar el botón Link de Stripe
   (se inyecta dentro del iframe cross-origin, inaccesible por CSS normal) */
.stripe-number-box { position: relative; }
.stripe-number-box::after {
    content: '';
    position: absolute;
    right: 1px; top: 1px; bottom: 1px;
    width: 148px;
    background: #1a1a1a;
    border-radius: 0 10px 10px 0;
    z-index: 10;
    pointer-events: all;
    cursor: text;
}


/* Herencia de fuente en elementos de formulario */
#pago-overlay button,
#pago-overlay input { font-family: inherit; }

/* Ocultar flechas de inputs numéricos en la pasarela */
#pago-overlay input[type=number]::-webkit-inner-spin-button,
#pago-overlay input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
#pago-overlay input[type=number] { -moz-appearance: textfield; }
</style>

<div id="pago-overlay" role="dialog" aria-modal="true" aria-label="Pasarela de pago"
     class="hidden fixed inset-0 bg-black/75 backdrop-blur-[6px] z-[9999] items-center justify-center font-dm">

    <div class="pago-modal bg-[#111] border border-white/[0.08] rounded-3xl w-[400px] max-w-[96vw] pb-[1.6rem] shadow-[0_32px_80px_rgba(0,0,0,0.6)] overflow-hidden">

        <!-- Cabecera -->
        <div class="flex items-center justify-between px-[1.4rem] py-[0.9rem] bg-black border-b border-white/[0.06]">
            <span class="text-white font-bold text-[0.95rem] tracking-[0.03em]">BeLoyal</span>
            <button id="pago-cerrar" aria-label="Cerrar"
                    class="bg-white/[0.06] border-0 text-[#888] text-[1rem] cursor-pointer w-7 h-7 rounded-full flex items-center justify-center transition-all duration-150 hover:bg-white/[0.12] hover:text-white">×</button>
        </div>

        <!-- Importe -->
        <div class="flex flex-col items-center px-[1.4rem] pt-[1.6rem] pb-4 border-b border-white/[0.06]">
            <span class="text-[0.72rem] uppercase tracking-[0.1em] text-[#666] mb-[0.4rem]">Total a cobrar</span>
            <span id="pago-total-display" class="text-[2.8rem] font-light text-white tracking-[-0.02em]">0,00</span>
        </div>

        <!-- Paso 1: Selector de método -->
        <div id="step-metodo" class="px-[1.4rem] pt-[1.2rem]">
            <p class="font-semibold text-[0.88rem] mb-[0.9rem] text-[#ccc]">Elige el método de pago</p>
            <button id="btn-ir-tarjeta"
                    class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/[0.04] text-white text-[0.9rem] font-medium cursor-pointer mb-[0.55rem] transition-all duration-150 text-left hover:border-white/30 hover:bg-white/[0.08]">
                Pagar con tarjeta
            </button>
            <button id="btn-ir-efectivo"
                    class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/[0.04] text-white text-[0.9rem] font-medium cursor-pointer mb-[0.55rem] transition-all duration-150 text-left hover:border-[#34d399] hover:text-[#34d399] hover:bg-[#34d399]/[0.06]">
                Cobrar en efectivo
            </button>
        </div>

        <!-- Paso 2: Tarjeta Stripe -->
        <div id="step-tarjeta" class="hidden px-[1.4rem] pt-[1.2rem]">
            <button id="btn-volver-metodo"
                    class="bg-transparent border-0 text-[#555] text-[0.8rem] cursor-pointer p-0 mb-[0.8rem] transition-colors duration-150 hover:text-[#ccc]">← Volver</button>
            <p class="font-semibold text-[0.88rem] mb-[0.9rem] text-[#ccc]">Datos de la tarjeta</p>
            <div id="stripe-card-number-el"
                 class="stripe-field-box stripe-number-box border border-white/[0.12] rounded-xl px-4 py-[0.85rem] mb-[0.55rem] bg-white/[0.03] transition-colors duration-200"></div>
            <div class="flex gap-3 mb-[0.7rem]">
                <div id="stripe-card-expiry-el"
                     class="stripe-field-box flex-1 border border-white/[0.12] rounded-xl px-4 py-[0.85rem] bg-white/[0.03] transition-colors duration-200"></div>
                <div id="stripe-card-cvc-el"
                     class="stripe-field-box flex-1 border border-white/[0.12] rounded-xl px-4 py-[0.85rem] bg-white/[0.03] transition-colors duration-200"></div>
            </div>
            <div id="stripe-card-error" class="text-[#f87171] text-[0.8rem] min-h-[1.2em] mb-[0.5rem]" role="alert"></div>
            <button id="btn-pagar-tarjeta"
                    class="w-full py-[0.85rem] rounded-xl border-0 bg-white text-black text-[0.95rem] font-bold cursor-pointer transition-all duration-150 flex items-center justify-center gap-2 hover:bg-[#e5e5e5] active:scale-[0.98] disabled:bg-[#2a2a2a] disabled:text-[#555] disabled:cursor-not-allowed">
                <span id="btn-pagar-label">Pagar <strong id="btn-pagar-importe"></strong></span>
                <span id="btn-pagar-spinner" class="hidden w-[18px] h-[18px] border-2 border-black/20 border-t-black rounded-full spin-anim"></span>
            </button>
        </div>

        <!-- Paso 3: Efectivo -->
        <div id="step-efectivo" class="hidden px-[1.4rem] pt-[1.2rem]">
            <button id="btn-volver-efectivo"
                    class="bg-transparent border-0 text-[#555] text-[0.8rem] cursor-pointer p-0 mb-[0.8rem] transition-colors duration-150 hover:text-[#ccc]">← Volver</button>
            <p class="font-semibold text-[0.88rem] mb-[0.9rem] text-[#ccc]">Cobro en efectivo</p>
            <div class="flex justify-between items-baseline bg-white/[0.04] border border-white/[0.06] rounded-xl px-[1.1rem] py-[0.9rem] mb-4">
                <span class="text-[0.78rem] text-[#666]">Total</span>
                <span id="efectivo-total" class="text-[1.8rem] font-light text-white">0,00</span>
            </div>
            <div class="bg-white/[0.04] border border-white/[0.06] rounded-xl px-[1.1rem] py-[0.9rem] mb-4">
                <label class="text-[0.72rem] text-[#666] uppercase tracking-[0.08em] block mb-[0.5rem]">Entrega el cliente</label>
                <div class="flex items-center border border-white/10 rounded-lg bg-white/[0.03] overflow-hidden">
                    <span class="px-[0.7rem] text-[#555] text-[1rem]">€</span>
                    <input type="number" id="efectivo-entrega" min="0" step="0.01" placeholder="0,00"
                           class="flex-1 border-0 outline-none text-[1.2rem] font-medium py-[0.55rem] px-[0.4rem] bg-transparent text-white placeholder-[#555]">
                </div>
                <div class="flex justify-between mt-[0.7rem] text-[0.88rem] text-[#888]">
                    <span>Cambio</span>
                    <span id="efectivo-cambio" class="font-semibold text-[#34d399]">€0,00</span>
                </div>
            </div>
            <button id="btn-confirmar-efectivo"
                    class="w-full py-[0.85rem] rounded-xl bg-transparent border border-[#34d399] text-[#34d399] text-[0.95rem] font-bold cursor-pointer transition-all duration-150 flex items-center justify-center hover:bg-[#34d399]/[0.08] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                Confirmar cobro
            </button>
        </div>

        <!-- Paso 4: Éxito -->
        <div id="step-exito" class="hidden px-[1.4rem] pt-[1.2rem]">
            <div class="exito-pop w-[60px] h-[60px] bg-[#34d399]/10 border border-[#34d399] text-[#34d399] rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <p class="text-center text-[1.2rem] font-semibold text-white">Cobro realizado</p>
            <p id="exito-metodo-label" class="text-center text-[#666] text-[0.82rem] mt-[0.3rem]">Pago completado</p>
            <p id="exito-importe-label" class="text-center text-[1.6rem] font-light text-white mt-[0.4rem]"></p>
            <div id="email-ticket-box" class="my-4">
                <p class="text-[0.78rem] text-[#666] text-center mb-[0.5rem]">¿Enviar ticket al cliente?</p>
                <div class="flex gap-[0.4rem]">
                    <input type="email" id="email-cliente-input" placeholder="email@cliente.com"
                           class="flex-1 border border-white/[0.12] rounded-lg px-3 py-[0.55rem] text-[0.85rem] outline-none transition-colors duration-150 bg-white/[0.05] text-white placeholder-[#555] focus:border-white/[0.35]">
                    <button id="btn-enviar-ticket"
                            class="px-[0.9rem] py-[0.55rem] bg-white/[0.08] text-white border border-white/[0.15] rounded-lg text-[0.82rem] font-semibold cursor-pointer whitespace-nowrap transition-all duration-150 hover:bg-white/[0.14] hover:border-white/30 disabled:bg-transparent disabled:text-[#444] disabled:border-[#333] disabled:cursor-not-allowed">
                        Enviar
                    </button>
                </div>
                <p id="email-ticket-feedback" class="text-[0.78rem] text-center mt-[0.4rem] min-h-[1em] text-[#34d399]"></p>
            </div>
            <button id="btn-nuevo-cobro"
                    class="w-full py-[0.85rem] rounded-xl bg-white/[0.06] text-white border border-white/10 text-[0.95rem] font-bold cursor-pointer transition-all duration-150 flex items-center justify-center mt-4 hover:bg-white/10 active:scale-[0.98]">
                Nueva venta
            </button>
        </div>

    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    'use strict';

    const STRIPE_PUBLIC_KEY = '<?= htmlspecialchars($stripePublicKey ?? '', ENT_QUOTES) ?>';
    const URL_CREAR_INTENT  = 'index.php?page=pago_crear_intent';

    let stripe, elements, cardNumberEl, cardExpiryEl, cardCvcEl;
    let carritoActual = {};
    let totalCents = 0;
    let yaRegistrado = false;

    const overlay         = document.getElementById('pago-overlay');
    const btnCerrar       = document.getElementById('pago-cerrar');
    const totalDisplay    = document.getElementById('pago-total-display');
    const stepMetodo      = document.getElementById('step-metodo');
    const stepTarjeta     = document.getElementById('step-tarjeta');
    const stepEfectivo    = document.getElementById('step-efectivo');
    const stepExito       = document.getElementById('step-exito');
    const btnIrTarjeta    = document.getElementById('btn-ir-tarjeta');
    const btnIrEfectivo   = document.getElementById('btn-ir-efectivo');
    const btnVolverMet    = document.getElementById('btn-volver-metodo');
    const btnVolverEfe    = document.getElementById('btn-volver-efectivo');
    const btnPagarTar     = document.getElementById('btn-pagar-tarjeta');
    const btnPagarLabel   = document.getElementById('btn-pagar-label');
    const btnPagarSpin    = document.getElementById('btn-pagar-spinner');
    const btnPagarImp     = document.getElementById('btn-pagar-importe');
    const cardError       = document.getElementById('stripe-card-error');
    const efectivoEntrega = document.getElementById('efectivo-entrega');
    const efectivoCambio  = document.getElementById('efectivo-cambio');
    const efectivoTotal   = document.getElementById('efectivo-total');
    const btnConfEfe      = document.getElementById('btn-confirmar-efectivo');
    const btnNuevoCobro   = document.getElementById('btn-nuevo-cobro');
    const exitoMetLbl     = document.getElementById('exito-metodo-label');
    const exitoImpLbl     = document.getElementById('exito-importe-label');

    // ── Detección de conexión: sin internet solo efectivo ───────────────────
    function actualizarBotonesConexion() {
        const online = navigator.onLine;
        btnIrTarjeta.style.display = online ? '' : 'none';
    }
    actualizarBotonesConexion();
    window.addEventListener('online',  actualizarBotonesConexion);
    window.addEventListener('offline', actualizarBotonesConexion);

    function abrirOverlay() {
        overlay.classList.remove('hidden');
        overlay.classList.add('open');
    }

    function cerrarOverlay() {
        overlay.classList.remove('open');
        overlay.classList.add('hidden');
    }

    function initStripe() {
        if (stripe) return;
        stripe   = Stripe(STRIPE_PUBLIC_KEY);
        elements = stripe.elements({ locale: 'es' });

        const fieldStyle = {
            base: {
                fontFamily: "'DM Sans', Arial, sans-serif",
                fontSize: '16px',
                color: '#fff',
                '::placeholder': { color: '#555' },
            },
            invalid: { color: '#f87171' },
        };

        cardNumberEl = elements.create('cardNumber', { style: fieldStyle, disableLink: true });
        cardExpiryEl = elements.create('cardExpiry', { style: fieldStyle });
        cardCvcEl    = elements.create('cardCvc',    { style: fieldStyle, placeholder: 'CVV' });

        cardNumberEl.mount('#stripe-card-number-el');
        cardExpiryEl.mount('#stripe-card-expiry-el');
        cardCvcEl.mount('#stripe-card-cvc-el');

        [cardNumberEl, cardExpiryEl, cardCvcEl].forEach(el => {
            el.on('change', e => { cardError.textContent = e.error ? e.error.message : ''; });
        });
    }

    async function procesarTarjeta() {
        setLoadingTarjeta(true);
        cardError.textContent = '';
        try {
            const { clientSecret } = await crearIntent();
            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardNumberEl },
            });
            if (error) { cardError.textContent = error.message; setLoadingTarjeta(false); return; }
            await finalizarCobro(paymentIntent.id, 'tarjeta');
        } catch (err) {
            cardError.textContent = err.message || 'Error al procesar el pago';
            setLoadingTarjeta(false);
        }
    }

    async function crearIntent() {
        const res = await fetch(URL_CREAR_INTENT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                importe_cents: totalCents,
                items: Object.values(carritoActual).map(i => ({ nombre: i.nombre, precio: i.precio, cantidad: i.cantidad })),
            }),
        });
        const data = await res.json();
        if (data.error) throw new Error(data.error);
        return data;
    }

    let ultimoIdVenta = null;
    let ultimoMetodo  = null;

    async function finalizarCobro(intentId, metodo) {
        if (yaRegistrado) return;
        yaRegistrado = true;
        ultimoMetodo = metodo;

        if (typeof registrarVenta === 'function') {
            const resultado = await registrarVenta(metodo);
            if (resultado && resultado.id_venta) {
                ultimoIdVenta = resultado.id_venta;
            }
        }

        mostrarExito(metodo, totalCents / 100);
    }

    function irStep(step) {
        [stepMetodo, stepTarjeta, stepEfectivo, stepExito].forEach(s => s.classList.add('hidden'));
        step.classList.remove('hidden');
    }

    function setLoadingTarjeta(loading) {
        btnPagarTar.disabled = loading;
        btnPagarLabel.classList.toggle('hidden', loading);
        btnPagarSpin.classList.toggle('hidden', !loading);
    }

    function mostrarExito(metodo, importe) {
        exitoMetLbl.textContent = metodo.charAt(0).toUpperCase() + metodo.slice(1);
        exitoImpLbl.textContent = '€' + importe.toFixed(2).replace('.', ',');
        irStep(stepExito);
    }

    function fmt(eur) { return '€' + eur.toFixed(2).replace('.', ','); }

    window.abrirPasarela = async function (carrito, total) {
        if (!total || Object.keys(carrito).length === 0) {
            if (typeof mostrarToast === 'function') mostrarToast('El carrito esta vacio', 'err');
            return;
        }
        carritoActual = carrito;
        totalCents    = Math.round(total * 100);
        yaRegistrado  = false;

        const totalFmt = fmt(total);
        totalDisplay.textContent   = totalFmt;
        btnPagarImp.textContent    = totalFmt;
        efectivoTotal.textContent  = totalFmt;
        efectivoEntrega.value      = '';
        efectivoCambio.textContent = '€0,00';
        btnConfEfe.disabled        = true;
        cardError.textContent      = '';
        setLoadingTarjeta(false);
        if (cardNumberEl) { cardNumberEl.clear(); cardExpiryEl.clear(); cardCvcEl.clear(); }

        actualizarBotonesConexion();
        if (navigator.onLine) initStripe();
        irStep(stepMetodo);
        abrirOverlay();
    };

    btnCerrar.addEventListener('click', cerrarOverlay);
    overlay.addEventListener('click', e => { if (e.target === overlay) cerrarOverlay(); });
    btnIrTarjeta.addEventListener('click', () => irStep(stepTarjeta));
    btnIrEfectivo.addEventListener('click', () => irStep(stepEfectivo));
    btnVolverMet.addEventListener('click', () => irStep(stepMetodo));
    btnVolverEfe.addEventListener('click', () => irStep(stepMetodo));
    btnPagarTar.addEventListener('click', procesarTarjeta);

    efectivoEntrega.addEventListener('input', () => {
        const entrega = parseFloat(efectivoEntrega.value) || 0;
        const cambio  = entrega - totalCents / 100;
        efectivoCambio.textContent = fmt(Math.max(0, cambio));
        btnConfEfe.disabled = entrega < totalCents / 100;
    });

    btnConfEfe.addEventListener('click', async () => {
        await finalizarCobro(null, 'efectivo');
    });

    // ── Enviar ticket por email ──────────────────────────────────────────────
    document.getElementById('btn-enviar-ticket').addEventListener('click', async () => {
        const emailInput = document.getElementById('email-cliente-input');
        const feedback   = document.getElementById('email-ticket-feedback');
        const btnEnviar  = document.getElementById('btn-enviar-ticket');
        const email      = emailInput.value.trim();

        const clsBase = 'text-[0.78rem] text-center mt-[0.4rem] min-h-[1em]';

        if (!email || !/^[^@]+@[^@]+\.[^@]+$/.test(email)) {
            feedback.textContent = 'Introduce un email válido';
            feedback.className   = clsBase + ' text-[#f87171]';
            return;
        }

        btnEnviar.disabled   = true;
        feedback.textContent = 'Enviando...';
        feedback.className   = clsBase + ' text-[#34d399]';

        try {
            const res = await fetch('index.php?page=enviar_ticket', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({
                    email_cliente:  email,
                    nombre_cliente: email.split('@')[0],
                    id_venta:       ultimoIdVenta,
                    metodo_pago:    ultimoMetodo,
                    total:          totalCents / 100,
                    items:          Object.values(carritoActual).map(i => ({
                        nombre:   i.nombre,
                        precio:   i.precio,
                        cantidad: i.cantidad,
                        tipo:     i.tipo,
                        id:       i.idReal,
                    })),
                }),
            });
            const data = await res.json();
            if (data.ok) {
                feedback.textContent = 'Ticket enviado a ' + email;
                feedback.className   = clsBase + ' text-[#34d399]';
                emailInput.value     = '';
                btnEnviar.disabled   = false;
            } else {
                feedback.textContent = 'Error: ' + (data.error || 'No se pudo enviar');
                feedback.className   = clsBase + ' text-[#f87171]';
                btnEnviar.disabled   = false;
            }
        } catch (e) {
            feedback.textContent = 'Error de conexión';
            feedback.className   = clsBase + ' text-[#f87171]';
            btnEnviar.disabled   = false;
        }
    });

    btnNuevoCobro.addEventListener('click', () => {
        cerrarOverlay();
        document.getElementById('email-cliente-input').value = '';
        document.getElementById('email-ticket-feedback').textContent = '';
        document.getElementById('btn-enviar-ticket').disabled = false;
        if (typeof limpiarCarrito === 'function') limpiarCarrito();
    });
})();
</script>
