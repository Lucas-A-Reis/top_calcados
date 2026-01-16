<?php
function emailExiste(PDO $pdo, $email) {
    $sql = "SELECT id FROM clientes WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    
    return $stmt->rowCount() > 0;
}

function cadastrarCliente(PDO $pdo, Cliente $cliente) {

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
?>