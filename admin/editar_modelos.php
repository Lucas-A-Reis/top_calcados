<?php
require_once '../checkout/config.php';
#require_once 'autenticacao.php';
require_once '../src/database/conecta.php';
require_once '../src/models/modelo.php';
require_once '../src/services/modeloServico.php';
require_once '../src/helpers/funcoes_uteis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $marca = sanitizar($_POST['marca'], 'string');
    $tipo = sanitizar($_POST['tipo'] ?? null, 'string');
    $preco = sanitizar($_POST['preco'], 'float');
    $descricao = sanitizar($_POST['descricao'] ?? null, 'string');
    $genero = sanitizar($_POST['genero'] ?? null, 'string');
    $faixa_etaria = sanitizar($_POST['faixa_etaria'] ?? null, 'string');
    $peso = sanitizar($_POST['peso'], 'int');
    $comprimento = sanitizar($_POST['comprimento'], 'int');
    $largura = sanitizar($_POST['largura'], 'int');
    $altura = sanitizar($_POST['altura'], 'int');
    $formato = sanitizar($_POST['formato'] ?? null, 'int');
    $slug = sanitizar($_POST['slug'] ?? null, 'string');
    $destaque = sanitizar(isset($_POST['destaque']) && $_POST['destaque'] == "1" ? 1 : 0, 'int');
    $status = sanitizar($_POST['status'] ?? null, 'int');

    $modeloEditado = new Modelo($marca, $tipo, $genero, $faixa_etaria, $preco, $descricao, $slug, $destaque, $status, $peso, $comprimento, $largura, $altura, $formato);
    $modeloEditado->setId($id);

    if (atualizarModelo($pdo, $modeloEditado)) {
        header("Location: gerenciar_modelos.php?sucesso=2");
        exit();
    } else {
        header("Location: gerenciar_modelos.php?erro=1");
    }
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$modelo = buscarModeloPorId($pdo, $id);

if (!$modelo) {
    header("Location: gerenciar_modelos.php?erro=modelo_nao_encontrado");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Editar Modelo</title>
</head>

<body class="admin">
    <?php include_once '../includes/cabecalho_admin.php'; ?>
    <form style="align-self: center;" class="form" action="editar_modelos.php?id=<?php echo $modelo->getId(); ?>"
        method="POST">
        <input type="hidden" name="id" value="<?php echo $modelo->getId(); ?>">

        <div class="grid">
            <h3>Informações Básicas</h3>
            <input type="text" name="marca" placeholder="Marca (Ex: Nike)"
                value="<?php echo htmlspecialchars($modelo->getMarca()); ?>" required>

            <input type="text" name="tipo" placeholder="Tipo/Modelo (Ex: Air Max)"
                value="<?php echo htmlspecialchars($modelo->getTipo() ?? ''); ?>" required>

            <input type="number" name="preco" step="0.01" placeholder="Preço (Ex:299,90)" value="<?php echo $modelo->getPreco(); ?>"
                required>
        </div>

        <div class="grid">
            <h3>Logística (Medidas)</h3>
            <input type="number" name="peso" placeholder="Peso em gramas" value="<?php echo $modelo->getPeso(); ?>" required>

            <input type="number" name="comprimento" placeholder="Comprimento (cm)"
                value="<?php echo $modelo->getComprimento(); ?>" required>

            <input type="number" name="largura" placeholder="Largura (cm)" value="<?php echo $modelo->getLargura(); ?>"
                required>

            <input type="number" name="altura" placeholder="Altura (cm)" value="<?php echo $modelo->getAltura(); ?>"
                required>

            <input type="number" name="formato" value="<?php echo $modelo->getFormato(); ?>"
                placeholder="Formato da embalagem" required>
        </div>

        <div class="grid">
            <h3>Público</h3>

            <h3>Público</h3>
            <select name="genero" value="<?php echo htmlspecialchars($modelo->getGenero() ?? ''); ?>">
                <option value="Masculino">Masculino</option>
                <option value="Feminino">Feminino</option>
                <option value="Unissex">Unissex</option>
            </select>
            <select name="faixa_etaria" value="<?php echo htmlspecialchars($modelo->getFaixaEtaria() ?? ''); ?>">
                <option value="Infantil">Infantil</option>
                <option value="Adulto">Adulto</option>
            </select>
        </div>
        </div>

        <div class="grid">
            <h3>Descrição e Slug</h3>

            <textarea name="descricao"
                placeholder="Breve descrição do calçado"><?php echo htmlspecialchars($modelo->getDescricao() ?? ''); ?></textarea>

        <div class="grid">
            <h3>Configurações de Exibição</h3>
            <select name="status">
                <option value="1" <?php echo $modelo->getStatus() == 1 ? 'selected' : ''; ?>>Ativo</option>
                <option value="0" <?php echo $modelo->getStatus() == 0 ? 'selected' : ''; ?>>Inativo</option>
            </select>

            <label>
                <input type="checkbox" name="destaque" value="1" <?php echo $modelo->getDestaque() ? 'checked' : ''; ?>>
                Destaque na Loja
            </label>
        </div>

        <div class="grid">
            <h3>Slug</h3>
            <input type="text" name="slug" placeholder="Slug(Ex: nike-air-max)"
                value="<?php echo htmlspecialchars($modelo->getSlug()); ?>" required>
        </div>

        <button class="btn_acessar" type="submit" style="margin-top: 20px;">
            Salvar Alterações
        </button>
    </form>
</body>

</html>