<?php
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';

session_start();

if (!isset($_SESSION['auth_admin_id'])) {
    header("Location: login.php");
    exit();
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_digitado = $_POST['codigo'];
    $admin_id = $_SESSION['auth_admin_id'];

    $sql = "SELECT id, nome, email FROM admins 
            WHERE id = :id 
            AND codigo_2fa = :codigo 
            AND expiracao_2fa > NOW()";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $admin_id, ':codigo' => $codigo_digitado]);
    $admin = $stmt->fetch();

    if ($admin) {
        $pdo->prepare("UPDATE admins SET codigo_2fa = NULL, expiracao_2fa = NULL WHERE id = :id")
            ->execute([':id' => $admin_id]);

        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nome'] = $admin['nome'];
        
        unset($_SESSION['auth_admin_id']);
        header("Location: ../admin/index.php");
        exit();
    } else {
        $erro = "Código inválido ou expirado. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificação de Segurança - Top Calçados</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .codigo-input {
            letter-spacing: 10px;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body style="display: flex; justify-content: center; background-color: #f0f0f0;">
    <main style="box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 30px; width: 400px; border-radius: 10px; background-color: #f9f9f9;" class="login">
        <form id="form_login" method="POST">
            <h2>Verificação de 2 Etapas</h2>
            <p>Enviamos um código de 6 dígitos para o seu e-mail cadastrado.</p>

            <?php if ($erro): ?>
                <div class="alerta-erro"><?php echo $erro; ?></div>
            <?php endif; ?>

            <div class="campo_entrada">
                <label for="codigo">Digite o código:</label>
                <input type="text" name="codigo" class="codigo-input" 
                       maxlength="6" placeholder="000000" required autofocus>
            </div>

            <button type="submit" class="btn_acessar">Verificar Acesso</button>
            
            <p style="margin-top: 20px; font-size: 0.9em;">
                <a href="login.php">Voltar para o login</a>
            </p>
        </form>
    </main>
</body>
</html>