<?php
class Variacao {
    private ?int $id;
    private int $modelo_id;
    private int $tamanho;
    private string $cor_hex;
    private string $cor;

    public function __construct(
        int $modelo_id,
        int $tamanho,
        string $cor_hex,
        string $cor,
        ?int $id = null
    ) {
        $this->modelo_id = $modelo_id;
        $this->tamanho = $tamanho;
        $this->cor_hex = $cor_hex;
        $this->cor = $cor;
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function getModeloId(): int { return $this->modelo_id; }
    public function getTamanho(): int { return $this->tamanho; }
    public function getCorHex(): string { return $this->cor_hex; }
    public function getCor(): string { return $this->cor; }

    public function setId(int $id): void { $this->id = $id; }
}