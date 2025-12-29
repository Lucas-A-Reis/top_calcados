<?php
require_once 'config.php'; 

use MercadoPago\Client\Payment\PaymentClient;


$body = json_decode(file_get_contents('php://input'), true);

$client = new PaymentClient();

try {

    $payment = $client->create([
        "transaction_amount" => (float)$body['transaction_amount'],
        "token" => $body['token'],
        "description" => "Compra na Top Calçados",
        "installments" => (int)$body['installments'],
        "payment_method_id" => $body['payment_method_id'],
        "payer" => [
            "email" => $body['payer']['email'],
        ]
    ]);


    echo json_encode([
        "status" => $payment->status,
        "id" => $payment->id
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}