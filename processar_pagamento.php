<?php
error_reporting(0);
header('Content-Type: application/json');

require_once 'config.php';
require_once 'src/database/conecta.php';
require_once 'src/services/pedidoServico.php';

use MercadoPago\Client\Payment\PaymentClient;


$body = json_decode(file_get_contents('php://input'), true);

$client = new PaymentClient();

try {

    $payment = $client->create([
        "transaction_amount" => (float) $body['transaction_amount'],
        "token" => $body['token'],
        "description" => "Compra na Top Calçados",
        "installments" => (int) $body['installments'],
        "payment_method_id" => $body['payment_method_id'],
        "payer" => [
            "email" => $body['payer']['email'],
        ]
    ]);

    salvarPedido($pdo, [
        'email' => $payment->payer->email,
        'valor' => $payment->transaction_amount,
        'status' => $payment->status,
        'pagamento_id' => (string) $payment->id,
        'metodo' => $payment->payment_method_id
    ]);

    echo json_encode([
        "status" => $payment->status,
        "id" => $payment->id,
        "qr_code" => $payment->point_of_interaction->transaction_data->qr_code ?? null,
        "qr_code_base64" => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}