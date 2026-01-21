<?php
require_once '../checkout/config.php';
#require_once 'autenticacao.php';
require_once '../src/database/conecta.php';
require_once '../src/models/variacao.php';
require_once '../src/models/imagem.php';
require_once '../src/services/imagemServico.php';
require_once '../src/services/variacaoServico.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $erros = [];

    $tamanho = sanitizar($_POST['tamanho'] ?? '', 'int');
    $cor_hex = sanitizar($_POST['cor_hex'] ?? '', 'string');
    $cor = sanitizar($_POST['cor'] ?? '', 'string');

    if ($tamanho <= 0) {
        $erros[] = "O tamanho deve ser um valor positivo.";
    }

    $imagens = [];

    $campos_fotos = ['imagem', 'imagem2', 'imagem3'];

    foreach ($campos_fotos as $campo) {
        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
            $imagens[] = $_FILES[$campo];
        }
    }

    if (count($imagens) === 0) {
        $erros[] = "Pelo menos uma imagem deve ser enviada.";
    }

    $flag = false;

if (empty($erros)) {

        $variacao = new Variacao(10, $tamanho, $cor_hex, $cor);

        if (inserirVariacao($pdo, $variacao)) {
            $id_variacao = (int)$pdo->lastInsertId();

            foreach ($imagens as $imagem) {

                $nome_imagem = upload($imagem);

                if ($nome_imagem) {

                    $imagemModel = new Imagem($id_variacao, $nome_imagem);
                    inserirImagem($pdo, $imagemModel);
                }
            }
            $flag = true;
        }
    }

    if ($flag) {
        header('Location: gerenciar_modelos.php?sucesso=1');
        exit();
    } else {
        $erros[] = "Erro ao cadastrar a variação. Por favor, tente novamente.";
        echo "<div class='erros'><ul>";
        foreach ($erros as $erro) {
            echo "<li>" . htmlspecialchars($erro) . "</li></ul></div>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Gerenciar Variações</title>
</head>

<body class="admin">

    <h2>Cadastrar uma variação</h2>

    <form class="form" action="" method="post" enctype="multipart/form-data">
        <div class="grid">
            <h3>Tamanho</h3>
            <input type="number" name="tamanho" placeholder="Tamanho" step="1" required>
        </div>
        <div class="grid">
            <h3>Cor</h3>
            <section class="campos-cor">
                <input type="color" name="cor_hex" value="#551A88">
                <input type="text" name="cor" placeholder="Nome da cor (ex: Azul Royal)" required>
            </section>
        </div>
        <div class="grid">
            <h3>Imagens</h3>
            <label for="imagem">Selecione uma imagem:</label>
            <input type="file" id="imagem" name="imagem"
                accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp" required>
            <label for="imagem2">Selecione uma segunda imagem:</label>
            <input type="file" id="imagem2" name="imagem2"
                accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">
            <label for="imagem3">Selecione uma terceira imagem:</label>
            <input type="file" id="imagem3" name="imagem3"
                accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">
        </div>

        <button style="margin-top: 10px;" class="btn_acessar" type="submit">Cadastrar Modelo</button>

    </form>

</body>

</html>