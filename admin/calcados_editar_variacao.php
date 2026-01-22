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
if ($id <= 0) {
    header("Location: calcados_gerenciar_variacoes.php?erro=variacao_nao_encontrada");
    exit();
}

$variacao = buscarVariacaoPorId($pdo, $id);

$imagens = buscarImagensPorVariacaoId($pdo, $id);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Editar Variação</title>
</head>

<body class="admin">
    <?php include '../includes/cabecalho_admin.php'; ?>

    <h2>Editar Variação</h2>

    <form class="form" action="calcados_editar_variacao.php" method="POST" enctype="multipart/form-data">

        <div class="grid">
            <h3>Tamanho</h3>
            <input type="number" name="tamanho" value="<?php echo htmlspecialchars($variacao->getTamanho()); ?>"
                required>
        </div>

        <div class="grid">
            <h3>Cor</h3>
            <section class="campos-cor">
                <input type="color" name="cor_hex" value="#551A88" required>
                <input type="text" name="cor" placeholder="Nome da cor (ex: Azul Royal)" required>
            </section>
        </div>

        <h3 style="margin-bottom: 40px;">Imagens</h3>
        <div class="imagens-editar">
            <?php foreach ($imagens as $index => $imagem): ?>
                <div class="campo-imagem-editar">
                    <div class="container-imagem-editar">
                        <img class="preview-img" src="../media/img/calcados/<?php echo htmlspecialchars($imagem->getCaminhoArquivo()); ?>"
                            width="100">

                        <input type="file" name="imagem<?php echo $index + 1; ?>" class="input-img" id="imagem<?php echo $index + 1; ?>"
                            accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">

                        <label for="imagem<?php echo $index + 1; ?>">
                            Trocar Imagem
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php include '../includes/alerta_de_erro.php'; ?>

    </form>

    <form class="form" action="">
        <h3 style="margin-bottom: 40px;">Adicionar mais Imagens</h3>
        <?php for ($i = count($imagens); $i < 3; $i++): ?>
            <div class="campo-imagem-editar">
                <div class="container-imagem-editar">
                    <img class="preview-img" src="../media/img/sem_imagem.png" width="100">

                    <input type="file" name="imagem<?php echo $i + 1; ?>" class="input-img" id="imagem<?php echo $i + 1; ?>"
                        accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">

                    <label for="imagem<?php echo $i + 1; ?>">
                        Adicionar Imagem
                    </label>
                </div>
            </div>
        <?php endfor; ?>
    </form>

<script>
    document.querySelectorAll('.input-img').forEach(input => {
    input.addEventListener('change', function() {

        const arquivo = this.files[0];
        const previewImg = this.closest('.container-imagem-editar').querySelector('.preview-img');

        if (arquivo) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
            }

            reader.readAsDataURL(arquivo);
        }
    });
})
</script>
</body>

</html>