<?php
function inserirModelo(PDO $pdo, Modelo $modelo) {
    try {
        $sql = "INSERT INTO modelos_calcado (
                    marca, tipo, genero, faixa_etaria, preco, 
                    descricao, slug, destaque, status, 
                    peso, comprimento, largura, altura, formato
                ) VALUES (
                    :marca, :tipo, :genero, :faixa_etaria, :preco, 
                    :descricao, :slug, :destaque, :status, 
                    :peso, :comprimento, :largura, :altura, :formato
                )";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':marca'        => $modelo->getMarca(),
            ':tipo'         => $modelo->getTipo() ?? null,
            ':genero'       => $modelo->getGenero() ?? null,
            ':faixa_etaria' => $modelo->getFaixaEtaria() ?? null,
            ':preco'        => $modelo->getPreco(),
            ':descricao'    => $modelo->getDescricao() ?? null,
            ':slug'         => $modelo->getSlug(),
            ':destaque'     => $modelo->getDestaque() ?? 0,
            ':status'       => $modelo->getStatus() ?? 1,
            ':peso'         => $modelo->getPeso(),
            ':comprimento'  => $modelo->getComprimento(),
            ':largura'      => $modelo->getLargura(),
            ':altura'       => $modelo->getAltura(),
            ':formato'      => $modelo->getFormato() ?? 1
        ]);

        return true;

    } catch (PDOException $e) {
        return false;
    }
}

function listarModelos(PDO $pdo): array {
    $modelos = [];
    
    try {

        $sql = "SELECT * FROM modelos_calcado ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        
        $resultados = $stmt->fetchAll();
        
        foreach ($resultados as $resultado) {
            
            $modelo = new Modelo(
                $resultado['marca'],
                $resultado['tipo'],
                $resultado['genero'],
                $resultado['faixa_etaria'],
                (float)$resultado['preco'],
                $resultado['descricao'],
                $resultado['slug'],
                (int)$resultado['destaque'],
                (int)$resultado['status'],
                (int)$resultado['peso'],
                (int)$resultado['comprimento'],
                (int)$resultado['largura'],
                (int)$resultado['altura'],
                (int)$resultado['formato']
            );
            
            
            $modelo->setId($resultado['id']);
            
            $modelos[] = $modelo;
        }
        
    } catch (PDOException $e) {
        error_log("Erro ao listar modelos: " . $e->getMessage());
    }
    
    return $modelos;
}

function buscarModeloPorId(PDO $pdo, int $id): ?Modelo {
    try {
        $sql = "SELECT * FROM modelos_calcado WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resposta = $stmt->fetch();

        if (!$resposta) {
            return null;
        }

        $modelo = new Modelo(
            $resposta['marca'],
            $resposta['tipo'],
            $resposta['genero'],
            $resposta['faixa_etaria'],
            (float)$resposta['preco'],
            $resposta['descricao'],
            $resposta['slug'],
            (int)$resposta['destaque'],
            (int)$resposta['status'],
            (int)$resposta['peso'],
            (int)$resposta['comprimento'],
            (int)$resposta['largura'],
            (int)$resposta['altura'],
            (int)$resposta['formato']
        );

        $modelo->setId((int)$resposta['id']);

        return $modelo;

    } catch (PDOException $e) {
        error_log("Erro ao buscar modelo por ID: " . $e->getMessage());
        return null;
    }
}

function atualizarModelo(PDO $pdo, Modelo $modelo): bool {
    try {
        $sql = "UPDATE modelos_calcado SET 
                    marca = :marca, 
                    tipo = :tipo, 
                    genero = :genero, 
                    faixa_etaria = :faixa_etaria, 
                    preco = :preco, 
                    descricao = :descricao, 
                    slug = :slug, 
                    destaque = :destaque, 
                    status = :status, 
                    peso = :peso, 
                    comprimento = :comprimento, 
                    largura = :largura, 
                    altura = :altura, 
                    formato = :formato 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':marca', $modelo->getMarca());
        $stmt->bindValue(':tipo', $modelo->getTipo());
        $stmt->bindValue(':genero', $modelo->getGenero());
        $stmt->bindValue(':faixa_etaria', $modelo->getFaixaEtaria());
        $stmt->bindValue(':preco', $modelo->getPreco());
        $stmt->bindValue(':descricao', $modelo->getDescricao());
        $stmt->bindValue(':slug', $modelo->getSlug());
        $stmt->bindValue(':destaque', $modelo->getDestaque(), PDO::PARAM_INT);
        $stmt->bindValue(':status', $modelo->getStatus(), PDO::PARAM_INT);
        $stmt->bindValue(':peso', $modelo->getPeso(), PDO::PARAM_INT);
        $stmt->bindValue(':comprimento', $modelo->getComprimento(), PDO::PARAM_INT);
        $stmt->bindValue(':largura', $modelo->getLargura(), PDO::PARAM_INT);
        $stmt->bindValue(':altura', $modelo->getAltura(), PDO::PARAM_INT);
        $stmt->bindValue(':formato', $modelo->getFormato(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $modelo->getId(), PDO::PARAM_INT);

        return $stmt->execute();

    } catch (PDOException $e) {
        error_log("Erro ao atualizar modelo: " . $e->getMessage());
        return false;
    }
}