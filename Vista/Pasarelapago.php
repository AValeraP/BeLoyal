<div class="pago-overlay" id="pago-overlay" role="dialog" aria-modal="true" aria-label="Pasarela de pago">
    <div class="pago-modal">

        <div class="pago-header">
            <span class="pago-logo">BeLoyal</span>
            <button class="pago-cerrar" id="pago-cerrar" aria-label="Cerrar">x</button>
        </div>

        <div class="pago-importe-box">
            <span class="pago-importe-label">Total a cobrar</span>
            <span class="pago-importe-num" id="pago-total-display">0,00</span>
        </div>

        <!-- Paso 1: Selector de método -->
        <div class="pago-step" id="step-metodo">
            <p class="pago-step-title">Elige el método de pago</p>
            <div id="pago-request-btn-wrapper">
                <div id="pago-request-btn"></div>
                <p class="pago-o hidden" id="pago-o"><span>o</span></p>
            </div>
            <button class="pago-btn-metodo" id="btn-ir-tarjeta">Pagar con tarjeta</button>
            <button class="pago-btn-metodo efectivo" id="btn-ir-efectivo">Cobrar en efectivo</button>
        </div>

        <!-- Paso 2: Tarjeta Stripe -->
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

        <!-- Paso 3: Efectivo -->
        <div class="pago-step hidden" id="step-efectivo">
            <button class="pago-volver" id="btn-volver-efectivo">← Volver</button>
            <p class="pago-step-title">Cobro en efectivo</p>
            <div class="efectivo-display">
                <span class="efectivo-label">Total</span>
                <span class="efectivo-num" id="efectivo-total">0,00</span>
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
            <button class="pago-btn-confirmar efectivo-ok" id="btn-confirmar-efectivo">Confirmar cobro</button>
        </div>

        <!-- Paso 4: Exito -->
        <div class="pago-step hidden" id="step-exito">
            <div class="exito-icon">✓</div>
            <p class="exito-title">Cobro realizado</p>
            <p class="exito-sub" id="exito-metodo-label">Pago completado</p>
            <p class="exito-importe" id="exito-importe-label"></p>
            <button class="pago-btn-confirmar exito-nuevo" id="btn-nuevo-cobro">Nueva venta</button>
        </div>

    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

.pago-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.75);
    backdrop-filter: blur(6px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', Arial, sans-serif;
}
.pago-overlay.open { display: flex; animation: fadeIn .18s ease; }
@keyframes fadeIn { from { opacity:0 } to { opacity:1 } }

.pago-modal {
    background: #111;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 24px;
    width: 400px;
    max-width: 96vw;
    padding: 0 0 1.6rem;
    box-shadow: 0 32px 80px rgba(0,0,0,.6);
    animation: slideUp .22s ease;
    overflow: hidden;
}
@keyframes slideUp { from { transform:translateY(20px);opacity:0 } to { transform:translateY(0);opacity:1 } }

.pago-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .9rem 1.4rem;
    background: #000;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.pago-logo { color: #fff; font-weight: 700; font-size: .95rem; letter-spacing: .03em; }
.pago-cerrar {
    background: rgba(255,255,255,0.06);
    border: none;
    color: #888;
    font-size: 1rem;
    cursor: pointer;
    width: 28px; height: 28px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s, color .15s;
}
.pago-cerrar:hover { background: rgba(255,255,255,0.12); color: #fff; }

.pago-importe-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.6rem 1.4rem 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.pago-importe-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .1em; color: #666; margin-bottom: .4rem; }
.pago-importe-num { font-size: 2.8rem; font-weight: 300; color: #fff; letter-spacing: -.02em; }

.pago-step { padding: 1.2rem 1.4rem 0; }
.pago-step.hidden { display: none; }
.pago-step-title { font-weight: 600; font-size: .88rem; margin-bottom: .9rem; color: #ccc; }

#pago-request-btn-wrapper { margin-bottom: .2rem; }
#pago-request-btn { min-height: 44px; }
.pago-o {
    text-align: center;
    margin: .8rem 0;
    position: relative;
    font-size: .78rem;
    color: #555;
}
.pago-o::before, .pago-o::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 40%;
    height: 1px;
    background: rgba(255,255,255,0.06);
}
.pago-o::before { left: 0; }
.pago-o::after { right: 0; }

.pago-btn-metodo {
    width: 100%;
    padding: .75rem 1rem;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.04);
    color: #fff;
    font-size: .9rem;
    font-weight: 500;
    cursor: pointer;
    margin-bottom: .55rem;
    transition: border-color .15s, background .15s;
    font-family: inherit;
    text-align: left;
}
.pago-btn-metodo:hover { border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.08); }
.pago-btn-metodo.efectivo:hover { border-color: #34d399; color: #34d399; background: rgba(52,211,153,0.06); }

.pago-volver {
    background: none;
    border: none;
    color: #555;
    font-size: .8rem;
    cursor: pointer;
    padding: 0;
    margin-bottom: .8rem;
    font-family: inherit;
    transition: color .15s;
}
.pago-volver:hover { color: #ccc; }

.stripe-card-box {
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 12px;
    padding: .85rem 1rem;
    margin-bottom: .7rem;
    background: rgba(255,255,255,0.03);
    transition: border-color .2s;
}
.stripe-card-box:focus-within { border-color: #fff; }
.pago-error { color: #f87171; font-size: .8rem; min-height: 1.2em; margin-bottom: .5rem; }

.pago-btn-confirmar {
    width: 100%;
    padding: .85rem;
    border-radius: 12px;
    border: none;
    background: #fff;
    color: #000;
    font-size: .95rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: background .15s, transform .1s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}
.pago-btn-confirmar:hover { background: #e5e5e5; }
.pago-btn-confirmar:active { transform: scale(.98); }
.pago-btn-confirmar:disabled { background: #2a2a2a; color: #555; cursor: not-allowed; }
.pago-btn-confirmar.efectivo-ok { background: transparent; border: 1px solid #34d399; color: #34d399; }
.pago-btn-confirmar.efectivo-ok:hover { background: rgba(52,211,153,0.08); }
.pago-btn-confirmar.exito-nuevo { background: rgba(255,255,255,0.06); color: #fff; border: 1px solid rgba(255,255,255,0.1); margin-top: 1rem; }
.pago-btn-confirmar.exito-nuevo:hover { background: rgba(255,255,255,0.1); }

.spinner {
    width: 18px; height: 18px;
    border: 2px solid rgba(0,0,0,.2);
    border-top-color: #000;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.hidden { display: none !important; }

.efectivo-display {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: .9rem 1.1rem;
    margin-bottom: 1rem;
}
.efectivo-label { font-size: .78rem; color: #666; }
.efectivo-num { font-size: 1.8rem; font-weight: 300; color: #fff; }
.efectivo-calculadora {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: .9rem 1.1rem;
    margin-bottom: 1rem;
}
.efectivo-field-label { font-size: .72rem; color: #666; text-transform: uppercase; letter-spacing: .08em; display: block; margin-bottom: .5rem; }
.efectivo-input-wrap {
    display: flex;
    align-items: center;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    background: rgba(255,255,255,0.03);
    overflow: hidden;
}
.efectivo-euro { padding: 0 .7rem; color: #555; font-size: 1rem; }
.efectivo-input {
    flex: 1; border: none; outline: none;
    font-size: 1.2rem; font-weight: 500;
    padding: .55rem .4rem;
    font-family: inherit;
    background: transparent;
    color: #fff;
}
.efectivo-cambio-row { display: flex; justify-content: space-between; margin-top: .7rem; font-size: .88rem; color: #888; }
.efectivo-cambio { font-weight: 600; color: #34d399; }

.exito-icon {
    width: 60px; height: 60px;
    background: rgba(52,211,153,0.1);
    border: 1px solid #34d399;
    color: #34d399;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 700;
    margin: 0 auto 1rem;
    animation: popIn .3s cubic-bezier(.17,.67,.28,1.3);
}
@keyframes popIn { from { transform:scale(0) } to { transform:scale(1) } }
.exito-title { text-align: center; font-size: 1.2rem; font-weight: 600; color: #fff; }
.exito-sub { text-align: center; color: #666; font-size: .82rem; margin-top: .3rem; }
.exito-importe { text-align: center; font-size: 1.6rem; font-weight: 300; color: #fff; margin-top: .4rem; }
</style>

<script src="https://js.stripe.com/v3/"></script>
<script>
(function () {
    'use strict';

    const STRIPE_PUBLIC_KEY = '<?= htmlspecialchars($stripePublicKey ?? 'pk_test_51TUYfWBLLZWK8N7qmlmzbvfPdsNrRUdoCNEmJi0JLsIkBgZJyOFvNsPMD8uErBYJZSZvo6fByQaEfZ2lmDN3BpRG00FQIMyBKK', ENT_QUOTES) ?>';
    const URL_CREAR_INTENT  = 'index.php?page=pago_crear_intent';

    let stripe, elements, cardElement, paymentRequest;
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

    function initStripe() {
        if (stripe) return;
        stripe   = Stripe(STRIPE_PUBLIC_KEY);
        elements = stripe.elements({ locale: 'es' });
        cardElement = elements.create('card', {
            style: {
                base: {
                    fontFamily: "'DM Sans', Arial, sans-serif",
                    fontSize: '16px',
                    color: '#fff',
                    '::placeholder': { color: '#555' },
                },
                invalid: { color: '#f87171' },
            },
            hidePostalCode: true,
        });
        cardElement.mount('#stripe-card-element');
        cardElement.on('change', e => { cardError.textContent = e.error ? e.error.message : ''; });
    }

    async function setupPaymentRequest() {
        if (!stripe) return;
        document.getElementById('pago-request-btn').innerHTML = '';
        paymentRequest = stripe.paymentRequest({
            country: 'ES', currency: 'eur',
            total: { label: 'BeLoyal TPV', amount: totalCents },
            requestPayerName: false, requestPayerEmail: false,
        });
        const canMake = await paymentRequest.canMakePayment();
        const wrapper = document.getElementById('pago-request-btn-wrapper');
        if (canMake) {
            const prBtn = elements.create('paymentRequestButton', {
                paymentRequest,
                style: { paymentRequestButton: { type: 'default', theme: 'dark', height: '48px' } },
            });
            prBtn.mount('#pago-request-btn');
            wrapper.style.display = 'block';
            document.getElementById('pago-o').classList.remove('hidden');
            paymentRequest.on('paymentmethod', async ev => {
                try {
                    const { clientSecret } = await crearIntent();
                    const { paymentIntent, error } = await stripe.confirmCardPayment(
                        clientSecret, { payment_method: ev.paymentMethod.id }, { handleActions: false }
                    );
                    if (error) { ev.complete('fail'); return; }
                    if (paymentIntent.status === 'requires_action') {
                        const { error: e2 } = await stripe.confirmCardPayment(clientSecret);
                        if (e2) { ev.complete('fail'); return; }
                    }
                    ev.complete('success');
                    await finalizarCobro(paymentIntent.id, 'tarjeta');
                } catch (err) { ev.complete('fail'); }
            });
        } else {
            wrapper.style.display = 'none';
        }
    }

    async function procesarTarjeta() {
        setLoadingTarjeta(true);
        cardError.textContent = '';
        try {
            const { clientSecret } = await crearIntent();
            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardElement },
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

    async function finalizarCobro(intentId, metodo) {
        if (yaRegistrado) return;
        yaRegistrado = true;

        if (typeof registrarVenta === 'function') {
            await registrarVenta(metodo);
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

        initStripe();
        irStep(stepMetodo);
        overlay.classList.add('open');
        await setupPaymentRequest();
    };

    btnCerrar.addEventListener('click', () => overlay.classList.remove('open'));
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('open'); });
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

    btnNuevoCobro.addEventListener('click', () => {
        overlay.classList.remove('open');
        if (typeof limpiarCarrito === 'function') limpiarCarrito();
    });
})();
</script>