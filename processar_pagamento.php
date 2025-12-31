<?php
error_reporting(0);
header('Content-Type: application/json');

require_once 'config.php';
require_once 'src/database/conecta.php';
require_once 'src/services/pedidoServico.php';
require_once 'src/helpers/funcoes_uteis.php';

use MercadoPago\Client\Payment\PaymentClient;

$body = json_decode(file_get_contents('php://input'), true);

if (!$body) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Erro 400. Dados não foram recebidos corretamente."]);
    exit;
}

$client = new PaymentClient();

try {

    $payerData = $body['payer'];
    $address = $payerData['address'];

    $uf = (strlen($address['federal_unit']) > 2) ? "MG" : $address['federal_unit'];

    $payment = $client->create([
        "transaction_amount" => (float) $body['transaction_amount'],
        "description" => "Compra na Top Calçados",
        // "installments" => $body['installments'],
        "payment_method_id" => $body['payment_method_id'],
        // "issuer_id" => $body['issuer_id'],
        "payer" => [
            "email" => $payerData['email'],
            "first_name" => $payerData['first_name'],
            "last_name" => $payerData['last_name'],
            "identification" => [
                "type" => $payerData['identification']['type'],
                "number" => $payerData['identification']['number']
            ],
            "address" => [
                "zip_code" => preg_replace('/[^0-9]/', '', $address['zip_code']),
                "street_name" => $address['street_name'],
                "street_number" => $address['street_number'],
                "neighborhood" => $address['neighborhood'],
                "city" => $address['city'],
                "federal_unit" => $uf
            ]
        ]
    ]);

    $json_completo = json_encode($payment, JSON_PRETTY_PRINT);
    file_put_contents('debug_pagamento.json', $json_completo);

    if (!$pdo) {
        die(json_encode(["status" => "error", "message" => "Conexão PDO não existe."]));
    }

    $dados = sanitizarDados([
        'email' => $payment->payer->email,
        'valor' => $payment->transaction_amount,
        'status' => $payment->status,
        'pagamento_id' => (string) $payment->id,
        'metodo' => $payment->payment_method_id
    ]);

    salvarPedido($pdo, $dados);

    $res = [
        "id" => $payment->id,
        "status" => $payment->status,
        "qr_code" => $payment->point_of_interaction->transaction_data->qr_code ?? null,
        "qr_code_base64" => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
        // "external_resource_url" => $payment->transaction_details->external_resource_url,
        // "digitable_line" => $payment->point_of_interaction->transaction_details->digitable_line
        // "body" => var_dump($body)
    ];

    echo json_encode($res);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(),
        // "body" => var_dump($body)
    ]);

}