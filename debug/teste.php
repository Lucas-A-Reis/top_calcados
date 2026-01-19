<?php
// require_once '../src/database/conecta.php';

// $email_cliente = "teste@cliente.com";
// $valor = 199.90;
// $status = "pendente";

// try {
//     $sql = "INSERT INTO pedidos (cliente_email, valor_total, status_pagamento) VALUES (?, ?, ?)";
//     $stmt = $pdo->prepare($sql);
    
//     $stmt->execute([$email_cliente, $valor, $status]);

//     echo "Sucesso! Pedido de teste gravado no banco. ID: " . $pdo->lastInsertId();
// } catch (Exception $e) {
//     echo "Erro ao salvar: " . $e->getMessage();
// }
date_default_timezone_set('America/Sao_Paulo');

$data = new DateTime();
    $data->modify('+1 hour');
    echo $data->format('Y-m-d H:i:s');