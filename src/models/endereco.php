<?php

class Endereco {
    private ?int $id;
    private int $cliente_id;
    private string $logradouro;
    private int $numero;
    private string $bairro;
    private string $cidade;
    private string $uf;
    private string $cep;

    public function __construct($cliente_id, $logradouro, $numero, $bairro, $cidade, $uf, $cep, $id = null) {
        $this->id = $id;
        $this->cliente_id = $cliente_id;
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->uf = $uf;
        $this->cep = $cep;
    }

    public function getId(): ?int { return $this->id; }
    public function getClienteId(): int { return $this->cliente_id; }
    public function getLogradouro(): string { return $this->logradouro; }
    public function getNumero(): int { return $this->numero; }
    public function getBairro(): string { return $this->bairro; }
    public function getCidade(): string { return $this->cidade; }
    public function getUf(): string { return $this->uf; }
    public function getCep(): string { return $this->cep; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setClienteId(int $cliente_id): void { $this->cliente_id = $cliente_id; }
    public function setLogradouro(string $logradouro): void { $this->logradouro = $logradouro; }
    public function setNumero(int $numero): void { $this->numero = $numero; }
    public function setBairro(string $bairro): void { $this->bairro = $bairro; }
    public function setCidade(string $cidade): void { $this->cidade = $cidade; }
    public function setUf(string $uf): void { $this->uf = $uf; }
    public function setCep(string $cep): void { $this->cep = $cep; }
}