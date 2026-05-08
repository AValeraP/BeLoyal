<div class="pago-overlay" id="pago-overlay" role="dialog" aria-modal="true" aria-label="Pasarela de pago">
    <div class="pago-modal">

        <!-- Cabecera -->
        <div class="pago-header">
            <span class="pago-logo">✂️ BeLoyal</span>
            <button class="pago-cerrar" id="pago-cerrar" aria-label="Cerrar">✕</button>
        </div>

        <!-- Importe -->
        <div class="pago-importe-box">
            <span class="pago-importe-label">Total a cobrar</span>
            <span class="pago-importe-num" id="pago-total-display">€0,00</span>
        </div>

        <!-- Paso 1: Selector de método -->
        <div class="pago-step" id="step-metodo">
            <p class="pago-step-title">Elige el método de pago</p>

            <!-- Google Pay / Apple Pay / Link (Stripe Payment Request) -->
            <div id="pago-request-btn-wrapper">
                <div id="pago-request-btn"><!-- Stripe inserta el botón aquí --></div>
                <p class="pago-o" id="pago-o"><span>o</span></p>
            </div>

            <!-- Tarjeta manual -->
            <button class="pago-btn-tarjeta" id="btn-ir-tarjeta">
                💳 Pagar con tarjeta
            </button>

            <!-- Efectivo -->
            <button class="pago-btn-efectivo" id="btn-ir-efectivo">
                💵 Cobrar en efectivo
            </button>
        </div>

        <!-- Paso 2: Formulario tarjeta Stripe -->
        <div class="pago-step hidden" id="step-tarjeta">
            <button class="pago-volver" id="btn-volver-metodo">← Volver</button>
            <p class="pago-step-title">Datos de la tarjeta</p>
            <div id="stripe-card-element" class="stripe-card-box"></div>
            <div id="stripe-card-error" class="pago-error" role="alert"></div>
            <button class="pago-btn-confirmar" id="btn-pagar-tarjeta">
                <span id="btn-pagar-label">Pagar <strong id="btn-pagar-importe"></strong></span>
                <span id="btn-pagar-spinner" class="spinner hidden"></span>
            </button>
        </div>

        <!-- Paso 3: Cobro en efectivo -->
        <div class="pago-step hidden" id="step-efectivo">
            <button class="pago-volver" id="btn-volver-efectivo">← Volver</button>
            <p class="pago-step-title">Cobro en efectivo</p>
            <div class="efectivo-display">
                <span class="efectivo-label">Total</span>
                <span class="efectivo-num" id="efectivo-total">€0,00</span>
            </div>
            <div class="efectivo-calculadora">
                <label class="efectivo-field-label">Entrega el cliente</label>
                <div class="efectivo-input-wrap">
                    <span class="efectivo-euro">€</span>
                    <input type="number" id="efectivo-entrega" min="0" step="0.01" placeholder="0,00" class="efectivo-input">
                </div>
                <div class="efectivo-cambio-row">
                    <span>Cambio</span>
                    <span class="efectivo-cambio" id="efectivo-cambio">€0,00</span>
                </div>
            </div>
            <button class="pago-btn-confirmar efectivo-ok" id="btn-confirmar-efectivo">
                ✓ Confirmar cobro
            </button>
        </div>

        <!-- Paso 4: Éxito -->
        <div class="pago-step hidden" id="step-exito">
            <div class="exito-icon">✓</div>
            <p class="exito-title">¡Cobro realizado!</p>
            <p class="exito-sub" id="exito-metodo-label">Pago completado</p>
            <p class="exito-importe" id="exito-importe-label"></p>
            <button class="pago-btn-confirmar exito-nuevo" id="btn-nuevo-cobro">
                Nueva venta
            </button>
        </div>

    </div>
</div>

<!-- ── Estilos pasarela ─────────────────────────────────────────────────── -->
<style>
/* Fuente moderna */
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

.pago-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.62);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', Arial, sans-serif;
}
.pago-overlay.open { display: flex; animation: fadeIn .18s ease; }
@keyframes fadeIn { from { opacity:0 } to { opacity:1 } }

.pago-modal {
    background: #fff;
    border-radius: 20px;
    width: 420px;
    max-width: 96vw;
    padding: 0 0 1.6rem;
    box-shadow: 0 24px 64px rgba(0,0,0,.28);
    animation: slideUp .22s ease;
    overflow: hidden;
}
@keyframes slideUp { from { transform: translateY(24px); opacity:0 } to { transform: translateY(0); opacity:1 } }

/* ── Cabecera ── */
.pago-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .9rem 1.4rem;
    background: #1a1a1a;
}
.pago-logo { color: #d4a017; font-weight: 700; font-size: 1rem; letter-spacing: .02em; }
.pago-cerrar {
    background: none;
    border: none;
    color: #888;
    font-size: 1.1rem;
    cursor: pointer;
    width: 28px; height: 28px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s;
}
.pago-cerrar:hover { background: #333; color: #fff; }

/* ── Importe ── */
.pago-importe-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.4rem 1.4rem .8rem;
    border-bottom: 1px solid #f0f0f0;
}
.pago-importe-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .09em; color: #999; margin-bottom: .3rem; }
.pago-importe-num { font-size: 2.6rem; font-weight: 700; color: #1a1a1a; }

/* ── Steps ── */
.pago-step { padding: 1.2rem 1.4rem 0; }
.pago-step.hidden { display: none; }
.pago-step-title { font-weight: 600; font-size: .92rem; margin-bottom: .9rem; color: #333; }

/* ── Payment Request Button (Google/Apple Pay) ── */
#pago-request-btn-wrapper { margin-bottom: .2rem; }
#pago-request-btn { min-height: 44px; }
.pago-o {
    text-align: center;
    margin: .8rem 0;
    position: relative;
    font-size: .8rem;
    color: #bbb;
}
.pago-o::before, .pago-o::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 40%;
    height: 1px;
    background: #eee;
}
.pago-o::before { left: 0; }
.pago-o::after { right: 0; }

/* ── Botones selector ── */
.pago-btn-tarjeta, .pago-btn-efectivo {
    width: 100%;
    padding: .75rem;
    border-radius: 10px;
    border: 1.5px solid #e0e0e0;
    background: #fff;
    font-size: .92rem;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: .55rem;
    transition: border-color .15s, background .15s;
    font-family: inherit;
}
.pago-btn-tarjeta:hover { border-color: #d4a017; background: #fffbf0; }
.pago-btn-efectivo:hover { border-color: #27ae60; background: #f0fff4; }

/* ── Botón volver ── */
.pago-volver {
    background: none;
    border: none;
    color: #888;
    font-size: .82rem;
    cursor: pointer;
    padding: 0;
    margin-bottom: .7rem;
    font-family: inherit;
}
.pago-volver:hover { color: #333; }

/* ── Stripe card ── */
.stripe-card-box {
    border: 1.5px solid #ddd;
    border-radius: 10px;
    padding: .85rem 1rem;
    margin-bottom: .7rem;
    transition: border-color .2s;
}
.stripe-card-box:focus-within { border-color: #d4a017; }
.pago-error { color: #e53e3e; font-size: .82rem; min-height: 1.2em; margin-bottom: .5rem; }

/* ── Botón confirmar ── */
.pago-btn-confirmar {
    width: 100%;
    padding: .85rem;
    border-radius: 10px;
    border: none;
    background: #d4a017;
    color: #000;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: background .15s, transform .1s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}
.pago-btn-confirmar:hover { background: #b8880f; }
.pago-btn-confirmar:active { transform: scale(.98); }
.pago-btn-confirmar:disabled { background: #ddd; color: #999; cursor: not-allowed; }
.pago-btn-confirmar.efectivo-ok { background: #27ae60; }
.pago-btn-confirmar.efectivo-ok:hover { background: #1e8449; }
.pago-btn-confirmar.exito-nuevo { background: #1a1a1a; color: #d4a017; margin-top: 1rem; }

/* ── Spinner ── */
.spinner {
    width: 18px; height: 18px;
    border: 2.5px solid rgba(0,0,0,.15);
    border-top-color: #1a1a1a;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.hidden { display: none !important; }

/* ── Efectivo ── */
.efectivo-display {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    background: #f8f8f8;
    border-radius: 10px;
    padding: .9rem 1.1rem;
    margin-bottom: 1rem;
}
.efectivo-label { font-size: .82rem; color: #888; }
.efectivo-num { font-size: 1.8rem; font-weight: 700; color: #1a1a1a; }
.efectivo-calculadora { background: #f8f8f8; border-radius: 10px; padding: .9rem 1.1rem; margin-bottom: 1rem; }
.efectivo-field-label { font-size: .78rem; color: #888; text-transform: uppercase; letter-spacing: .06em; display: block; margin-bottom: .4rem; }
.efectivo-input-wrap { display: flex; align-items: center; border: 1.5px solid #ddd; border-radius: 8px; background: #fff; overflow: hidden; }
.efectivo-euro { padding: 0 .6rem; color: #999; font-size: 1rem; }
.efectivo-input { flex: 1; border: none; outline: none; font-size: 1.2rem; font-weight: 600; padding: .55rem .4rem; font-family: inherit; }
.efectivo-cambio-row { display: flex; justify-content: space-between; margin-top: .6rem; font-size: .9rem; }
.efectivo-cambio { font-weight: 700; color: #27ae60; }

/* ── Éxito ── */
.exito-icon {
    width: 64px; height: 64px;
    background: #27ae60;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 auto 1rem;
    animation: popIn .3s cubic-bezier(.17,.67,.28,1.3);
}
@keyframes popIn { from { transform: scale(0) } to { transform: scale(1) } }
.exito-title { text-align: center; font-size: 1.25rem; font-weight: 700; color: #1a1a1a; }
.exito-sub { text-align: center; color: #888; font-size: .85rem; margin-top: .3rem; }
.exito-importe { text-align: center; font-size: 1.5rem; font-weight: 700; color: #d4a017; margin-top: .4rem; }
</style>

<!-- ── Script pasarela ──────────────────────────────────────────────────── -->
<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    'use strict';

    // ── Config ──────────────────────────────────────────────────────────────
    const STRIPE_PUBLIC_KEY = '<?= htmlspecialchars($stripePublicKey ?? 'pk_test_51TUYfWBLLZWK8N7qmlmzbvfPdsNrRUdoCNEmJi0JLsIkBgZJyOFvNsPMD8uErBYJZSZvo6fByQaEfZ2lmDN3BpRG00FQIMyBKK', ENT_QUOTES) ?>';
    const URL_CREAR_INTENT  = 'index.php?page=pago_crear_intent';
    const URL_REGISTRAR     = 'index.php?page=pago_registrar';

    // ── Estado ──────────────────────────────────────────────────────────────
    let stripe, elements, cardElement, paymentRequest, prButton;
    let carritoActual = {};
    let totalCents = 0;

    // ── DOM ─────────────────────────────────────────────────────────────────
    const overlay       = document.getElementById('pago-overlay');
    const btnCerrar     = document.getElementById('pago-cerrar');
    const totalDisplay  = document.getElementById('pago-total-display');

    const stepMetodo    = document.getElementById('step-metodo');
    const stepTarjeta   = document.getElementById('step-tarjeta');
    const stepEfectivo  = document.getElementById('step-efectivo');
    const stepExito     = document.getElementById('step-exito');

    const btnIrTarjeta  = document.getElementById('btn-ir-tarjeta');
    const btnIrEfectivo = document.getElementById('btn-ir-efectivo');
    const btnVolverMet  = document.getElementById('btn-volver-metodo');
    const btnVolverEfe  = document.getElementById('btn-volver-efectivo');
    const btnPagarTar   = document.getElementById('btn-pagar-tarjeta');
    const btnPagarLabel = document.getElementById('btn-pagar-label');
    const btnPagarSpin  = document.getElementById('btn-pagar-spinner');
    const btnPagarImp   = document.getElementById('btn-pagar-importe');
    const cardError     = document.getElementById('stripe-card-error');

    const efectivoEntrega = document.getElementById('efectivo-entrega');
    const efectivoCambio  = document.getElementById('efectivo-cambio');
    const efectivoTotal   = document.getElementById('efectivo-total');
    const btnConfEfe      = document.getElementById('btn-confirmar-efectivo');
    const btnNuevoCobro   = document.getElementById('btn-nuevo-cobro');
    const exitoMetLbl     = document.getElementById('exito-metodo-label');
    const exitoImpLbl     = document.getElementById('exito-importe-label');

    // ── Inicializar Stripe una sola vez ──────────────────────────────────────
    function initStripe() {
        if (stripe) return;
        stripe   = Stripe(STRIPE_PUBLIC_KEY);
        elements = stripe.elements({ locale: 'es' });

        // Card Element
        cardElement = elements.create('card', {
            style: {
                base: {
                    fontFamily: "'DM Sans', Arial, sans-serif",
                    fontSize: '16px',
                    color: '#1a1a1a',
                    '::placeholder': { color: '#bbb' },
                },
                invalid: { color: '#e53e3e' },
            },
            hidePostalCode: true,
        });
        cardElement.mount('#stripe-card-element');
        cardElement.on('change', e => {
            cardError.textContent = e.error ? e.error.message : '';
        });
    }

    // ── Crear/actualizar Payment Request (Google Pay / Apple Pay) ────────────
    async function setupPaymentRequest() {
        if (!stripe) return;

        // Destruir instancia anterior si existe
        const wrapper = document.getElementById('pago-request-btn-wrapper');
        const prDiv   = document.getElementById('pago-request-btn');
        prDiv.innerHTML = '';

        paymentRequest = stripe.paymentRequest({
            country: 'ES',
            currency: 'eur',
            total: {
                label: 'BeLoyal TPV',
                amount: totalCents,
            },
            requestPayerName:  false,
            requestPayerEmail: false,
        });

        // Comprobar disponibilidad (Google Pay / Apple Pay)
        const canMake = await paymentRequest.canMakePayment();

        if (canMake) {
            prButton = elements.create('paymentRequestButton', {
                paymentRequest,
                style: {
                    paymentRequestButton: {
                        type:   'default',
                        theme:  'dark',
                        height: '48px',
                    },
                },
            });
            prButton.mount('#pago-request-btn');
            wrapper.style.display = 'block';
            document.getElementById('pago-o').classList.remove('hidden');

            paymentRequest.on('paymentmethod', async ev => {
                await procesarPaymentRequest(ev);
            });
        } else {
            // Ocultar sección Google/Apple Pay si no está disponible
            wrapper.style.display = 'none';
            document.getElementById('pago-o').classList.add('hidden');
        }
    }

    // ── Procesar pago vía Google/Apple Pay ───────────────────────────────────
    async function procesarPaymentRequest(ev) {
        try {
            const { clientSecret } = await crearIntent();

            const { paymentIntent, error: confirmError } = await stripe.confirmCardPayment(
                clientSecret,
                { payment_method: ev.paymentMethod.id },
                { handleActions: false }
            );

            if (confirmError) {
                ev.complete('fail');
                mostrarError(confirmError.message);
                return;
            }

            if (paymentIntent.status === 'requires_action') {
                const { error } = await stripe.confirmCardPayment(clientSecret);
                if (error) { ev.complete('fail'); mostrarError(error.message); return; }
            }

            ev.complete('success');
            await registrarYExito(paymentIntent.id, 'Google Pay / Apple Pay');
        } catch (err) {
            ev.complete('fail');
            mostrarError(err.message);
        }
    }

    // ── Procesar pago con tarjeta ────────────────────────────────────────────
    async function procesarTarjeta() {
        setLoadingTarjeta(true);
        cardError.textContent = '';

        try {
            const { clientSecret } = await crearIntent();

            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardElement },
            });

            if (error) {
                cardError.textContent = error.message;
                setLoadingTarjeta(false);
                return;
            }

            await registrarYExito(paymentIntent.id, 'Tarjeta bancaria');
        } catch (err) {
            cardError.textContent = err.message || 'Error al procesar el pago';
            setLoadingTarjeta(false);
        }
    }

    // ── Crear PaymentIntent en el backend ────────────────────────────────────
    async function crearIntent() {
        const res = await fetch(URL_CREAR_INTENT, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                importe_cents: totalCents,
                items: Object.values(carritoActual).map(i => ({
                    nombre:   i.nombre,
                    precio:   i.precio,
                    cantidad: i.cantidad,
                })),
            }),
        });
        const data = await res.json();
        if (data.error) throw new Error(data.error);
        return data;
    }

    // ── Registrar ticket y mostrar éxito ─────────────────────────────────────
    async function registrarYExito(intentId, metodo) {
        try {
            await fetch(URL_REGISTRAR, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    intent_id: intentId,
                    total: totalCents / 100,
                    items: Object.values(carritoActual),
                }),
            });
        } catch (_) { /* No bloqueamos si falla el registro */ }

        if (typeof registrarVenta === 'function') {
        await registrarVenta(metodo);
    }

        mostrarExito(metodo, totalCents / 100);
    }

    // ── Helpers UI ───────────────────────────────────────────────────────────
    function irStep(step) {
        [stepMetodo, stepTarjeta, stepEfectivo, stepExito].forEach(s => s.classList.add('hidden'));
        step.classList.remove('hidden');
    }

    function setLoadingTarjeta(loading) {
        btnPagarTar.disabled = loading;
        btnPagarLabel.classList.toggle('hidden', loading);
        btnPagarSpin.classList.toggle('hidden', !loading);
    }

    function mostrarError(msg) {
        cardError.textContent = msg;
    }

    function mostrarExito(metodo, importe) {
        exitoMetLbl.textContent = metodo;
        exitoImpLbl.textContent = '€' + importe.toFixed(2).replace('.', ',');
        irStep(stepExito);
    }

    function fmt(eur) {
        return '€' + eur.toFixed(2).replace('.', ',');
    }

    // ── Apertura del modal ────────────────────────────────────────────────────
    /**
     * Llama a esta función desde PanelEmpleado.js:
     *   window.abrirPasarela(carrito, total)
     *
     * @param {Object} carrito  – Objeto { id: { nombre, precio, cantidad } }
     * @param {number} total    – Total en euros (ej. 15.50)
     */
    window.abrirPasarela = async function (carrito, total) {
        carritoActual = carrito;
        totalCents    = Math.round(total * 100);

        // Actualiza displays
        const totalFmt = fmt(total);
        totalDisplay.textContent = totalFmt;
        btnPagarImp.textContent  = totalFmt;
        efectivoTotal.textContent = totalFmt;
        efectivoEntrega.value    = '';
        efectivoCambio.textContent = '€0,00';

        // Inicia Stripe y Payment Request
        initStripe();

        // Muestra el overlay y step inicial
        irStep(stepMetodo);
        overlay.classList.add('open');

        // Setup async de Google/Apple Pay
        await setupPaymentRequest();
    };

    // ── Eventos ───────────────────────────────────────────────────────────────
    btnCerrar.addEventListener('click', () => overlay.classList.remove('open'));
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('open'); });

    btnIrTarjeta.addEventListener('click', () => irStep(stepTarjeta));
    btnIrEfectivo.addEventListener('click', () => irStep(stepEfectivo));
    btnVolverMet.addEventListener('click', () => irStep(stepMetodo));
    btnVolverEfe.addEventListener('click', () => irStep(stepMetodo));

    btnPagarTar.addEventListener('click', procesarTarjeta);

    // Calculadora efectivo
    efectivoEntrega.addEventListener('input', () => {
        const entrega = parseFloat(efectivoEntrega.value) || 0;
        const cambio  = entrega - totalCents / 100;
        efectivoCambio.textContent = fmt(Math.max(0, cambio));
        btnConfEfe.disabled = entrega < totalCents / 100;
    });

    btnConfEfe.addEventListener('click', () => {
        mostrarExito('Efectivo', totalCents / 100);
    });

    btnNuevoCobro.addEventListener('click', () => {
        overlay.classList.remove('open');
        // Limpia el carrito del panel principal
        if (typeof limpiarCarrito === 'function') limpiarCarrito();
    });
})();
</script>