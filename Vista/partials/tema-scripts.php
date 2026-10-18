<?php
/**
 * Animación con el cursor: luz que lo sigue, profundidad del fondo y reflejo
 * en la tarjeta bajo el ratón. Se incluye antes de </body>:
 *   <?php include __DIR__ . '/partials/tema-scripts.php'; ?>
 */
?>
<script>
(function () {
    // Solo con ratón y si el usuario no ha pedido reducir el movimiento
    if (!matchMedia('(pointer: fine)').matches || matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const light = document.getElementById('cursor-light');
    const depth = document.getElementById('scene-depth');
    if (!light || !depth) return;

    let tx = innerWidth / 2, ty = innerHeight / 2, x = tx, y = ty;

    addEventListener('pointermove', function (e) {
        tx = e.clientX; ty = e.clientY;
        light.style.opacity = '1';

        // Reflejo en la tarjeta que está bajo el cursor
        const card = e.target.closest && e.target.closest('.bl-card');
        if (card) {
            const r = card.getBoundingClientRect();
            card.style.setProperty('--sx', (e.clientX - r.left) + 'px');
            card.style.setProperty('--sy', (e.clientY - r.top) + 'px');
        }
    }, { passive: true });

    document.documentElement.addEventListener('pointerleave', function () {
        light.style.opacity = '0';
    });

    // Movimiento suavizado en cada fotograma
    (function frame() {
        x += (tx - x) * 0.1;
        y += (ty - y) * 0.1;
        const nx = x / innerWidth - 0.5, ny = y / innerHeight - 0.5;
        light.style.transform = 'translate3d(' + (x - 320) + 'px,' + (y - 320) + 'px,0)';
        depth.style.transform = 'translate3d(' + (-nx * 30).toFixed(1) + 'px,' + (-ny * 30).toFixed(1) + 'px,0)';
        requestAnimationFrame(frame);
    })();
})();
</script>
