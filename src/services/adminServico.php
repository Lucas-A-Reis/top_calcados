<?php

function gerarCodigo2FA(PDO $pdo, $adminId) {
    
    $codigo = (string)random_int(100000, 999999);
    
    $data = new DateTime();
    $data->modify('+10 minutes');
    $expiracao = $data->format('Y-m-d H:i:s');

    $sql = "UPDATE admins 
            SET codigo_2fa = :codigo, expiracao_2fa = :expiracao 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':codigo' => $codigo,
        ':expiracao' => $expiracao,
        ':id' => $adminId
    ]);

    return $codigo;
}