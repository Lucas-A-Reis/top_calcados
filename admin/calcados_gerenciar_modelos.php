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
        header("Location: calcados_gerenciar_modelos.php?sucesso=1");
        exit();
    } else {
        $erros[] = "Erro ao salvar o modelo no banco de dados, verifique os dados e tente novamente.";
    }

}

$lista_de_modelos = listarModelos($pdo);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Gerenciar Modelos</title>
</head>

<body class="admin">

    <?php include_once '../includes/cabecalho_admin.php'; ?>

    <h2>Cadastrar um modelo</h2>

    <form class="form" action="gerenciar_modelos.php" method="POST">
        <div class="grid">
            <h3>Informações Básicas</h3>
            <input type="text" name="marca" placeholder="Marca (Ex: Nike)" required>
            <input type="text" name="tipo" placeholder="Tipo/Modelo (Ex: Air Max)" required>
            <input type="number" name="preco" step="0.01" placeholder="Preço (Ex: 299.90)" required>
        </div>

        <div class="grid">
            <h3>Logística (Medidas)</h3>
            <input type="number" name="peso" placeholder="Peso em gramas" required>
            <input type="number" name="comprimento" placeholder="Comprimento (cm)" required>
            <input type="number" name="largura" placeholder="Largura (cm)" required>
            <input type="number" name="altura" placeholder="Altura (cm)" required>
            <input type="number" name="formato" value="1" placeholder="Formato da embalagem" required>
        </div>

        <div class="grid">
            <h3>Público</h3>
            <select name="genero">
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
                <option value="Unissex">Unissex</option>
            </select>
            <select name="faixa_etaria">
                <option value="Infantil">Infantil</option>
                <option value="Adulto">Adulto</option>
            </select>
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

        <?php $nome = "Modelo"; ?>

        <?php if (isset($_GET['sucesso'])): ?>
            <p style="transition: opacity 1s ease; margin-top: 20px;" class="alerta-sucesso sumir">
                <?php if ($_GET['sucesso'] == 1) {
                    echo $nome."cadastrado com sucesso!";
                } elseif ($_GET['sucesso'] == 2) {
                    echo $nome."editado com sucesso!";
                } ?>
            </p>
        <?php endif; ?>

        <?php if (isset($_GET['erro'])): ?>
            <p style="transition: opacity 1s ease; margin-top: 20px;" class="alerta-erro sumir"> Erro ao editar o <?php echo $nome; ?>.</p>
        <?php endif; ?>

        <?php include '../includes/alerta_de_erro.php'; ?>

    </form>

    <hr>

    <h2 style="margin-top: 40px;">Modelos Cadastrados</h2>

    <?php $listaModelos = listarModelos($pdo); ?>

    <div class="container-tabela">
        <table class="tabela">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca / Tipo</th>
                    <th>Preço</th>
                    <th>Gênero/Idade</th>
                    <th>Destaque</th>
                    <th>Status</th>
                    <th>Dimensões (CxLxA)</th>
                    <th>Peso</th>
                    <th>Slug</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaModelos as $m): ?>
                    <tr>
                        <td><?php echo $m->getId(); ?></td>
                        <td><strong><?php echo $m->getMarca(); ?></strong> <?php echo $m->getTipo(); ?></td>
                        <td>R$ <?php echo number_format($m->getPreco(), 2, ',', '.'); ?></td>
                        <td><?php echo ($m->getGenero() ?? '-') . " / " . ($m->getFaixaEtaria() ?? '-'); ?></td>
                        <td><?php echo $m->getDestaque() ? '⭐ Sim' : 'Não'; ?></td>
                        <td>
                            <span class="<?php echo $m->getStatus() ? 'status-ativo' : 'status-inativo'; ?>">
                                <?php echo $m->getStatus() ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </td>
                        <td><?php echo $m->getComprimento() . "x" . $m->getLargura() . "x" . $m->getAltura(); ?> cm</td>
                        <td><?php echo $m->getPeso(); ?>g</td>
                        <td><small><?php echo $m->getSlug(); ?></small></td>
                        <td>
                            <a href="calcados_editar_modelos.php?id=<?php echo $m->getId(); ?>" class="btn-editar">Editar</a>
                            <a href="calcados_gerenciar_variacoes.php?id=<?php echo $m->getId(); ?>" class="btn-add">Adicionar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="js/alertas.js"></script>

</body>

</html>