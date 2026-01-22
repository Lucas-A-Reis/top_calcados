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

function buscarVariacaoPorId(PDO $pdo, int $id): ?Variacao {
    try {
        $sql = "SELECT * FROM variacoes_calcado WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($linha) {
            return new Variacao(
                $linha['modelo_id'],
                $linha['tamanho'],
                $linha['cor_hex'],
                $linha['cor'],
                $linha['id']
            );
        } else {
            return null;
        }

    } catch (PDOException $e) {
        error_log("Erro ao buscar variação: " . $e->getMessage());
        return null;
    }
}

function atualizarVariacao(PDO $pdo, Variacao $variacao) {
    try {
        $sql = "UPDATE variacoes_calcado 
                SET tamanho = :tamanho, 
                    cor_hex = :cor_hex, 
                    cor = :cor 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':tamanho', $variacao->getTamanho(), PDO::PARAM_INT);
        $stmt->bindValue(':cor_hex', $variacao->getCorHex(), PDO::PARAM_STR);
        $stmt->bindValue(':cor', $variacao->getCor(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $variacao->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao atualizar variação: " . $e->getMessage());
        return false;
    }
}