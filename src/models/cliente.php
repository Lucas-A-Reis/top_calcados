<?php
class Cliente {
    private $nome;
    private $email;
    private $senha;
    private $telefone;

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = $senha; }

    public function getTelefone() { return $this->telefone; }
    public function setTelefone($telefone) { $this->telefone = $telefone; }
}
?>