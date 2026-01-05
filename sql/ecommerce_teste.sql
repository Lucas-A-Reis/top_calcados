CREATE DATABASE ecommerce_teste;
USE ecommerce_teste;
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_email VARCHAR(100),
    valor_total DECIMAL(10,2),
    status_pagamento VARCHAR(20) DEFAULT 'pendente', -- pendente, aprovado, recusado
    psp_id VARCHAR(100),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE pedidos ADD COLUMN metodo_pagamento VARCHAR(50) AFTER psp_id;
ALTER TABLE pedidos ADD INDEX (psp_id);