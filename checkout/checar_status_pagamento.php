<?php
require_once 'config.php';
require_once '../src/database/conecta.php';
require_once '../src/services/pedidoServico.php';

$psp_id = $_GET['psp_id'] ?? null;

if (!$psp_id) {
    echo json_encode(['status' => 'error', 'message' => 'ID não fornecido']);
    exit;
}

$pedido = buscarPedido($pdo, $psp_id);

if ($pedido) {
    echo json_encode(['status' => $pedido['status_pagamento']]);
} else {
    echo json_encode(['status' => 'not_found']);
}