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

<body>
    <?php include '../includes/cabecalho_admin.php'; ?>

    <h2>Editar Variação</h2>

    <form class="form" action="editar_variacao.php" method="POST" enctype="multipart/form-data">
        <div class="grid">
            <h3>Tamanho</h3>
            <input type="number" name="tamanho" value="<?php echo htmlspecialchars($variacao->getTamanho()); ?>"
                required>

            <div class="grid">
                <h3>Cor</h3>
                <section class="campos-cor">
                    <input type="color" name="cor_hex" value="#551A88" required>
                    <input type="text" name="cor" placeholder="Nome da cor (ex: Azul Royal)" required>
                </section>
            </div>

            <div class="grid">
                <h3>Imagens</h3>
                <?php foreach ($imagens as $index => $imagem): ?>
                    <div>
                        <img src="../media/img/calcados/<?php echo htmlspecialchars($imagem->getCaminhoArquivo()); ?>"  width="100">
                        <label for="imagem<?php echo $index + 1; ?>">Alterar imagem <?php echo $index + 1; ?>:</label>
                        <input type="file" name="imagem<?php echo $index + 1; ?>"
                            accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">
                    </div>
                <?php endforeach; ?>
            </div>

            <?php include '../includes/alerta_de_erro.php'; ?>

    </form>
</body>

</html>