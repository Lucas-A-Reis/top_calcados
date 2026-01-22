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

function buscarImagensPorVariacaoId(PDO $pdo, int $variacao_id): array {
    try {
        $sql = "SELECT * FROM imagens WHERE variacao_id = :variacao_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':variacao_id', $variacao_id, PDO::PARAM_INT);
        $stmt->execute();

        $imagens = [];
        while ($linha = $stmt->fetch()) {
            $imagens[] = new Imagem(
                $linha['variacao_id'],
                $linha['arquivo'],
                $linha['id']
            );
        }

        return $imagens;

    } catch (PDOException $e) {
        error_log("Erro ao buscar imagens: " . $e->getMessage());
        return [];
    }
}