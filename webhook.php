<?php
require_once 'config.php';
require_once 'src/database/conecta.php';
require_once 'src/services/pedidoServico.php';

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

MercadoPagoConfig::setAccessToken($_ENV['MP_ACCESS_TOKEN']);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['type']) && $data['type'] === 'payment') {

    $id_do_post = $data['data']['id'] ?? null;

    if ($id_do_post) {
        $client = new PaymentClient();

        try {
            $payment = $client->get($id_do_post);

            $status_da_api = $payment->status;
            // $status_da_api = "approved";

                $pedido = buscarPedido($pdo, (string) $payment->id);

                if ($pedido) {
                    if ($pedido['status_pagamento'] !== $status_da_api) {
                        atualizarStatusPedido($pdo, (string) $payment->id, $status_da_api);
                    }
                }
            // }

        } catch (Exception $e) {
            error_log("Erro Webhook: " . $e->getMessage());
        }
    }
}

http_response_code(200);
exit;