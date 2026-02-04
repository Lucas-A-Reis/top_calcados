<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/models/imagem.php';
require_once '../src/services/imagemServico.php';


$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

if ($id) {
    $resultado = buscarImagensPorVariacaoId($pdo, $id)[0]->getCaminhoArquivo();
    if ($resultado) {
        echo json_encode($resultado);
    } else {
        echo json_encode(null);
    }
}