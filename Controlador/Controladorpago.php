<?php
/**
 * ControladorPago.php
 * Gestiona la pasarela de pago con Stripe (Google Pay / Apple Pay / Tarjeta)
 *
 * Requiere la librería oficial de Stripe:
 *   composer require stripe/stripe-php
 *
 * Variables de entorno recomendadas (.env o config.php):
 *   STRIPE_SECRET_KEY  → sk_test_...
 *   STRIPE_PUBLIC_KEY  → pk_test_...
 */

require_once __DIR__ . '/../Conn/conexion.php';

// Opción A: Composer (recomendado)
 require_once __DIR__ . '/../vendor/autoload.php';


class ControladorPago
{
    /** Clave pública Stripe (se envía al frontend) */
    private string $stripePublicKey;

    /** Clave secreta Stripe (solo backend) */
    private string $stripeSecretKey;

    public function __construct()
    {
        $this->proteger('empleado');

        // ── Configura aquí tus claves Stripe ────────────────────────────────
        // En producción usa variables de entorno o un archivo de config seguro.
        $this->stripePublicKey  = getenv('STRIPE_PUBLIC_KEY')  ?: '';
        $this->stripeSecretKey  = getenv('STRIPE_SECRET_KEY')  ?: '';
    }

    // ────────────────────────────────────────────────────────────────────────
    // ENDPOINT 1: Crear PaymentIntent
    // POST index.php?page=pago_crear_intent
    // Body JSON: { "importe_cents": 1500, "items": [...] }
    // ────────────────────────────────────────────────────────────────────────
    public function crearIntent(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['importe_cents']) || $input['importe_cents'] <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Importe inválido']);
            return;
        }

        $importeCents = (int) $input['importe_cents'];

        try {
            \Stripe\Stripe::setApiKey($this->stripeSecretKey);

            $intent = \Stripe\PaymentIntent::create([
                'amount'   => $importeCents,        // en céntimos (ej. 1500 = 15,00 €)
                'currency' => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'empleado' => $_SESSION['usuario']['nombre'] ?? '',
                    'items'    => json_encode($input['items'] ?? []),
                ],
            ]);

            echo json_encode([
                'clientSecret' => $intent->client_secret,
                'intentId'     => $intent->id,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // ────────────────────────────────────────────────────────────────────────
    // ENDPOINT 2: Verificar estado del pago
    // POST index.php?page=pago_verificar
    // Body JSON: { "payment_intent_id": "pi_..." }
    // ────────────────────────────────────────────────────────────────────────
    public function verificar(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['payment_intent_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de pago requerido']);
            return;
        }

        try {
            \Stripe\Stripe::setApiKey($this->stripeSecretKey);

            $intent = \Stripe\PaymentIntent::retrieve($input['payment_intent_id']);

            echo json_encode([
                'status'    => $intent->status,   // 'succeeded' | 'processing' | 'requires_payment_method'
                'amount'    => $intent->amount,
                'currency'  => $intent->currency,
                'intentId'  => $intent->id,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // ────────────────────────────────────────────────────────────────────────
    // ENDPOINT 3: Registrar ticket en BD tras pago exitoso (opcional)
    // POST index.php?page=pago_registrar
    // Body JSON: { "intent_id": "pi_...", "items": [...], "total": 15.00 }
    // ────────────────────────────────────────────────────────────────────────
    public function registrarTicket(): void
    {
        header('Content-Type: application/json');

        global $pdo;
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['intent_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos insuficientes']);
            return;
        }

        try {
            // Verifica de nuevo con Stripe para garantizar que el pago fue correcto
            \Stripe\Stripe::setApiKey($this->stripeSecretKey);
            $intent = \Stripe\PaymentIntent::retrieve($input['intent_id']);

            if ($intent->status !== 'succeeded') {
                http_response_code(402);
                echo json_encode(['error' => 'El pago no está completado: ' . $intent->status]);
                return;
            }

            /*
             * ── Aquí puedes guardar el ticket en tu BD ──────────────────────
             * Ejemplo (descomenta y adapta a tu esquema):
             *
             * $stmt = $pdo->prepare("
             *     INSERT INTO tickets (empleado_id, importe, stripe_intent, items, fecha_pago)
             *     VALUES (:empleado, :importe, :intent, :items, NOW())
             * ");
             * $stmt->execute([
             *     ':empleado' => $_SESSION['usuario']['id'],
             *     ':importe'  => $input['total'],
             *     ':intent'   => $input['intent_id'],
             *     ':items'    => json_encode($input['items'] ?? []),
             * ]);
             */

            echo json_encode([
                'ok'       => true,
                'intentId' => $intent->id,
                'importe'  => $intent->amount / 100,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // ────────────────────────────────────────────────────────────────────────
    // VISTA: Modal de pasarela (renderiza el HTML con la clave pública)
    // Llamado desde PanelEmpleado.php vía include
    // ────────────────────────────────────────────────────────────────────────
    public function stripePublicKey(): string
    {
        return htmlspecialchars($this->stripePublicKey, ENT_QUOTES);
    }

    // ── Protección de sesión ─────────────────────────────────────────────────
    private function proteger(string $rol): void
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== $rol) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}