<?php

/**
 * ServicioQuaderno.php
 * ─────────────────────────────────────────────────────────────────
 * Integración con la API de Quaderno para:
 *   1. Crear una transacción (venta)
 *   2. Quaderno genera automáticamente el PDF del ticket
 *   3. Quaderno envía el ticket por email al cliente
 *
 * Credenciales:
 *   QUADERNO_API_KEY     → sk_live_nd928TSA6qTF8J1_LYt2  (en producción usar variable de entorno)
 *   QUADERNO_ACCOUNT     → assur-10785
 *   QUADERNO_BASE_URL    → https://assur-10785.quadernoapp.com/api
 * ─────────────────────────────────────────────────────────────────
 */

class ServicioQuaderno
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        // ── En producción usa variables de entorno ──────────────────
        $this->apiKey  = getenv('QUADERNO_API_KEY') ?: 'sk_live_nd928TSA6qTF8J1_LYt2';
        $account       = getenv('QUADERNO_ACCOUNT')  ?: 'assur-10785';
        $this->baseUrl = "https://{$account}.quadernoapp.com/api";
    }

    /**
     * Crea una transacción en Quaderno y envía el ticket por email.
     *
     * @param array  $items      Lista de items: [['nombre'=>..., 'precio'=>..., 'cantidad'=>...]]
     * @param float  $total      Total de la venta en EUR
     * @param string $metodoPago 'efectivo' | 'Tarjeta bancaria' | 'Google Pay / Apple Pay'
     * @param string $emailCliente Email del cliente (opcional, si no se tiene se omite)
     * @param string $nombreCliente Nombre del cliente (opcional)
     * @param int    $idVenta     ID de la venta en tu BD
     *
     * @return array ['ok' => bool, 'quaderno_id' => string|null, 'error' => string|null]
     */
    public function emitirTicket(
        array  $items,
        float  $total,
        string $metodoPago,
        string $emailCliente  = '',
        string $nombreCliente = 'Cliente',
        int    $idVenta       = 0
    ): array {
        try {
            // ── 1. Normalizar método de pago al formato Quaderno ────────
            $metodoPagoQ = $this->normalizarMetodoPago($metodoPago);

            // ── 2. Construir los items para Quaderno ────────────────────
            $itemsQ = [];
            foreach ($items as $item) {
                $itemsQ[] = [
                    'description' => $item['nombre'],
                    'quantity'    => (int) ($item['cantidad'] ?? 1),
                    'amount'      => round((float) $item['precio'], 2),
                    'tax'         => [
                        'country'  => 'ES',
                        'rate'     => 21.0,   // IVA España (ajusta si aplica tipo reducido)
                        'tax_code' => 'standard',
                    ],
                ];
            }

            // ── 3. Construir payload de la transacción ──────────────────
            $payload = [
                'type'         => 'sale',
                'currency'     => 'EUR',
                'date'         => date('Y-m-d'),
                'processor'    => 'BeLoyal_TPV',
                'processor_id' => 'venta_' . (is_array($idVenta) ? ($idVenta['id_venta'] ?? 0) : (int)$idVenta) . '_' . time(),
                'items'        => $itemsQ,
                'payment'      => [
                    'method'    => $metodoPagoQ,
                    'processor' => 'BeLoyal',
                ],
                'notes'        => 'Ticket BeLoyal · Venta #' . (is_array($idVenta) ? ($idVenta['id_venta'] ?? 0) : (int)$idVenta),
            ];

            // ── 4. Añadir cliente si tiene email ────────────────────────
            if (!empty($emailCliente)) {
                $partes = explode(' ', trim($nombreCliente), 2);
                $payload['customer'] = [
                    'first_name' => $partes[0],
                    'last_name'  => $partes[1] ?? '',
                    'email'      => $emailCliente,
                    'country'    => 'ES',
                ];
            } else {
                // Sin email: cliente anónimo (ticket simplificado)
                $payload['customer'] = [
                    'first_name' => 'Cliente',
                    'last_name'  => 'BeLoyal',
                    'country'    => 'ES',
                ];
            }

            // ── 5. Llamada a la API ─────────────────────────────────────
            $respuesta = $this->request('POST', '/transactions', $payload);

            if (!$respuesta['ok']) {
                return [
                    'ok'    => false,
                    'error' => $respuesta['error'] ?? 'Error desconocido en Quaderno',
                ];
            }

            $transaccion = $respuesta['data'];
            $quaderno_id = $transaccion['id'] ?? null;

            // ── 6. Si hay email, enviar el documento generado ───────────
            if (!empty($emailCliente) && !empty($transaccion['document']['id'])) {
                $this->enviarDocumento($transaccion['document']['type'], $transaccion['document']['id']);
            }

            return [
                'ok'          => true,
                'quaderno_id' => $quaderno_id,
                'documento'   => $transaccion['document'] ?? null,
            ];

        } catch (\Exception $e) {
            return [
                'ok'    => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Envía por email el documento (invoice/receipt) generado por Quaderno.
     */
    private function enviarDocumento(string $tipo, string $idDocumento): void
    {
        // El tipo viene como 'Invoice', 'Receipt', etc.
        $endpoint = '/' . strtolower($tipo) . 's/' . $idDocumento . '/deliver';
        $this->request('GET', $endpoint);
    }

    /**
     * Normaliza el método de pago al formato que acepta Quaderno.
     */
    private function normalizarMetodoPago(string $metodo): string
    {
        $metodo = strtolower($metodo);
        if (str_contains($metodo, 'efectivo')) return 'cash';
        if (str_contains($metodo, 'tarjeta'))  return 'credit_card';
        if (str_contains($metodo, 'google'))   return 'credit_card';
        if (str_contains($metodo, 'apple'))    return 'credit_card';
        return 'credit_card';
    }

    /**
     * Realiza una petición HTTP a la API de Quaderno.
     */
    private function request(string $metodo, string $endpoint, array $body = []): array
    {
        $url  = $this->baseUrl . $endpoint;
        $auth = base64_encode($this->apiKey . ':x');

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $metodo,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Basic ' . $auth,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ['ok' => false, 'error' => 'cURL error: ' . $err];
        }

        $data = json_decode($raw, true);

        if ($code >= 200 && $code < 300) {
            return ['ok' => true, 'data' => $data];
        }

        $msg = $data['errors'] ?? ($data['message'] ?? "HTTP $code");
        if (is_array($msg)) $msg = implode(', ', array_map(fn($k,$v) => "$k: " . (is_array($v) ? implode(', ', $v) : $v), array_keys($msg), $msg));
        return ['ok' => false, 'error' => $msg];
    }
}