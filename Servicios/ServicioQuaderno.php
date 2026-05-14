<?php

class ServicioQuaderno
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = getenv('QUADERNO_API_KEY') ?: 'sk_live_nd928TSA6qTF8J1_LYt2';
        $account       = getenv('QUADERNO_ACCOUNT')  ?: 'assur-10785';
        $this->baseUrl = "https://{$account}.quadernoapp.com/api";
    }

    public function emitirTicket(
        array  $items,
        float  $total,
        string $metodoPago,
        string $emailCliente  = '',
        string $nombreCliente = 'Cliente',
        int    $idVenta       = 0
    ): array {
        try {
            $metodoPagoQ = $this->normalizarMetodoPago($metodoPago);

            // ── 1. Construir items ───────────────────────────────────────
            // El precio ya incluye IVA — se calcula la base imponible
            // para que Quaderno muestre "IVA incluido" sin sumarlo encima
            $itemsQ = [];
            foreach ($items as $item) {
                $precioConIva  = round((float) $item['precio'], 2);
                // Base imponible = precio / 1.21
                $baseImponible = round($precioConIva / 1.21, 4);
                $cuotaIva      = round($precioConIva - $baseImponible, 2);

                $itemsQ[] = [
                    'description' => $item['nombre'],
                    'quantity'    => (int) ($item['cantidad'] ?? 1),
                    'unit_price'  => $baseImponible,  // base sin IVA
                    'tax_1_name'  => 'IVA',
                    'tax_1_rate'  => 21,
                ];
            }

            // ── 2. Preparar datos del cliente ────────────────────────────
            $partes   = explode(' ', trim($nombreCliente), 2);
            $customer = [
                'first_name' => $partes[0],
                'last_name'  => $partes[1] ?? '',
                'country'    => 'ES',
            ];
            if (!empty($emailCliente)) {
                $customer['email'] = $emailCliente;
            }

            // ── 3. Crear factura ─────────────────────────────────────────
            $facturaPayload = [
                'currency'       => 'EUR',
                'date'           => date('Y-m-d'),
                'payment_method' => $metodoPagoQ,
                'notes'          => 'Ticket BeLoyal · Venta #' . $idVenta,
                'contact'        => $customer,
                'items'          => $itemsQ,
                'po_number'      => 'venta_' . $idVenta . '_' . time(),
            ];

            $respuesta = $this->request('POST', '/invoices', $facturaPayload);

            if (!$respuesta['ok']) {
                return ['ok' => false, 'error' => $respuesta['error'] ?? 'Error al crear factura en Quaderno'];
            }

            $factura     = $respuesta['data'];
            $facturaId   = $factura['id'] ?? null;
            $quaderno_id = $facturaId;

            // ── 4. Enviar por email si hay dirección ─────────────────────
            if (!empty($emailCliente) && $facturaId) {
                $deliver = $this->request('GET', '/invoices/' . $facturaId . '/deliver');
                if (!$deliver['ok']) {
                    $this->request('POST', '/invoices/' . $facturaId . '/deliver');
                }
            }

            return [
                'ok'          => true,
                'quaderno_id' => $quaderno_id,
                'factura'     => $factura,
            ];

        } catch (\Exception $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    private function normalizarMetodoPago(string $metodo): string
    {
        $metodo = strtolower($metodo);
        if (str_contains($metodo, 'efectivo')) return 'cash';
        if (str_contains($metodo, 'tarjeta'))  return 'credit_card';
        if (str_contains($metodo, 'google'))   return 'credit_card';
        if (str_contains($metodo, 'apple'))    return 'credit_card';
        return 'credit_card';
    }

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
        if (is_array($msg)) {
            $msg = implode(', ', array_map(
                fn($k, $v) => "$k: " . (is_array($v) ? implode(', ', $v) : $v),
                array_keys($msg), $msg
            ));
        }

        return ['ok' => false, 'error' => $msg];
    }
}