<?php
require_once '../checkout/config.php';
#require_once 'autenticacao.php';
require_once '../src/database/conecta.php';
require_once '../src/models/variacao.php';
require_once '../src/models/imagem.php';
require_once '../src/services/imagemServico.php';
require_once '../src/services/variacaoServico.php';
require_once '../src/helpers/funcoes_uteis.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

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

    if (empty($erros)) {

        $variacao = new Variacao($id, $tamanho, $cor_hex, $cor);

        if (inserirVariacao($pdo, $variacao)) {
            $id_variacao = (int) $pdo->lastInsertId();

            foreach ($imagens as $imagem) {

                $nome_imagem = upload($imagem);

                if ($nome_imagem) {

                    $imagemModel = new Imagem($id_variacao, $nome_imagem);
                    inserirImagem($pdo, $imagemModel);
                }
            }
        }
    } else {
        $erros[] = "Erro ao cadastrar a variação. Por favor, tente novamente.";
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

<?php include '../includes/cabecalho_admin.php'; ?>

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
                <input type="color" name="cor_hex" value="#551A88" required>
                <input type="text" name="cor" placeholder="Nome da cor (ex: Azul Royal)" required>
            </section>
        </div>


        <h3 style="margin-bottom: 40px;">Adicionar Imagens</h3>
        <div class="imagens-editar">

            <div class="campo-imagem-editar">
                <div class="container-imagem-editar">
                    <img class="preview-img" src="../media/img/logo.webp" width="100">

                    <input type="file" name="imagem" class="input-img" id="imagem"
                        accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">

                    <label for="imagem">
                        Adicionar Imagem
                    </label>
                </div>
            </div>

            <div class="campo-imagem-editar">
                <div class="container-imagem-editar">
                    <img class="preview-img" src="../media/img/logo.webp" width="100">

                    <input type="file" name="imagem2" class="input-img" id="imagem2"
                        accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">

                    <label for="imagem2">
                        Adicionar Imagem
                    </label>
                </div>
            </div>

            <div class="campo-imagem-editar">
                <div class="container-imagem-editar">
                    <img class="preview-img" src="../media/img/logo.webp" width="100">

                    <input type="file" name="imagem3" class="input-img" id="imagem3"
                        accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">

                    <label for="imagem3">
                        Adicionar Imagem
                    </label>
                </div>
            </div>

        </div>

        <button style="margin-top: 10px;" class="btn_acessar" type="submit">Adicionar</button>

        <?php include '../includes/alerta_de_erro.php'; ?>

    </form>

    <h2 style="margin-top: 40px;">Variações Cadastrados</h2>

    <?php var_dump($listaVariacoes = buscarVariacoesPorModelo($pdo, $id)) ?>

    <?php if ($listaVariacoes): ?>

        <div class="container-tabela compacta">
            <table class="tabela">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tamanho</th>
                        <th>Cor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaVariacoes as $v): ?>
                        <tr>
                            <td><?= htmlspecialchars($v->getId()) ?></td>
                            <td><?= htmlspecialchars($v->getTamanho()) ?></td>
                            <td style="color: <?= htmlspecialchars($v->getCorHex()) ?>"><?= htmlspecialchars($v->getCor()) ?></td>
                            <td>
                                <a class ="btn-editar" href="calcados_editar_variacao.php?id=<?= $v->getId() ?>">Editar</a>
                                <a class="btn-add" href="">Imagens</a>
                                <a class="btn-excluir" href="">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <p class="alerta-erro">Nenhuma variação cadastrada ainda</p>
    <?php endif ?>

    <script src="js/alertas.js"></script>
</body>

</html>