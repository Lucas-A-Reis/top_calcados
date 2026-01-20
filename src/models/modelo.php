<?php
class Modelo
{

    public function __construct(
        string $marca,
        ?string $tipo,
        ?string $genero,
        ?string $faixaEtaria,
        float $preco,
        ?string $descricao,
        string $slug,
        int $destaque,
        int $status,
        int $peso,
        int $comprimento,
        int $largura,
        int $altura,
        int $formato
    ) {
        $this->marca = $marca;
        $this->tipo = $tipo;
        $this->genero = $genero;
        $this->faixaEtaria = $faixaEtaria;
        $this->preco = $preco;
        $this->descricao = $descricao;
        $this->slug = $slug;
        $this->destaque = $destaque;
        $this->status = $status;
        $this->peso = $peso;
        $this->comprimento = $comprimento;
        $this->largura = $largura;
        $this->altura = $altura;
        $this->formato = $formato;
    }
    private ?int $id = null;
    private string $marca;
    private ?string $tipo;
    private ?string $genero;
    private ?string $faixaEtaria;
    private float $preco;
    private ?string $descricao;
    private string $slug;
    private int $destaque;
    private int $status;
    private int $peso;
    private int $comprimento;
    private int $largura;
    private int $altura;
    private int $formato;


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getMarca(): string
    {
        return $this->marca;
    }
    public function setMarca(string $marca): void
    {
        $this->marca = $marca;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }
    public function setTipo(?string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }
    public function setGenero(?string $genero): void
    {
        $this->genero = $genero;
    }

    public function getFaixaEtaria(): ?string
    {
        return $this->faixaEtaria;
    }
    public function setFaixaEtaria(?string $faixaEtaria): void
    {
        $this->faixaEtaria = $faixaEtaria;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }
    public function setPreco(float $preco): void
    {
        $this->preco = $preco;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDestaque(): int
    {
        return $this->destaque;
    }
    public function setDestaque(int $destaque): void
    {
        $this->destaque = $destaque;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getPeso(): int
    {
        return $this->peso;
    }
    public function setPeso(int $peso): void
    {
        $this->peso = $peso;
    }

    public function getComprimento(): int
    {
        return $this->comprimento;
    }
    public function setComprimento(int $comprimento): void
    {
        $this->comprimento = $comprimento;
    }

    public function getLargura(): int
    {
        return $this->largura;
    }
    public function setLargura(int $largura): void
    {
        $this->largura = $largura;
    }

    public function getAltura(): int
    {
        return $this->altura;
    }
    public function setAltura(int $altura): void
    {
        $this->altura = $altura;
    }

    public function getFormato(): int
    {
        return $this->formato;
    }
    public function setFormato(int $formato): void
    {
        $this->formato = $formato;
    }
}