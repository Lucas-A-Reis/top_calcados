<?php 

class Imagem {
    private ?int $id;
    private int $variacao_id;
    private string $caminho_arquivo;

    public function __construct(int $variacao_id, string $caminho_arquivo, ?int $id = null) {
        $this->variacao_id = $variacao_id;
        $this->caminho_arquivo = $caminho_arquivo;
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function getVariacaoId(): int { return $this->variacao_id; }
    public function getCaminhoArquivo(): string { return $this->caminho_arquivo; }

    public function setId(int $id): void { $this->id = $id; }
}