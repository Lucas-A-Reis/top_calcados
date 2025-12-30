<?php
require_once 'src/database/conecta.php';

$email_cliente = "teste@cliente.com";
$valor = 199.90;
$status = "pendente";

try {
    $sql = "INSERT INTO pedidos (cliente_email, valor_total, status_pagamento) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([$email_cliente, $valor, $status]);

    echo "Sucesso! Pedido de teste gravado no banco. ID: " . $pdo->lastInsertId();
} catch (Exception $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}