<?php
require_once 'autenticacao.php';
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/services/imagemServico.php';
require_once '../src/helpers/funcoes_uteis.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$variacao_id = isset($_GET['variacao_id']) ? (int) $_GET['variacao_id'] : 0;

if ($id > 0 && $variacao_id > 0) {

    $retorno = excluirImagem($pdo, $id, $variacao_id);

    switch ($retorno) {
        case 'true':
            registrar($pdo, $_SESSION['admin_id'], 'DELETE', 'imagens', $id);
            header("Location: calcados_imagens.php?id=$variacao_id&sucesso=3");
            break;

        case "ultima_imagem":
            header("Location: calcados_imagens.php?id=$variacao_id&erro=3");
            break;

        default:
            header("Location: calcados_imagens.php?id=$variacao_id&erro=2");
            break;
    }
    exit();
}