<?php 

function inserirImagem($pdo, Imagem $imagem) {
    try {
        $sql = "INSERT INTO imagens (variacao_id, arquivo) 
                VALUES (:variacao_id, :caminho_arquivo)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':variacao_id', $imagem->getVariacaoId());
        $stmt->bindValue(':caminho_arquivo', $imagem->getCaminhoArquivo());

        $stmt->execute();

        return true;

    } catch (PDOException $e) {
        error_log("Erro ao inserir imagem: " . $e->getMessage());
        return false;
    }
}