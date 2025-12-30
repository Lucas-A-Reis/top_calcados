<?php
error_reporting(0);
header('Content-Type: application/json');

require_once 'config.php';
require_once 'src/database/conecta.php';
require_once 'src/services/pedidoServico.php';
require_once 'src/helpers/funcoes_uteis.php';

use MercadoPago\Client\Payment\PaymentClient;

$body = json_decode(file_get_contents('php://input'), true);

if ($body) {
    $body = sanitizarDados($body);
}

if (!$body) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Erro 400. Dados não foram recebidos corretamente."]);
    exit;
}

$client = new PaymentClient();

try {

    $payment = $client->create([
        "transaction_amount" => (float) $body['transaction_amount'],
        "token" => $body['token'] ?? null,
        "description" => "Compra na Top Calçados",
        "installments" => (int) ($body['installments'] ?? 1),
        "payment_method_id" => $body['payment_method_id'],
        "payer" => [
            "email" => $body['payer']['email'],
            "first_name" => $body['payer']['first_name'] ?? "Nome",
            "last_name" => $body['payer']['last_name'] ?? "Sobrenome", 
            "identification" => [
                "type" => $body['payer']['identification']['type'] ?? null,
                "number" => $body['payer']['identification']['number'] ?? null,
            ]
        ]
    ]);

    if (!$pdo) {
        die(json_encode(["status" => "error", "message" => "Conexão PDO não existe!"]));
    }

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
        "qr_code_base64" => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
        "transaction_details" => [
            "external_resource_url" => $payment->transaction_details->external_resource_url ?? null,
            "barcode" => [
                "content" => $payment->transaction_details->barcode->content ?? null
            ]
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}