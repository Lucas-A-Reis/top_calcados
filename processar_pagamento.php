<?php
error_reporting(0); 
header('Content-Type: application/json');
require_once 'config.php';
require_once 'src/database/conecta.php';

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

    $sql = "INSERT INTO pedidos (cliente_email, valor_total, status_pagamento, psp_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $payment->payer->email,
        $payment->transaction_amount,
        $payment->status,
        (string) $payment->id
    ]);


    echo json_encode([
        "status" => $payment->status,
        "id" => $payment->id
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}