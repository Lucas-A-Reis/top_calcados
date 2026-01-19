<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Top Calçados</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php include '../includes/cabecalho_simples.php'; ?>

    <main id="login">
        <form id="form_login" action="processar_recuperacao.php?front=recuperar_senha.php" method="POST">
            <h2>Recuperar Senha</h2>
            <p style="text-align: center; font-size: 14px; margin-bottom: 20px; color: #666;">
                Digite seu e-mail para receber as instruções de redefinição de senha.
            </p>

            <?php switch ($_GET['status'] ?? ''):
                case 'enviado': ?>
                    <div class="alerta-sucesso">E-mail de recuperação enviado com sucesso!</div>
                <?php break; ?>
                <?php case 'falha_envio': ?>
                    <div class="alerta-erro">Falha ao enviar o e-mail de recuperação.</div>
                <?php break; ?>
                <?php case 'email_nao_encontrado': ?>
                    <div class="alerta-erro">E-mail não encontrado.</div>
                <?php break; ?>
                <?php case 'falha_sistema': ?>
                    <div class="alerta-erro">Ocorreu uma falha no sistema. Tente novamente mais tarde.</div>
                <?php break; ?>
                <?php case 'token_expirado': ?>
                    <div class="alerta-erro">O link de recuperação expirou. Solicite um novo link.</div>
                <?php break; ?>
            <?php endswitch; ?>

            <div class="campo_entrada">
                <label for="email">E-mail Cadastrado</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>

            <?php include '../includes/captcha.php'; ?>

            <button type="submit" class="btn_acessar">Enviar Link de Recuperação</button>
            
            <div class="links_auxiliares" style="justify-content: center; margin-top: 7px;">
                <a href="login.php">Voltar para o Login</a>
            </div>
        </form>
    </main>
</body>
</html>