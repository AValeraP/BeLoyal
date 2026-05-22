<?php
/**
 * Helpers para protección CSRF en formularios POST.
 *
 * Uso:
 *   - En el formulario: <?= csrf_field() ?>
 *   - En el controlador, al principio del método: csrf_check();
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES);
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function csrf_check(): void
{
    $enviado = $_POST['csrf_token'] ?? '';
    $valido  = $_SESSION['csrf_token'] ?? '';
    if (!$enviado || !$valido || !hash_equals($valido, $enviado)) {
        http_response_code(403);
        die('CSRF token inválido. Vuelve atrás y reintenta.');
    }
}
