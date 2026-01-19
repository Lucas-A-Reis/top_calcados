<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/services/adminServico.php';

$sql = "SELECT id, nome, senha FROM admins WHERE email = :email AND status = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => $email]);
$admin = $stmt->fetch();

if ($admin && password_verify($senha, $admin['senha'])) {

    $codigo = gerarCodigo2FA($pdo, $admin['id']);

    $assunto = "Código de Segurança - Painel Top Calçados";
    $corpo = "
        <h2>Autenticação de Dois Fatores</h2>
        <p>Olá, <strong>{$admin['nome']}</strong>.</p>
        <p>Seu código de acesso ao painel administrativo é:</p>
        <h1 style='background: #f4f4f4; padding: 10px; text-align: center; letter-spacing: 5px;'>$codigo</h1>
        <p>Este código expira em 10 minutos.</p>
    ";

    if (enviarEmail($email, $assunto, $corpo)) {
        session_start();
        $_SESSION['auth_admin_id'] = $admin['id'];

        header("Location: verificar_2fa.php");
        exit();
        
    } else {
        header("Location: login.php?erro=falha_email");
        exit();
    }
} else {
    header("Location: login.php?erro=dados_invalidos");
    exit();
}