CREATE DATABASE ecommerce_teste;
USE ecommerce_teste;
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_email VARCHAR(100),
    valor_total DECIMAL(10,2),
    status_pagamento VARCHAR(20) DEFAULT 'pendente',
    psp_id VARCHAR(100),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE pedidos ADD COLUMN metodo_pagamento VARCHAR(50) AFTER psp_id;
ALTER TABLE pedidos ADD INDEX (psp_id);
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    telefone VARCHAR(20),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE INDEX idx_email_unico (email),
    INDEX idx_cpf (cpf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE pedidos ADD COLUMN cliente_id INT AFTER id;
ALTER TABLE pedidos ADD CONSTRAINT fk_pedido_cliente 
FOREIGN KEY (cliente_id) REFERENCES clientes(id);