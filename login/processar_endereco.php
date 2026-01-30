<?php
session_start();
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/models/endereco.php';
require_once '../src/services/enderecoServico.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cliente_id = !empty($_SESSION['cliente_id']) 
            ? $_SESSION['cliente_id'] 
            : throw new Exception('Sessao expirada');

        $endereco = new Endereco(
            (int)$cliente_id, 
            sanitizar($_POST['logradouro'], 'string'), 
            (int)$_POST['numero'], 
            sanitizar($_POST['bairro'], 'string'), 
            sanitizar($_POST['cidade'], 'string'), 
            sanitizar($_POST['uf'], 'string'), 
            sanitizar($_POST['cep'], 'string')
        );

        if (inserirEndereco($pdo, $endereco)) {
            header('Location: area_do_usuario.php?sucesso=1');
            exit;
        } else {
            throw new Exception("Erro ao salvar no banco.");
        }

    } catch (\Throwable $e) {
        $mensagemErro = urlencode($e->getMessage());
        header("Location: area_do_usuario.php?erro=$mensagemErro");
        exit;
    }
}
