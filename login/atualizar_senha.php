<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($nova_senha !== $confirma_senha) {
        header("Location: redefinir_senha.php?token=$token&erro=senhas_diferentes");
        exit();
    }

    $sql = "SELECT email FROM recuperacao_senhas 
            WHERE token = :token AND usado = 0 AND data_expiracao > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token' => $token]);
    $solicitacao = $stmt->fetch();

    if ($solicitacao) {
        $email = $solicitacao['email'];
        
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $pdo->beginTransaction();

        try {

            $sqlUser = "UPDATE clientes SET senha = :senha WHERE email = :email";
            $stmtUser = $pdo->prepare($sqlUser);
            $stmtUser->execute([':senha' => $nova_senha_hash, ':email' => $email]);

            $sqlToken = "UPDATE recuperacao_senhas SET usado = 1 WHERE token = :token";
            $stmtToken = $pdo->prepare($sqlToken);
            $stmtToken->execute([':token' => $token]);

            $pdo->commit();
            header("Location: login.php?status=senha_alterada");
            exit();

        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: recuperar_senha.php?status=falha_sistema");
            exit();
        }
    } else {
        header("Location: recuperar_senha.php?status=token_expirado");
        exit();
    }
}