<?php

require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/services/clienteServico.php';
require_once '../src/helpers/funcoes_uteis.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = sanitizar($_POST['email'], 'email');

    validarCaptcha();

    if (emailExiste($pdo, $email)) {
        $tokenRecuperacao = criarPedidoDeRecuperacao($pdo, $email);

        if ($tokenRecuperacao) {

            $link = "http://localhost/top_calcados/login/redefinir_senha.php?token=" . $tokenRecuperacao;

            $assunto = "Recuperação de Senha - Top Calçados";
            $corpo = "
            <h2 style=\"font-family: Arial-serif;\">Olá,</h2>
            <p style=\"font-family: inter, sans-serif;\">Você solicitou a redefinição de sua senha na <strong>Top Calçados</strong>.</p>
            <p style=\"font-family: inter, sans-serif; margin-bottom: 25px;\">Clique no link abaixo para criar uma nova senha. Este link é válido por 1 hora:</p>
            <p style=\"font-size: 12px; font-family: inter, sans-serif;\"><a href='$link' style='background: #AB73DC; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 10px;'>Redefinir Minha Senha</a></p>
            <p style=\"font-family: inter, sans-serif; margin-top: 25px;\">Se você não solicitou isso, por favor ignore este e-mail.</p>
            ";

            if (enviarEmail($email, $assunto, $corpo)) {
                header("Location: recuperar_senha.php?status=enviado");
                exit();
            } else {
                header("Location: recuperar_senha.php?status=falha_envio");
                exit();
            }
        }
    }
} else {
    header("Location: recuperar_senha.php?status=email_nao_encontrado");
    exit();
}