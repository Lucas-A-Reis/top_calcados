<?php
require_once '../checkout/config.php';
#require_once 'autenticacao.php';
require_once '../src/database/conecta.php';
require_once '../src/models/modelo.php';
require_once '../src/services/modeloServico.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validação dos campos obrigatórios
    $camposObrigatorios = [
        'marca' => 'Marca',
        'preco' => 'Preço',
        'peso' => 'Peso',
        'comprimento' => 'Comprimento',
        'largura' => 'Largura',
        'altura' => 'Altura'
    ];

    $erros = [];

    foreach ($camposObrigatorios as $campo => $nomeExibicao) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === "") {
            $erros[] = "O campo <strong>$nomeExibicao</strong> é obrigatório.";
        }
    }

    // Sanitização
    $marca = sanitizar($_POST['marca'] ?? '', 'string');
    $preco = sanitizar($_POST['preco'] ?? '', 'float');
    $peso = sanitizar($_POST['peso'] ?? '', 'int');
    $comprimento = sanitizar($_POST['comprimento'] ?? '', 'int');
    $largura = sanitizar($_POST['largura'] ?? '', 'int');
    $altura = sanitizar($_POST['altura'] ?? '', 'int');

    if (!is_numeric($preco) || $preco <= 0) {
        $erros[] = "O preço deve ser um valor numérico positivo.";
    }

    if (!is_numeric($peso) || $peso <= 0) {
        $erros[] = "O peso deve ser um número inteiro positivo (em gramas).";
    }

    foreach (['comprimento', 'largura', 'altura'] as $dimensao) {
        $valor = $$dimensao;
        if (!is_numeric($valor) || $valor <= 0) {
            $erros[] = "A medida de " . ucfirst($dimensao) . " deve ser um número inteiro positivo (em cm).";
        }
    }

    // Sanitização dos campos opcionais
    $tipo = sanitizar($_POST['tipo'] ?? '', 'string');
    if (trim($tipo) === "") {
        $tipo = null;
    }

    $descricao = sanitizar($_POST['descricao'] ?? '', 'string');
    if (trim($descricao) === "") {
        $descricao = null;
    }

    $genero = sanitizar($_POST['genero'] ?? '', 'string');
    if (trim($genero) === "") {
        $genero = null;
    }

    $faixa_etaria = sanitizar($_POST['faixa_etaria'] ?? '', 'string');
    if (trim($faixa_etaria) === "") {
        $faixa_etaria = null;
    }

    $destaque = isset($_POST['destaque']) ? 1 : 0;

    $status = isset($_POST['status']) ? (int) $_POST['status'] : 1;

    $formato = sanitizar($_POST['formato'] ?? 1, 'int');
    $formato = (is_numeric($formato) && $formato > 0) ? (int) $formato : 1;

    // Gerar slug
    $marca = $_POST['marca'];
    $tipo = $_POST['tipo'];
    $textoParaSlug = $marca . " " . $tipo;
    $slug = gerarSlug($textoParaSlug);
    $slugFinal = garantirSlugUnico($pdo, $slug);

    // Model modelo
    $modelo = new Modelo($marca, $tipo, $genero, $faixa_etaria, $preco, $descricao, $slugFinal, $destaque, $status, $peso, $comprimento, $largura, $altura, $formato);

    // Inserir no banco
    $idModeloGerado = inserirModelo($pdo, $modelo);

    if ($idModeloGerado) {
        echo "Modelo cadastrado com sucesso! ID: " . $idModeloGerado;
    } else {
        $erros[] = "Erro ao salvar o modelo no banco de dados. Verifique os logs.";
    }

    if (count($erros) > 0) {
        echo "<h3>Erros detectados:</h3><ul>";
        foreach ($erros as $erro) {
            echo "<li>$erro</li></ul>";
        }
    }

    if ($idModeloGerado) {
        header("Location: gerenciar_modelos.php?sucesso=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Gerenciar Modelos</title>
</head>

<body>

    <?php if (isset($_GET['sucesso'])): ?>
        <p class="alerta-sucesso">Modelo cadastrado com sucesso!</p>
    <?php endif; ?>

    <form action="gerenciar_modelos.php" method="POST">
        <h3>Informações Básicas</h3>
        <input type="text" name="marca" placeholder="Marca (Ex: Nike)" required>
        <input type="text" name="tipo" placeholder="Tipo/Modelo (Ex: Air Max)">
        <input type="number" name="preco" step="0.01" placeholder="Preço (Ex: 299.90)" required>

        <h3>Logística (Medidas)</h3>
        <input type="number" name="peso" placeholder="Peso em gramas" required>
        <input type="number" name="comprimento" placeholder="Comprimento (cm)" required>
        <input type="number" name="largura" placeholder="Largura (cm)" required>
        <input type="number" name="altura" placeholder="Altura (cm)" required>

        <h3>Público e Categorias</h3>
        <input type="text" name="genero" placeholder="Gênero (Ex: Masculino)">
        <input type="text" name="faixa_etaria" placeholder="Faixa Etária (Ex: Adulto)">

        <h3>Descrição do Produto</h3>
        <textarea name="descricao" placeholder="Breve descrição do calçado"></textarea>

        <h3>Configurações de Exibição</h3>
        <label>
            <input type="checkbox" name="destaque" value="1"> Colocar em Destaque
        </label>

        <select name="status">
            <option value="1">Ativo</option>
            <option value="0">Inativo</option>
        </select>

        <input type="number" name="formato" value="1" placeholder="Formato da embalagem">

        <button type="submit">Cadastrar Modelo</button>
    </form>
</body>

</html>