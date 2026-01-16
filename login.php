<?php
require_once 'checkout/config.php';
require_once 'src/database/conecta.php';
require_once 'src/helpers/funcoes_uteis.php';
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header id="login_cabecalho" style="background-color: #551A88; width: 100%;">
        <img src="media/img/logo.webp" alt="logo Top Calçados" id="top_calcados_logo">
    </header>
    <main id="login">
        <div id="entrar">
            <form id="form_login" action="processar_login.php" method="POST">
                <h2>Já sou cadastrado</h2>

                <div class="campo_entrada">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="exemplo@email.com" required>
                </div>

                <div class="campo_entrada">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
                </div>

                <button type="submit" class="btn_acessar">Entrar</button>

            </form>
            <?php if (isset($_GET['erro'])): ?>
                <div class="alerta-erro">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-circle-x-icon lucide-circle-x">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m15 9-6 6" />
                        <path d="m9 9 6 6" />
                    </svg>
                    E-mail ou senha inválidos.
                </div>
            <?php endif; ?>
        </div>
        <div class="linha"></div>
        <div id="cadastro">
            <form id="form_login" action="processar_cadastro.php" method="POST">
                <h2>Crie sua conta</h2>
                <p style="text-align: center; font-size: 13px; margin-bottom: 20px; color: #666;">
                    Preencha os campos abaixo para ser cliente da Top Calçados.
                </p>

                <div class="campo_entrada">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo" required>
                </div>

                <div class="campo_entrada">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="exemplo@email.com" required>
                </div>

                <div class="campo_entrada">
                    <label for="telefone">Telefone / WhatsApp</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(37) 99999-0000" required>
                </div>

                <div class="campo_entrada">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Crie uma senha segura" required>
                </div>

                <button type="submit" class="btn_acessar">Finalizar Cadastro</button>
            </form>
        </div>
    </main>
</body>

</html>