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