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