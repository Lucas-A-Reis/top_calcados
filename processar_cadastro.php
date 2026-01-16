<?php
require_once 'config.php';
require_once 'src/database.php';
require_once 'src/models/cliente.php';
require_once 'src/services/clienteServico.php';
require_once 'src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = new Cliente();

    $cliente->setNome(sanitizar($_POST['nome'], 'string'));
    $cliente->setEmail(sanitizar($_POST['email'], 'email'));
    $cliente->setTelefone(sanitizar($_POST['telefone'], 'telefone'));
    $cliente->setSenha(sanitizar($_POST['senha'], 'none'));

    if (cadastrarCliente($pdo, $cliente)) {
        header("Location: area_do_usuario.php");
    } else {
        echo "Ocorreu um erro ao processar seu cadastro.";
    }
}