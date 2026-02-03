<?php
require_once '../checkout/config.php';
require_once 'autenticacao.php';
require_once '../src/database/conecta.php';
require_once '../src/models/variacao.php';
require_once '../src/models/imagem.php';
require_once '../src/services/imagemServico.php';
require_once '../src/services/variacaoServico.php';
require_once '../src/helpers/funcoes_uteis.php';

$id = 38;

$imagens = buscarImagensPorVariacaoId($pdo, $id);


if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $erros = [];

    for ($i = 0; $i < 3; $i++) {

        $campo = "imagem_$i";
        $idImgExistente = $_POST["id_imagem_$i"] ?? null;

        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {

            try {
                $novoNome = upload($_FILES[$campo]);

                if (!$novoNome) {
                    $erros[] = "Erro ao mover a imagem para a pasta do servidor " . ($i + 1);
                } else {
                    if (!atualizarImagem($pdo, (int) $idImgExistente, $novoNome)) {
                        $erros[] = "Erro ao atualizar a imagem " . ($i + 1);
                    } else {
                        registrar($pdo, $_SESSION['admin_id'], 'UPDATE', 'imagens', $idImgExistente);
                    }
                }
            } catch (PDOException $e) {
                $erros[] = $e->getMessage();
                break;
            }
        } elseif (isset($_FILES[$campo]) && $_FILES[$campo]['error'] !== UPLOAD_ERR_NO_FILE) {
            $erros[] = "Erro no upload da imagem " . ($i + 1);
        }
    }
    if (empty($erros)) {
        header("Location: calcados_imagens_carrossel.php?sucesso=4");
        exit();
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Gerenciar Imagens</title>
</head>

<body class="admin">

    <?php include '../includes/cabecalho_admin.php'; ?>

    <form class="form" action="" method="post" enctype="multipart/form-data">
        <h3 style="margin-bottom: 20px;">Gerenciar Carrossel da página inicial</h3>

        <div class="imagens-editar">

            <?php for ($i = 0; $i < 3; $i++):
                $imgExistente = isset($imagens[$i]) ? $imagens[$i] : null;
            ?>

                <div class="campo-imagem-editar">
                    <div class="container-imagem-editar">
                        <img class="preview-img"
                            src="<?= '../media/img/calcados/' . $imgExistente->getCaminhoArquivo(); ?>"
                            width="100">

                        <input type="file" name="imagem_<?= $i ?>" class="input-img" id="img_<?= $i ?>"
                            accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">

                        <input type="hidden" name="id_imagem_<?= $i ?>" value="<?= $imgExistente->getId() ?>">

                        <label for="img_<?= $i ?>">
                            <?= 'Trocar Imagem' ?>
                        </label>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <button style="margin-top: 30px;" class="btn_acessar" type="submit">Salvar Alterações</button>

        <?php $nome = " Imagem ";
        include '../includes/alertas.php'; ?>
    </form>

    <a style="margin-top:40px"
        href="index.php">Voltar</a>

    <script src="js/alertas.js"></script>
</body>

</html>