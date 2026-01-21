<?php

function inserirVariacao(PDO $pdo, Variacao $variacao) {
    try {
        $sql = "INSERT INTO variacoes_calcado (modelo_id, tamanho, cor_hex, cor) 
                VALUES (:modelo_id, :tamanho, :cor_hex, :cor)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':modelo_id', $variacao->getModeloId());
        $stmt->bindValue(':tamanho', $variacao->getTamanho());
        $stmt->bindValue(':cor_hex', $variacao->getCorHex());
        $stmt->bindValue(':cor', $variacao->getCor());

        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        return false;
    }
}