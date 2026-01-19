<?php

require_once 'autenticacao.php';
?>

<h1>Bem-vindo ao Painel, <?php echo htmlspecialchars($_SESSION['admin_nome']); ?></h1>