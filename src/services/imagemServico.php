<?php

function inserirImagem($pdo, Imagem $imagem)
{
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

function buscarImagensPorVariacaoId(PDO $pdo, int $variacao_id): array
{
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

function atualizarImagem($pdo, $id, $arquivo)
{
    try {
        $pdo->beginTransaction();
        $sql = 'SELECT * FROM imagens WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $imagem = $stmt->fetch();

        if (!$imagem) {
            throw new Exception("Imagem não encontrada.");
        }

        $sql = 'UPDATE imagens SET arquivo = :arquivo WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':arquivo' => $arquivo, ':id' => $id]);
        $pdo->commit();

        $caminho = "../media/img/calcados/" . $imagem['arquivo'];
        if (file_exists($caminho)) {
            unlink($caminho);
        }

        return true;

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Erro ao atualizar imagens:" . $e->getMessage());
        return false;
    }
}

function excluirImagem($pdo, $id, $variacao_id)
{
    try {

        $sqlContar = "SELECT COUNT(*) FROM imagens WHERE variacao_id = :variacao_id";
        $stmtContar = $pdo->prepare($sqlContar);
        $stmtContar->execute([':variacao_id' => $variacao_id]);
        $numero_de_Imagens = $stmtContar->fetchColumn();

        if ($numero_de_Imagens <= 1) {
            return "ultima_imagem"; 
        }

        $pdo->beginTransaction();

        $pdo->beginTransaction();

        $sql = "SELECT arquivo FROM imagens WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $imagem = $stmt->fetch();

        if (!$imagem) {
            $pdo->rollBack();
            return false;
        }

        $sqlDelete = "DELETE FROM imagens WHERE id = :id";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute([':id' => $id]);

        $pdo->commit();

        $caminho = "../media/img/calcados/" . $imagem['arquivo'];

        if (!empty($imagem['arquivo']) && file_exists($caminho)) {
            unlink($caminho);
        }

        return 'true';

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Erro ao excluir imagem: " . $e->getMessage());
        return false;
    }
}