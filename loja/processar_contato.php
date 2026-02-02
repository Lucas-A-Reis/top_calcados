<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';
require_once '../checkout/config.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    validarCaptcha();

    $nome     = sanitizar($_POST['nome'], 'string');
    $email    = sanitizar($_POST['email'], 'email');
    $telefone = sanitizar($_POST['telefone'], 'telefone');
    $mensagem = sanitizar(nl2br($_POST['mensagem']), 'string');

    $destinatario = 'aguiarreis@proton.me';
    $assunto = "$nome enviou uma mensagem do site";
    $corpoHtml = "
            <div style='font-family: Arial; line-height: 1.6;'>
                <h2>Nova mensagem de contato</h2>
                <p><strong>Nome:</strong> {$nome}</p>
                <p><strong>E-mail:</strong> {$email}</p>
                <p><strong>Telefone:</strong> {$telefone}</p>
                <hr>
                <p><strong>Mensagem:</strong><br>{$mensagem}</p>
            </div>";

    if (enviarEmail($destinatario, $assunto, $corpoHtml)) {
        header("Location: contato.php?status=enviado");
        exit();
    } else {
        header("Location: contato.php?status=falha_envio");
        exit();
    }
}
