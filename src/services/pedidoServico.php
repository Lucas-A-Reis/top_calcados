<?php

function salvarPedido($pdo, $dados)
{

    try {
        
        $sql = "INSERT INTO pedidos (cliente_email, valor_total, status_pagamento, psp_id, metodo_pagamento) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $dados['email'],
            $dados['valor'],
            $dados['status'],
            $dados['pagamento_id'],
            $dados['metodo']
        ]);
    } catch (PDOException $e) {
        error_log("Erro ao salvar pedido: " . $e->getMessage());
        return false;
    }
}