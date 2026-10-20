<style>
/* Pasarela de cobro: mismo estilo que el panel (negro, plata y bordes con luz) */
@keyframes fadeIn  { from { opacity: 0 } to { opacity: 1 } }
@keyframes slideUp { from { transform: translateY(16px) scale(.98); opacity: 0 } to { transform: none; opacity: 1 } }
@keyframes popIn   { from { transform: scale(0) } to { transform: scale(1) } }
@keyframes spin    { to { transform: rotate(360deg) } }
@keyframes aro     { from { transform: scale(1); opacity: .7 } to { transform: scale(1.9); opacity: 0 } }
@keyframes paso-entra { from { opacity: 0; transform: translateX(10px) } }

#pago-overlay.open { display: flex; animation: fadeIn .18s ease; }
.pago-modal  { animation: slideUp .25s cubic-bezier(.2,.7,.2,1); border-radius: 1.25rem; }
.exito-pop   { animation: popIn .3s cubic-bezier(.17,.67,.28,1.3); }
.spin-anim   { animation: spin .7s linear infinite; }
.pago-paso:not(.hidden) { animation: paso-entra .25s cubic-bezier(.2,.7,.2,1); }

.pago-etiqueta { font-size: .6875rem; letter-spacing: .14em; text-transform: uppercase; color: #8a8a8a; }
.pago-total { font-size: 2.75rem; font-weight: 300; letter-spacing: -.02em; line-height: 1.1; }
.pago-linea { height: 2px; width: 60%; margin: .9rem auto 0; background: linear-gradient(90deg, transparent, #d4d4d4 50%, transparent); box-shadow: 0 1px 8px rgba(255,255,255,.3); }
.pago-volver { display: inline-flex; align-items: center; gap: .3rem; margin-bottom: .9rem; font-size: .75rem; color: #737373; transition: color .2s, text-shadow .2s; }
.pago-volver:hover { color: #f5f5f5; text-shadow: 0 0 8px rgba(255,255,255,.4); }

/* Botón de cerrar: pastilla metálica redonda */
.pago-x { display: grid; place-items: center; width: 28px; height: 28px; border-radius: 9999px; font-size: 1rem; line-height: 1; color: #b5b5b5;
    background: linear-gradient(180deg, #2a2a2a, #161616); border: 1px solid #3a3a3a; box-shadow: inset 0 1px 0 rgba(255,255,255,.1); transition: color .2s, border-color .2s, box-shadow .2s; }
.pago-x:hover { color: #fff; border-color: #737373; box-shadow: inset 0 1px 0 rgba(255,255,255,.18), 0 0 10px rgba(255,255,255,.25); }

/* Opciones de pago: tarjeta metálica con icono, luz azul (tarjeta) o verde (efectivo) al pasar */
.pago-opcion { position: relative; overflow: hidden; display: flex; align-items: center; gap: .875rem; width: 100%; padding: .875rem 1rem; margin-bottom: .6rem;
    text-align: left; border-radius: .875rem; background: linear-gradient(160deg, #1f1f1f, #141414); border: 1px solid #2a2a2a;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.07); transition: border-color .25s, box-shadow .25s, transform .15s; --luz: 255,255,255; }
.pago-opcion::after { content: ""; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(105deg, transparent 35%, rgba(255,255,255,.08) 50%, transparent 65%); transform: translateX(-100%); }
.pago-opcion:hover { border-color: rgba(var(--luz), .55); box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 0 18px rgba(var(--luz), .18); transform: translateY(-1px); }
.pago-opcion:hover::after { transform: translateX(100%); transition: transform .9s ease; }
.pago-opcion:active { transform: scale(.98); }
.pago-opcion:focus-visible { outline: 2px solid #a3a3a3; outline-offset: 2px; }
.pago-opcion.es-tarjeta { --luz: 96,165,250; }
.pago-opcion.es-efectivo { --luz: 52,211,153; }
.pago-icono { display: grid; place-items: center; width: 40px; height: 40px; flex-shrink: 0; border-radius: 9999px; color: #0a0a0a;
    background: linear-gradient(135deg, #ffffff 0%, #c4c4c4 40%, #7a7a7a 60%, #e5e5e5 100%); box-shadow: 0 0 0 2px #171717, 0 0 0 3px rgba(255,255,255,.2), 0 0 12px rgba(255,255,255,.18); }
.pago-icono svg { width: 18px; height: 18px; }
.pago-opcion .led { width: 7px; height: 7px; border-radius: 9999px; background: rgb(var(--luz)); box-shadow: 0 0 6px rgb(var(--luz)); opacity: .35; transition: opacity .25s; }
.pago-opcion:hover .led { opacity: 1; }

/* Campos hundidos, como los formularios del admin */
.pago-campo { border-radius: .75rem; background: #0d0d0d; border: 1px solid #2f2f2f; box-shadow: inset 0 1px 2px rgba(0,0,0,.8), inset 0 -1px 0 rgba(255,255,255,.04); transition: border-color .2s, box-shadow .2s; }
.pago-campo:focus-within { border-color: #8a8a8a; box-shadow: inset 0 1px 2px rgba(0,0,0,.8), 0 0 0 3px rgba(255,255,255,.08), 0 0 14px rgba(255,255,255,.12); }
.pago-caja { border-radius: .875rem; background: linear-gradient(160deg, #1c1c1c, #131313); border: 1px solid #262626; box-shadow: inset 0 1px 0 rgba(255,255,255,.06); }

/* Importes rápidos en efectivo */
.pago-rapido { flex: 1; padding: .45rem 0; border-radius: .5rem; font-size: .75rem; color: #b5b5b5; background: linear-gradient(180deg, #232323, #161616); border: 1px solid #2f2f2f;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.08); transition: color .2s, border-color .2s, box-shadow .2s, transform .12s; }
.pago-rapido:hover { color: #fff; border-color: #4a4a4a; box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 0 10px rgba(255,255,255,.12); }
.pago-rapido:active { transform: scale(.95); }
.pago-cambio { font-size: 1.375rem; font-weight: 500; color: #34d399; text-shadow: 0 0 10px rgba(52,211,153,.55); }

/* Confirmar en efectivo: botón metálico con luz verde */
.btn-verde { display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%; padding: .8rem 1rem; border-radius: .75rem; font-size: .875rem; font-weight: 600;
    color: #a7f3d0; background: linear-gradient(180deg, #1d2a24, #121a16); border: 1px solid rgba(52,211,153,.5);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 0 16px rgba(52,211,153,.18); transition: box-shadow .2s, transform .12s, color .2s; }
.btn-verde:hover { color: #fff; box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 0 24px rgba(52,211,153,.35); text-shadow: 0 0 8px rgba(52,211,153,.6); }
.btn-verde:active { transform: scale(.98); }
.btn-verde:disabled { opacity: .35; cursor: not-allowed; box-shadow: none; text-shadow: none; }

/* Resultado: icono en un aro con luz que se expande */
.pago-resultado { position: relative; display: grid; place-items: center; width: 64px; height: 64px; margin: 0 auto 1rem; border-radius: 9999px; color: var(--c);
    background: radial-gradient(circle, rgba(var(--rgb), .18), rgba(var(--rgb), .04)); border: 1px solid var(--c); box-shadow: 0 0 18px rgba(var(--rgb), .35), inset 0 0 12px rgba(var(--rgb), .2); }
.pago-resultado::after { content: ""; position: absolute; inset: -1px; border-radius: inherit; border: 1px solid var(--c); animation: aro 1.6s ease-out infinite; }
.pago-ok    { --c: #34d399; --rgb: 52,211,153; }
.pago-error { --c: #f87171; --rgb: 248,113,113; }

/* Overlay solo sobre el campo de número para tapar el botón Link de Stripe
   (se inyecta dentro del iframe cross-origin, inaccesible por CSS normal) */
.stripe-number-box { position: relative; }
.stripe-number-box::after {
    content: ''; position: absolute; right: 1px; top: 1px; bottom: 1px; width: 148px;
    background: #0d0d0d; border-radius: 0 11px 11px 0; z-index: 10; pointer-events: all; cursor: text;
}

#pago-overlay button, #pago-overlay input { font-family: inherit; }
#pago-overlay input[type=number]::-webkit-inner-spin-button,
#pago-overlay input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
#pago-overlay input[type=number] { -moz-appearance: textfield; }

@media (prefers-reduced-motion: reduce) {
    #pago-overlay.open, .pago-modal, .exito-pop, .pago-paso:not(.hidden), .pago-resultado::after { animation: none; }
    .pago-opcion::after { display: none; }
}
</style>

<div id="pago-overlay" role="dialog" aria-modal="true" aria-label="Pasarela de pago"
     class="hidden fixed inset-0 bg-black/75 backdrop-blur-[6px] z-[9999] items-center justify-center font-inter">

    <div class="pago-modal bl-panel card-glow w-[400px] max-w-[94vw] max-h-[94vh] overflow-y-auto pb-6" style="--delay:-3s">

        <!-- Cabecera -->
        <div class="flex items-center gap-3 px-6 py-4" style="border-bottom:1px solid #262626">
            <svg class="w-4 h-4 text-[#e5e5e5]" style="filter:drop-shadow(0 0 4px rgba(255,255,255,.55))" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            <span class="flex-1 text-sm font-medium titulo-luz">Cobro</span>
            <button id="pago-cerrar" aria-label="Cerrar" class="pago-x">×</button>
        </div>

        <!-- Importe -->
        <div class="flex flex-col items-center px-6 pt-6 pb-5">
            <span class="pago-etiqueta mb-2">Total a cobrar</span>
            <span id="pago-total-display" class="pago-total plata">0,00</span>
            <span class="pago-linea" aria-hidden="true"></span>
        </div>

        <!-- Paso 1: Selector de método -->
        <div id="step-metodo" class="pago-paso px-6">
            <p class="pago-etiqueta mb-3">Elige el método de pago</p>
            <button id="btn-ir-tarjeta" class="pago-opcion es-tarjeta">
                <span class="pago-icono"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/></svg></span>
                <span class="flex-1">
                    <span class="block text-sm font-medium text-[#f5f5f5]">Pagar con tarjeta</span>
                    <span class="block text-[11px] text-[#737373] mt-0.5">Visa, Mastercard y otras</span>
                </span>
                <span class="led" aria-hidden="true"></span>
            </button>
            <button id="btn-ir-efectivo" class="pago-opcion es-efectivo">
                <span class="pago-icono"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg></span>
                <span class="flex-1">
                    <span class="block text-sm font-medium text-[#f5f5f5]">Cobrar en efectivo</span>
                    <span class="block text-[11px] text-[#737373] mt-0.5">Te calcula el cambio</span>
                </span>
                <span class="led" aria-hidden="true"></span>
            </button>
        </div>

        <!-- Paso 2: Tarjeta Stripe -->
        <div id="step-tarjeta" class="pago-paso hidden px-6">
            <button id="btn-volver-metodo" class="pago-volver">← Volver</button>
            <p class="pago-etiqueta mb-3">Datos de la tarjeta</p>
            <div id="stripe-card-number-el" class="stripe-field-box stripe-number-box pago-campo px-4 py-[0.85rem] mb-2"></div>
            <div class="flex gap-2 mb-2">
                <div id="stripe-card-expiry-el" class="stripe-field-box pago-campo flex-1 px-4 py-[0.85rem]"></div>
                <div id="stripe-card-cvc-el" class="stripe-field-box pago-campo flex-1 px-4 py-[0.85rem]"></div>
            </div>
            <div id="stripe-card-error" class="text-[#f87171] text-xs min-h-[1.2em] mb-2" role="alert"></div>
            <button id="btn-pagar-tarjeta" class="btn-plata">
                <span id="btn-pagar-label">Pagar <strong id="btn-pagar-importe"></strong></span>
                <span id="btn-pagar-spinner" class="hidden w-[18px] h-[18px] border-2 border-black/20 border-t-black rounded-full spin-anim"></span>
            </button>
        </div>

        <!-- Paso 3: Efectivo -->
        <div id="step-efectivo" class="pago-paso hidden px-6">
            <button id="btn-volver-efectivo" class="pago-volver">← Volver</button>
            <div class="pago-caja px-4 py-4 mb-3">
                <label for="efectivo-entrega" class="pago-etiqueta block mb-2">Entrega el cliente</label>
                <div class="pago-campo flex items-center overflow-hidden">
                    <span class="pl-3 pr-1 text-[#737373]">€</span>
                    <input type="number" id="efectivo-entrega" min="0" max="1000" step="0.01" placeholder="0,00" inputmode="decimal"
                           class="flex-1 border-0 outline-none text-xl font-medium py-2 px-1 bg-transparent text-white placeholder-[#4a4a4a]">
                </div>
                <div class="flex gap-1.5 mt-2" aria-label="Importes rápidos">
                    <button type="button" class="pago-rapido" data-entrega="exacto">Exacto</button>
                    <button type="button" class="pago-rapido" data-entrega="10">10 €</button>
                    <button type="button" class="pago-rapido" data-entrega="20">20 €</button>
                    <button type="button" class="pago-rapido" data-entrega="50">50 €</button>
                </div>
                <div class="flex justify-between items-baseline mt-4 pt-3" style="border-top:1px solid #262626">
                    <span class="text-xs text-[#8a8a8a]">Total <span id="efectivo-total" class="text-[#e5e5e5] ml-1">0,00</span></span>
                    <span class="text-xs text-[#8a8a8a]">Cambio <span id="efectivo-cambio" class="pago-cambio ml-1">€0,00</span></span>
                </div>
            </div>
            <button id="btn-confirmar-efectivo" class="btn-verde">Confirmar cobro</button>
        </div>

        <!-- Paso 4: Éxito -->
        <div id="step-exito" class="pago-paso hidden px-6">
            <div class="pago-resultado pago-ok exito-pop">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <p class="text-center text-lg font-medium titulo-luz">Cobro realizado</p>
            <p id="exito-metodo-label" class="text-center text-[#8a8a8a] text-xs mt-1">Pago completado</p>
            <p id="exito-importe-label" class="text-center text-3xl font-light plata mt-2"></p>
            <div id="email-ticket-box" class="pago-caja px-4 py-3 my-4">
                <p class="pago-etiqueta mb-2">¿Enviar ticket al cliente?</p>
                <div class="flex gap-2">
                    <input type="email" id="email-cliente-input" placeholder="email@cliente.com"
                           class="pago-campo flex-1 min-w-0 px-3 py-2 text-sm outline-none text-white placeholder-[#4a4a4a]">
                    <button id="btn-enviar-ticket" class="seg-btn">Enviar</button>
                </div>
                <p id="email-ticket-feedback" class="text-[0.78rem] text-center mt-[0.4rem] min-h-[1em] text-[#34d399]"></p>
            </div>
            <button id="btn-nuevo-cobro" class="btn-plata">Nueva venta</button>
        </div>

        <!-- Paso 5: Error -->
        <div id="step-error" class="pago-paso hidden px-6 pb-2">
            <div class="pago-resultado pago-error exito-pop">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </div>
            <p class="text-center text-lg font-medium titulo-luz">Cobro fallido</p>
            <p id="error-msg-label" class="text-center text-[#f87171] text-xs mt-1 px-2"></p>
            <button id="btn-reintentar" class="seg-btn w-full py-3 mt-6">Volver al carrito</button>
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
    const stepError       = document.getElementById('step-error');
    const exitoMetLbl     = document.getElementById('exito-metodo-label');
    const exitoImpLbl     = document.getElementById('exito-importe-label');
    const errorMsgLbl     = document.getElementById('error-msg-label');

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
        // Cierra también el panel del carrito en móvil (si existe)
        const carritoPanel    = document.getElementById('carrito-panel');
        const carritoBackdrop = document.getElementById('carrito-backdrop');
        if (carritoPanel)    carritoPanel.classList.remove('open');
        if (carritoBackdrop) carritoBackdrop.classList.remove('open');
        document.body.classList.remove('carrito-abierto');
    }

    function initStripe() {
        if (stripe) return;
        stripe   = Stripe(STRIPE_PUBLIC_KEY);
        elements = stripe.elements({ locale: 'es' });

        const fieldStyle = {
            base: {
                fontFamily: "Inter, Arial, sans-serif",
                fontSize: '16px',
                color: '#fff',
                '::placeholder': { color: '#4a4a4a' },
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
            // Errores del backend (p.ej. stock insuficiente) se muestran en el modal
            // "Cobro fallido". El cliente NO ha sido cobrado porque pasa antes de Stripe.
            setLoadingTarjeta(false);
            mostrarError(err.message || 'Error al procesar el pago');
        }
    }

    async function crearIntent() {
        const res = await fetch(URL_CREAR_INTENT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                importe_cents: totalCents,
                items: Object.values(carritoActual).map(i => ({
                    nombre: i.nombre, precio: i.precio, cantidad: i.cantidad,
                    tipo: i.tipo, id: i.idReal,
                })),
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
            if (!resultado || resultado.ok === false) {
                yaRegistrado = false;
                setLoadingTarjeta(false);
                mostrarError(resultado ? resultado.error : null);
                return;
            }
            if (resultado.id_venta) {
                ultimoIdVenta = resultado.id_venta;
            }
        }

        mostrarExito(metodo, totalCents / 100);
    }

    function irStep(step) {
        [stepMetodo, stepTarjeta, stepEfectivo, stepExito, stepError].forEach(s => s.classList.add('hidden'));
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

    function mostrarError(msg) {
        errorMsgLbl.textContent = msg || 'No se pudo registrar la venta. Comprueba el stock disponible.';
        irStep(stepError);
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
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && overlay.classList.contains('open')) cerrarOverlay(); });
    document.querySelectorAll('.pago-rapido').forEach(b => b.addEventListener('click', () => {
        efectivoEntrega.value = b.dataset.entrega === 'exacto' ? (totalCents / 100).toFixed(2) : b.dataset.entrega;
        efectivoEntrega.dispatchEvent(new Event('input'));
    }));
    overlay.addEventListener('click', e => { if (e.target === overlay) cerrarOverlay(); });
    btnIrTarjeta.addEventListener('click', () => irStep(stepTarjeta));
    btnIrEfectivo.addEventListener('click', () => irStep(stepEfectivo));
    btnVolverMet.addEventListener('click', () => irStep(stepMetodo));
    btnVolverEfe.addEventListener('click', () => irStep(stepMetodo));
    btnPagarTar.addEventListener('click', procesarTarjeta);

    efectivoEntrega.addEventListener('input', () => {
        const MAX_EFECTIVO = 1000;
        if (parseFloat(efectivoEntrega.value) > MAX_EFECTIVO) {
            efectivoEntrega.value = MAX_EFECTIVO;
        }
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

    document.getElementById('btn-reintentar').addEventListener('click', () => {
        cerrarOverlay();
    });
})();
</script>
