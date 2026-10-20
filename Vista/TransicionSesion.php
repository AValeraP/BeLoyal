<?php
// Animación al entrar (solo justo después de iniciar sesión) y al cerrar sesión.
// Se incluye nada más abrir <body> en los dos paneles.
$tsNombre   = htmlspecialchars($_SESSION['usuario']['nombre'] ?? '');
$tsSub      = ($_SESSION['usuario']['rol'] ?? '') === 'admin' ? 'Panel de administración' : 'Caja';
$tsEntrada  = !empty($_SESSION['bienvenida']);
unset($_SESSION['bienvenida']);
?>
<style>
    #ts { position: fixed; inset: 0; z-index: 100000; display: grid; place-items: center; background: #000; }
    #ts[hidden] { display: none; }
    #ts .ts-luz { position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at 50% 46%, rgba(255,255,255,.12), rgba(255,255,255,.03) 28%, transparent 55%); }
    #ts .ts-escena { position: relative; display: flex; flex-direction: column; align-items: center; }
    #ts .ts-marco { position: relative; display: grid; place-items: center; width: 180px; height: 180px; }
    #ts .ts-aro { position: absolute; inset: 0; width: 100%; height: 100%; transform: rotate(-90deg); filter: drop-shadow(0 0 6px rgba(255,255,255,.45)); }
    #ts .ts-aro circle { fill: none; stroke: url(#ts-plata); stroke-width: 1.5; stroke-linecap: round; stroke-dasharray: 534; stroke-dashoffset: 534; }
    #ts .ts-logo { width: 104px; height: auto; opacity: 0;
        filter: drop-shadow(0 0 1px rgba(255,255,255,.7)) drop-shadow(0 0 10px rgba(255,255,255,.35)) drop-shadow(0 0 24px rgba(255,255,255,.15)); }
    #ts .ts-texto { margin-top: 1.5rem; font-size: 1.125rem; font-weight: 500; letter-spacing: .02em; opacity: 0;
        background: linear-gradient(180deg, #fff 0%, #d4d4d4 45%, #8a8a8a 100%); -webkit-background-clip: text; background-clip: text; color: transparent;
        filter: drop-shadow(0 0 6px rgba(255,255,255,.35)); }
    #ts .ts-sub { margin-top: .375rem; font-size: 10px; letter-spacing: .22em; text-transform: uppercase; color: #737373; opacity: 0; }

    /* Entrada: el aro se dibuja, el logo se enciende como un neón y la cortina se abre hacia el panel */
    #ts.ts-entrada { animation: ts-fuera .7s cubic-bezier(.6,0,.4,1) 1.55s forwards; }
    #ts.ts-entrada .ts-escena { animation: ts-acerca .7s cubic-bezier(.6,0,.4,1) 1.55s forwards; }
    #ts.ts-entrada .ts-aro circle { animation: ts-traza 1s cubic-bezier(.6,0,.2,1) .1s forwards; }
    #ts.ts-entrada .ts-logo { animation: ts-enciende .9s steps(1) .25s forwards; }
    #ts.ts-entrada .ts-texto { animation: ts-aparece .5s ease .7s forwards; }
    #ts.ts-entrada .ts-sub { animation: ts-aparece .5s ease .85s forwards; }
    body.ts-revela #ts ~ * { animation: ts-sube .8s cubic-bezier(.2,.7,.2,1) 1.6s backwards; }
    body.ts-revela #ts ~ #sidebar { animation-name: ts-asoma; } /* el menú del admin se mueve con transform en el móvil: solo aparece */

    /* Salida: el panel se desenfoca, el logo se apaga parpadeando y el aro se borra */
    #ts.ts-salida { animation: ts-dentro .35s ease forwards; }
    #ts.ts-salida .ts-aro circle { stroke-dashoffset: 0; animation: ts-borra .9s cubic-bezier(.6,0,.4,1) .35s forwards; }
    #ts.ts-salida .ts-logo { opacity: 1; animation: ts-apaga .8s steps(1) .45s forwards; }
    #ts.ts-salida .ts-texto, #ts.ts-salida .ts-sub { animation: ts-aparece .4s ease .1s forwards; }
    body.ts-saliendo #ts ~ * { transition: filter .45s ease, transform .45s ease, opacity .45s ease; filter: blur(6px); transform: scale(.98); opacity: .4; }

    @keyframes ts-traza   { to { stroke-dashoffset: 0; } }
    @keyframes ts-borra   { from { stroke-dashoffset: 0; } to { stroke-dashoffset: -534; } }
    @keyframes ts-aparece { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
    @keyframes ts-enciende { 0% { opacity: 0; } 15% { opacity: .8; } 25% { opacity: .1; } 40% { opacity: .9; } 50% { opacity: .3; } 65%, 100% { opacity: 1; } }
    @keyframes ts-apaga    { 0% { opacity: 1; } 20% { opacity: .2; } 30% { opacity: .9; } 50% { opacity: .1; } 60% { opacity: .5; } 75%, 100% { opacity: 0; } }
    @keyframes ts-fuera   { to { opacity: 0; visibility: hidden; } }
    @keyframes ts-dentro  { from { background: rgba(0,0,0,0); } to { background: rgba(0,0,0,.92); } }
    @keyframes ts-acerca  { to { transform: scale(1.18); opacity: 0; } }
    @keyframes ts-sube    { from { opacity: 0; transform: translateY(14px) scale(.985); } }
    @keyframes ts-asoma   { from { opacity: 0; } }

    @media (prefers-reduced-motion: reduce) {
        #ts.ts-entrada { animation: ts-fuera .3s ease .6s forwards; }
        #ts.ts-entrada *, #ts.ts-salida * { animation: none !important; opacity: 1 !important; stroke-dashoffset: 0 !important; }
        body.ts-revela #ts ~ * { animation: none; }
        body.ts-saliendo #ts ~ * { filter: none; transform: none; }
    }
</style>

<div id="ts" class="<?= $tsEntrada ? 'ts-entrada' : '' ?>" aria-hidden="true"<?= $tsEntrada ? '' : ' hidden' ?>>
    <div class="ts-luz"></div>
    <div class="ts-escena">
        <div class="ts-marco">
            <svg class="ts-aro" viewBox="0 0 180 180">
                <defs><linearGradient id="ts-plata" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fff"/><stop offset=".5" stop-color="#8a8a8a"/><stop offset="1" stop-color="#e5e5e5"/></linearGradient></defs>
                <circle cx="90" cy="90" r="85"/>
            </svg>
            <img class="ts-logo" src="public/img/logos/beloyal-logo.svg" alt="" draggable="false">
        </div>
        <p class="ts-texto"><?= $tsEntrada ? 'Bienvenido, ' : 'Hasta pronto, ' ?><?= $tsNombre ?></p>
        <p class="ts-sub"><?= $tsSub ?></p>
    </div>
</div>

<script>
(function () {
    const ts = document.getElementById('ts');
    const texto = ts.querySelector('.ts-texto');

    // Entrada: el panel sube detrás de la cortina; un clic o una tecla la saltan
    if (ts.classList.contains('ts-entrada')) {
        document.body.classList.add('ts-revela');
        // solo mientras dura la entrada: la despedida usa la misma capa
        const quitar = () => { if (!ts.classList.contains('ts-entrada')) return; ts.hidden = true; ts.className = ''; document.body.classList.remove('ts-revela'); };
        ts.addEventListener('animationend', e => { if (e.target === ts) quitar(); });
        ts.addEventListener('click', quitar);
        document.addEventListener('keydown', function salta() { quitar(); document.removeEventListener('keydown', salta); }, { once: true });
    }

    // Salida: cualquier enlace de cerrar sesión pasa antes por la despedida
    let saliendo = false;
    document.addEventListener('click', function (e) {
        const a = e.target.closest('a[href="index.php?page=logout"]');
        if (!a || e.ctrlKey || e.metaKey || e.shiftKey) return;
        e.preventDefault();
        if (saliendo) return;
        saliendo = true;
        texto.textContent = <?= json_encode('Hasta pronto, ' . ($_SESSION['usuario']['nombre'] ?? ''), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
        ts.hidden = false;
        ts.className = 'ts-salida';
        document.body.classList.add('ts-saliendo');
        const espera = matchMedia('(prefers-reduced-motion: reduce)').matches ? 300 : 1300;
        setTimeout(() => { location.href = a.href; }, espera);
    });
})();
</script>
