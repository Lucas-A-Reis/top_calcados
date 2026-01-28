<?php
function emailExiste(PDO $pdo, $email)
{
    $sql = "SELECT id FROM clientes WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

function cadastrarCliente(PDO $pdo, Cliente $cliente)
{

    if (emailExiste($pdo, $cliente->getEmail())) {
        return "Este e-mail já está cadastrado.";
    } else {

        try {
            $sql = "INSERT INTO clientes (nome, email, senha, telefone) 
                VALUES (:nome, :email, :senha, :telefone)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':nome', $cliente->getNome());
            $stmt->bindValue(':email', $cliente->getEmail());

            $senhaHash = password_hash($cliente->getSenha(), PASSWORD_DEFAULT);
            $stmt->bindValue(':senha', $senhaHash);

            $stmt->bindValue(':telefone', $cliente->getTelefone());

            return $stmt->execute();
        } catch (PDOException $e) {
            return "Erro técnico: " . $e->getMessage();
        }
    }
}

function buscarClientePorEmail(PDO $pdo, $email)
{
    $sql = "SELECT * FROM clientes WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    return $stmt->fetch();
}
function buscarClientes(PDO $pdo)
{
    $sql = "SELECT * FROM clientes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

function criarPedidoDeRecuperacao(PDO $pdo, $email)
{

    $token = bin2hex(random_bytes(32));

    $data = new DateTime();
    $data->modify('+1 hour');
    $expiracao = $data->format('Y-m-d H:i:s');

    try {

        $sqlInvalida = "UPDATE recuperacao_senhas SET usado = 1 WHERE email = :email";
        $stmtInv = $pdo->prepare($sqlInvalida);
        $stmtInv->execute([':email' => $email]);

        $sqlCria = "INSERT INTO recuperacao_senhas (email, token, data_expiracao) 
                VALUES (:email, :token, :data_expiracao)";

        $stmt = $pdo->prepare($sqlCria);
        $stmt->execute([
            ':email' => $email,
            ':token' => $token,
            ':data_expiracao' => $expiracao
        ]);

        return $token;
    } catch (PDOException $e) {
        return false;
    }
}

function buscarClientePorId(PDO $pdo, $id)
{
    $sql = "SELECT * FROM clientes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    return $stmt->fetch();
}

?>