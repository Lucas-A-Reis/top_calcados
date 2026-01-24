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

$erros = [];

$variacao = buscarVariacaoPorId($pdo, $id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idVariacao = $id;
    $tamanho = (int) $_POST['tamanho'];
    $corHex = $_POST['cor_hex'];
    $cor = $_POST['cor'];

    if ($tamanho <= 0) {
        $erros[] = "O tamanho deve ser um valor positivo.";
    }

    if(empty($erros)){

    $variacaoParaAtualizar = new Variacao(
        $variacao->getModeloId(),
        $tamanho,
        $corHex,
        $cor,
        $id
    );

    if (atualizarVariacao($pdo, $variacaoParaAtualizar)) {
        header("Location: calcados_gerenciar_variacoes.php?sucesso=1");
        exit();
    } else {
        $erros[] = "Erro ao atualizar no banco de dados.";
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
    <title>Top Calçados - Editar Variação</title>
</head>

<body class="admin">
    <?php include '../includes/cabecalho_admin.php'; ?>

    <h2>Editar Variação</h2>

    <form class="form" action="" method="POST" enctype="multipart/form-data">

        <div class="grid">
            <h3>Tamanho</h3>
            <input type="number" name="tamanho" value="<?php echo htmlspecialchars($variacao->getTamanho()); ?>"
                required>
        </div>

        <div class="grid">
            <h3>Cor</h3>
            <section class="campos-cor">
                <input type="color" name="cor_hex" value="<?= htmlspecialchars($variacao->getCorHex()) ?>">
                <input type="text" name="cor" value="<?= htmlspecialchars($variacao->getCor()) ?>">
            </section>
        </div>

        <button style="margin-top: 10px;" class="btn_acessar" type="submit">Atualizar</button>

        <?php include '../includes/alerta_de_erro.php'; ?>

    </form>
</body>

</html>