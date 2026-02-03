<?php
require_once 'autenticacao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Painel Administrativo</title>
</head>

<body style="height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; ">
    <h1 id="titulo_painel">Bem-vindo ao Painel,
        <?= htmlspecialchars($_SESSION['admin_nome']); ?>
    </h1>
    <nav id="menu_admin">
        <ul>
            <li><a href="calcados_gerenciar_modelos.php">Calçados</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="historico.php">Histórico</a></li>
            <li><a href="logout.php">Sair</a></li>
            <li><a href="calcados_imagens_carrossel.php">Sair</a></li>
        </ul>
    </nav>
</body>

</html>