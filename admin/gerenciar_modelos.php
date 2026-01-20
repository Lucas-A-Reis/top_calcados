<?php
require_once '../checkout/config.php';
#require_once 'autenticacao.php';
require_once '../src/database/conecta.php';
require_once '../src/models/modelo.php';
require_once '../src/services/modeloServico.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $erros = [];

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
    $textoParaSlug = $marca . " " . $tipo;
    $slug = gerarSlug($textoParaSlug);
    $slugFinal = garantirSlugUnico($pdo, $slug);

    // Model modelo
    $modelo = new Modelo($marca, $tipo, $genero, $faixa_etaria, $preco, $descricao, $slugFinal, $destaque, $status, $peso, $comprimento, $largura, $altura, $formato);

    // Inserir no banco

    $flag = false;

    if (empty($erros)) {

        $flag = inserirModelo($pdo, $modelo);

    }

    if ($flag) {
        header("Location: gerenciar_modelos.php?sucesso=1");
        exit();
    } else {
        $erros[] = "Erro ao salvar o modelo no banco de dados, verifique os dados e tente novamente.";
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

<body style="background-color: #f4f7f6;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px;">
    <form class="form" action="gerenciar_modelos.php" method="POST">
        <div class="grid">
            <h3>Informações Básicas</h3>
            <input type="text" name="marca" placeholder="Marca (Ex: Nike)" required>
            <input type="text" name="tipo" placeholder="Tipo/Modelo (Ex: Air Max)">
            <input type="number" name="preco" step="0.01" placeholder="Preço (Ex: 299.90)" required>
        </div>

        <div class="grid">
            <h3>Logística (Medidas)</h3>
            <input type="number" name="peso" placeholder="Peso em gramas" required>
            <input type="number" name="comprimento" placeholder="Comprimento (cm)" required>
            <input type="number" name="largura" placeholder="Largura (cm)" required>
            <input type="number" name="altura" placeholder="Altura (cm)" required>
            <input type="number" name="formato" value="1" placeholder="Formato da embalagem">
        </div>

        <div class="grid">
            <h3>Público e Categorias</h3>
            <input type="text" name="genero" placeholder="Gênero (Ex: Masculino)">
            <input type="text" name="faixa_etaria" placeholder="Faixa Etária (Ex: Adulto)">
        </div>

        <div class="grid">
            <h3>Descrição do Produto</h3>
            <textarea name="descricao" placeholder="Breve descrição do calçado"></textarea>
        </div>

        <div class="grid">
            <h3>Configurações de Exibição</h3>

            <select name="status">
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>

            <label>
                <input type="checkbox" name="destaque" value="1"> Colocar em Destaque
            </label>

        </div>

        <br>

        <button class="btn_acessar" type="submit">Cadastrar Modelo</button>

        <?php if (isset($_GET['sucesso'])): ?>
            <p style="transition: opacity 1s ease; margin-top: 20px;" class="alerta-sucesso">Modelo cadastrado com sucesso!
            </p>
        <?php endif; ?>

        <?php if (!empty($erros)): ?>
            <div class="alerta-erro" style="margin-top: 20px;">
                <ul style="padding-left: 20px; margin: 0;">
                    <strong>Erros detectados:</strong>
                    <?php foreach ($erros as $erro): ?>
                        <li><?php echo $erro; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </form>


    <script>

        const mensagem = document.getElementsByClassName('alerta-sucesso');

        if (mensagem.length > 0) {
            setTimeout(() => {
                mensagem[0].style.opacity = '0';
                setTimeout(() => {
                    mensagem[0].remove();
                }, 1000);
            }, 3000);
        }


        if (window.history.replaceState) {
            const novaUrl = window.location.pathname;
            window.history.replaceState({}, document.title, novaUrl);
        }

    </script>
</body>

</html>