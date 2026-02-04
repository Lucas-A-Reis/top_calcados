<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/services/variacaoServico.php';
require_once  '../src/helpers/funcoes_uteis.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$modelo_id = isset($_GET['modelo_id']) ? (int) $_GET['modelo_id'] : 0;

if ($id > 0) {
    if (excluirVariacao($pdo, $id)) {
        registrar($pdo, $_SESSION['admin_id'], 'DELETE', 'variacoes_calcados', $idVariacao);
        header("Location: calcados_gerenciar_variacoes.php?id=$modelo_id&sucesso=3");
    } else {
        header("Location: calcados_gerenciar_variacoes.php?id=$modelo_id&erro=2");
    }
} else {
    header("Location: calcados_gerenciar_modelos.php");
}
exit();