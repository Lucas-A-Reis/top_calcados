<?php
session_start();
require_once '../src/services/clienteServico.php';
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/helpers/funcoes_uteis.php';

if (isset($_SESSION['cliente_id'])){
    $cliente = buscarClientePorId($pdo, $_SESSION['cliente_id']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Contato </title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <?php include '../includes/cabecalho.php'; ?>
    <main class="login">

        <form class="form" action="processar_contato.php?front=contato.php" method="POST">
            <h2 style="margin-bottom: 20px;">Envie-nos uma mensagem</h2>

            <div class="campo_entrada">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Seu nome" value="<?= $cliente['nome'] ?? '' ?>" required>
            </div>

            <div class="campo_entrada">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="exemplo@email.com" value="<?= $cliente['email'] ?? '' ?>" required>
            </div>

            <div class="campo_entrada">
                <label for="telefone">Telefone / WhatsApp</label>
                <input type="tel" id="telefone" name="telefone" placeholder="(37) 99999-0000" value="<?= (isset($cliente)) ? formatarTelefone($cliente['telefone']) : ''; ?>" required>
            </div>

            <div class="campo_entrada">
                <label for="mensagem">Mensagem</label>
                <textarea id="mensagem" name="mensagem" placeholder="Digite aqui sua mensagem..." required></textarea>
            </div>

            <?php include '../includes/captcha.php'; ?>

            <button style="margin-bottom: 10px;" type="submit" class="btn_acessar">Enviar</button>

            <?php switch ($_GET['status'] ?? ''):
                case 'enviado': ?>
                    <div class="alerta-sucesso sumir">Sua mensagem foi enviada com sucesso!</div>
                <?php break;
                case 'falha_envio': ?>
                    <div class="alerta-erro sumir">Falha ao enviar a menssagem. Tente novamente</div>
            <?php break;
            endswitch; ?>

        </form>
    </main>
    <?php include '../includes/rodape.html'; ?>
    <script>
        if (window.history.replaceState) {

            const parametros = new URLSearchParams(window.location.search);
            parametros.delete('status');
            parametros.delete('erro_captcha');
            const novaQuery = parametros.toString() ? '?' + parametros.toString() : '';
            const url = window.location.pathname + novaQuery;
            window.history.replaceState({}, document.title, url);
        }

        const mensagem = document.getElementsByClassName('sumir');

        if (mensagem.length > 0) {
            setTimeout(() => {
                mensagem[0].style.opacity = '0';
                setTimeout(() => {
                    mensagem[0].remove();
                }, 2000);
            }, 3000);
        }
    </script>
</body>

</html>