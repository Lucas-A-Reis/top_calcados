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

function buscarPedido($pdo, $psp_id): array | false
{
    $sql_busca = "SELECT psp_id, status_pagamento FROM pedidos WHERE psp_id = :psp_id";
    $stmt_busca = $pdo->prepare($sql_busca);
    $stmt_busca->execute([':psp_id' => (string) $psp_id]);
    return $stmt_busca->fetch();
}

function atualizarStatusPedido($pdo, $psp_id, $novo_status): bool
{
    $sql = "UPDATE pedidos SET status_pagamento = :novo_status WHERE psp_id = :psp_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':novo_status' => $novo_status,
        ':psp_id' => (string) $psp_id
    ]);
}

function buscarPedidos($pdo){
    $sql = 'SELECT pedidos.*, clientes.nome FROM pedidos
            JOIN clientes WHERE clientes.id = pedidos.clientes_id
            ORDER BY data_criacao DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}