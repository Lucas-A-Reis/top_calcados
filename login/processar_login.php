<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/models/cliente.php';
require_once '../src/services/clienteServico.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizar($_POST['email'], 'email');
    $senha = sanitizar($_POST['senha'], 'none');

    $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    
    if ($stmt->fetch()) {
        require_once 'processar_login_admin.php';
        exit();
    }

    $clienteDados = buscarClientePorEmail($pdo, $email);

    if ($clienteDados && password_verify($senha, $clienteDados['senha'])) {
        session_start();
        $_SESSION['cliente_id'] = $clienteDados['id'];
        $_SESSION['cliente_nome'] = $clienteDados['nome'];
        header("Location: area_do_usuario.php");
    } else {
        header("Location: login.php?erro=1");
        exit();
    }
}
