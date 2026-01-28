<?php
session_start();
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/models/cliente.php';
require_once '../src/services/clienteServico.php';

var_dump($_SESSION);

$cliente = buscarClientePorId($pdo, $_SESSION['cliente_id']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Área do Usuário</title>
</head>

<body>
    <?php include '../includes/cabecalho.php'; ?>
    <main style="padding: 10px;">
        <section>
            <div>
                <h1 style="margin-bottom:40px">Olá, <br> <?= $_SESSION['cliente_nome']; ?></h1>
                <div class="card campo-imagem-editar">
                    <div class="titulo_e_icone">
                        <h3>Informações Pessoais</h3>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil">
                                <path
                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                <path d="m15 5 4 4" />
                            </svg>
                        </button>
                    </div>
                    <div class="informacoes">
                        <p><strong>Nome: </strong><?= $cliente['nome']; ?></p>
                        <p><strong>Email: </strong><?= $cliente['email']; ?></p>
                        <p><strong>Telefone: </strong><?= $cliente['telefone']; ?></p>
                    </div>
                </div>
            </div>
            <div>

            </div>
        </section>
        <section></section>
    </main>
    <?php include '../includes/rodape.html'; ?>
</body>

</html>