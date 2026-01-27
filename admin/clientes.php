<?php
require_once 'autenticacao.php';
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/helpers/funcoes_uteis.php';
require_once '../src/services/clienteServico.php';
$clientes = buscarClientes($pdo);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .tabela td,
        .tabela th {
            width: 20%;
            text-align: center;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 1%;

        }
    </style>
    <title>Top Calçados - Clientes</title>
</head>

<body style="height: 100vh" class="admin">

    <?php include '../includes/cabecalho_admin.php'; ?>
    <h2>Lista de Clientes</h2>
    <main style="padding-bottom: 0px; width: 100%; height: 100%;" class="container-tabela">
        <table style="width: 100%; height: 100%;" class="tabela">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>ID</th>
                    <th>Mais informações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['nome']); ?></td>
                        <td><?= htmlspecialchars($cliente['email']); ?></td>
                        <td><a href="<?= 'tel:+55' . $cliente['telefone']; ?>"> <?= $cliente['telefone']; ?> </a></td>
                        <td><?= $cliente['id']; ?></td>
                        <td><a class="btn-roxo" href="clientes_pedidos.php">Pedidos</a><a style="margin-left:10px;" class="btn-roxo" href="clientes_enderecos">Endereços</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>