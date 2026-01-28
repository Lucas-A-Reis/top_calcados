<?php
require_once 'autenticacao.php';
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/helpers/funcoes_uteis.php';
require_once '../src/services/pedidoServico.php';

$clientes = buscarPedidos($pdo);
?>

