<?php
/**
 * Fondo 3D animado (decorativo). Se incluye justo después de <body>:
 *   <?php include __DIR__ . '/partials/tema-fondo.php'; ?>
 */
?>
<div class="scene" aria-hidden="true">
    <div class="orb orb--1"></div>
    <div class="orb orb--2"></div>

    <!-- Luz que sigue al cursor -->
    <div id="cursor-light" class="cursor-light"></div>

    <!-- Capa con profundidad: se desplaza con el cursor -->
    <div id="scene-depth" class="scene-depth">
        <div class="globe globe--main">
            <span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span>
            <span style="--i:4"></span><span style="--i:5"></span><span style="--i:6"></span><span style="--i:7"></span>
            <span class="lat" style="--z:0;--s:1"></span>
            <span class="lat" style="--z:calc(var(--g) * .25);--s:.866"></span>
            <span class="lat" style="--z:calc(var(--g) * -.25);--s:.866"></span>
            <span class="lat" style="--z:calc(var(--g) * .4);--s:.6"></span>
            <span class="lat" style="--z:calc(var(--g) * -.4);--s:.6"></span>
        </div>
        <div class="globe globe--small">
            <span style="--i:0"></span><span style="--i:1"></span><span style="--i:2"></span><span style="--i:3"></span>
            <span style="--i:4"></span><span style="--i:5"></span><span style="--i:6"></span><span style="--i:7"></span>
            <span class="lat" style="--z:0;--s:1"></span>
        </div>
    </div>
</div>
