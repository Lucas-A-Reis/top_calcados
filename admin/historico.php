<?php
require_once 'autenticacao.php';
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/helpers/funcoes_uteis.php';
$historico = buscarLogs($pdo);
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
    </style>
    <title>Top Calçados - Histórico</title>
</head>

<body style="height: 100vh" class="admin">
    
    <?php include '../includes/cabecalho_admin.php'; ?>
    <h2>Histórico da Área administrativa</h2>
    <main class="container-tabela" style="width: 100%; height: 100%; padding-bottom: 0px;">
        <table style="width: 100%; height: 100%;" class="tabela">
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Admin</th>
                    <th>Ação</th>
                    <th>Tabela</th>
                    <th style="text-align:center;">ID Afetado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $log): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($log['data_hora'])) ?></td>
                        <td><?= htmlspecialchars($log['admin_nome'] ?? 'Admin não encontrado') ?></td>
                        <td><strong><?= $log['acao'] ?></strong></td>
                        <td><?= $log['tabela_afetada'] ?></td>
                        <td style="width: "><?= $log['linha_afetada_id'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>