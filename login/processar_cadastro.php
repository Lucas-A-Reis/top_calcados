<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/models/cliente.php';
require_once '../src/services/clienteServico.php';
require_once '../src/helpers/funcoes_uteis.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    validarCaptcha();

    $cliente = new Cliente();

    $cliente->setNome(sanitizar($_POST['nome'], 'string'));
    $cliente->setEmail(sanitizar($_POST['email'], 'email'));
    $cliente->setTelefone(sanitizar($_POST['telefone'], 'telefone'));
    $cliente->setSenha(sanitizar($_POST['senha'], 'none'));

    if (cadastrarCliente($pdo, cliente: $cliente)) {
        session_start();
        $id = $pdo->lastInsertId();
        $_SESSION['cliente_id'] = $id;
        $_SESSION['cliente_nome'] = $cliente->getNome();
        header("Location: area_do_usuario.php");
    } else {
        echo "Ocorreu um erro ao processar seu cadastro.";
    }
}