<?php
session_start();
require_once '../checkout/config.php';
require_once  '../src/database/conecta.php';
require_once '../src/models/imagem.php';
require_once '../src/services/imagemServico.php';
require_once '../src/helpers/funcoes_uteis.php';
require_once '../src/models/modelo.php';
require_once '../src/services/modeloServico.php';
require_once '../src/models/variacao.php';
require_once '../src/services/variacaoServico.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$modelo = buscarModeloPorId($pdo, $id);
$variacoes = buscarVariacoesPorModelo($pdo, $id);

foreach ($variacoes as $variacao) {
    $imagens[] = buscarImagensPorVariacaoId($pdo, $variacao->getId());
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $modelo->getMarca() . " " . $modelo->getTipo() ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include '../includes/cabecalho.php'; ?>
    <main>
        <section id="imagens">
            <ul id="outras-imagens"></ul>
            <div id="imagem-principal-container">
                <img id="imagem-principal" src="" alt="">
            </div>
        </section>
        <div id="form-info-container">
            <section id="informacoes_do_calcado">
                <h1>NOME</h1>
                <p>descrição</p>
                <span>preço</span>
            </section>
            <form action="sacola.php" method="POST">
                <div class="container-cores">
                    <h2>Cores</h2>
                    <?php foreach($variacoes as $variacao): ?>
                        <input type="radio" name="cor" value="<?= $variacao->getId() ?>" id="<?= 'cor_'.$variacao->getId() ?>" style="display:none;" >
                        <label for="<?= 'cor_'.$variacao->getId() ?>" style="<?= 'background-color:'.$variacao->getCorHex() ?>" class="bolinha-maior"></label>
                    <?php endforeach ?>
                </div>
                
                <label for="quantidade"></label>
                <input id="quantidade" name="quantidade" type="number">
                <button formaction="" type="submit" class="btn-comprar">Comprar</button>
                <button type="submit" class="btn-sacola">Adicionar à Sacola</button>
            </form>
        </div>
    </main>
    <?php include '../includes/rodape.html'; ?>
</body>

</html>