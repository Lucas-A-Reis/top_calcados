<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';

$token = $_GET['token'] ?? '';
$exibirFormulario = false;

if (!empty($token)) {

        $sql = "SELECT * FROM recuperacao_senhas 
            WHERE token = :token 
            AND usado = 0 
            AND data_expiracao > NOW()";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token' => $token]);
    $solicitacao = $stmt->fetch();

    if ($solicitacao) {
        $exibirFormulario = true;
        $emailDoCliente = $solicitacao['email'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha - Top Calçados</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/cabecalho_simples.php'; ?>
    <main class="login" style="background-color: f9f9f9; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); width: 400px; margin: 50px auto;">
        <?php if ($exibirFormulario): ?>
            <form id="form_login" action="atualizar_senha.php" method="POST">
                <h2>Crie sua nova senha</h2>
                <p>Olá! Digite abaixo sua nova senha de acesso.</p>

                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                <div class="campo_entrada">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" required minlength="8">
                </div>

                <div class="campo_entrada">
                    <label for="confirma_senha">Confirme a Nova Senha</label>
                    <input type="password" id="confirma_senha" name="confirma_senha" required>
                </div>

                <button type="submit" class="btn_acessar">Salvar Nova Senha</button>
            </form>
        <?php else: ?>
            <div id="form_login" style="text-align: center;">
                <h2>Link Inválido ou Expirado</h2>
                <p>Infelizmente este link de recuperação não é mais válido.</p>
                <a href="recuperar_senha.php" class="btn_acessar" style="text-decoration: none; display: block; margin-top: 20px;">Solicitar novo link</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>