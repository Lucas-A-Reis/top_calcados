<?php

function inserirEndereco($pdo, Endereco $endereco)
{
    try {
        $sql = "INSERT INTO enderecos (cliente_id, logradouro, numero, bairro, cidade, uf, cep) 
                VALUES (:cliente_id, :logradouro, :numero, :bairro, :cidade, :uf, :cep)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':cliente_id', $endereco->getClienteId(), PDO::PARAM_INT);
        $stmt->bindValue(':logradouro', $endereco->getLogradouro(), PDO::PARAM_STR);
        $stmt->bindValue(':numero',     $endereco->getNumero(),     PDO::PARAM_INT);
        $stmt->bindValue(':bairro',     $endereco->getBairro(),     PDO::PARAM_STR);
        $stmt->bindValue(':cidade',     $endereco->getCidade(),     PDO::PARAM_STR);
        $stmt->bindValue(':uf',         $endereco->getUf(),         PDO::PARAM_STR);
        $stmt->bindValue(':cep',        $endereco->getCep(),        PDO::PARAM_STR);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Erro ao inserir endereço: " . $e->getMessage());
        return false;
    }
}

function listarEnderecos($pdo, $cliente_id)
{
    try {
        $sql = "SELECT id, cliente_id, logradouro, numero, bairro, cidade, uf, cep 
                FROM enderecos 
                WHERE cliente_id = :cliente_id 
                ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cliente_id', (int)$cliente_id, PDO::PARAM_INT);
        $stmt->execute();

        $resultados = $stmt->fetchAll();
        $listaEnderecos = [];

        foreach ($resultados as $res) {
            $listaEnderecos[] = new Endereco(
                $res['cliente_id'],
                $res['logradouro'],
                $res['numero'],
                $res['bairro'],
                $res['cidade'],
                $res['uf'],
                $res['cep'],
                $res['id']
            );
        }

        return $listaEnderecos;
    } catch (PDOException $e) {
        error_log("Erro ao listar endereços: " . $e->getMessage());
        return [];
    }
}
