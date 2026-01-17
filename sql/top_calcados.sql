CREATE DATABASE IF NOT EXISTS top_calcados;
USE top_calcados;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    codigo_2fa VARCHAR(6) NULL,
    expiracao_2fa DATETIME NULL,
    status TINYINT(1) DEFAULT 1
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20)
);

CREATE TABLE enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    logradouro VARCHAR(255) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    uf CHAR(2) NOT NULL,
    cep VARCHAR(9) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

CREATE TABLE modelos_calcado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    genero VARCHAR(20),
    faixa_etaria VARCHAR(20),
    preco DECIMAL(10, 2) NOT NULL,
    descricao TEXT,
    slug VARCHAR(255) UNIQUE,
    destaque TINYINT(1) DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    peso INT NOT NULL,
    comprimento INT NOT NULL, 
    largura INT NOT NULL, 
    altura INT NOT NULL, 
    formato INT DEFAULT 1
);

CREATE TABLE variacoes_calcado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modelo_id INT NOT NULL,
    tamanho INT NOT NULL,
    cor VARCHAR(50) NOT NULL,
    estoque INT DEFAULT 0,
    FOREIGN KEY (modelo_id) REFERENCES modelos_calcado(id) ON DELETE CASCADE
);

CREATE TABLE imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    variacao_id INT NOT NULL,
    arquivo VARCHAR(255) NOT NULL,
    FOREIGN KEY (variacao_id) REFERENCES variacoes_calcado(id) ON DELETE CASCADE
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10, 2) NOT NULL,
    status_pagamento VARCHAR(50) DEFAULT 'pendente',
    metodo_pagamento VARCHAR(50),
    psp_id VARCHAR(255),
    logradouro_entrega VARCHAR(255) NOT NULL,
    numero_entrega VARCHAR(20) NOT NULL,
    bairro_entrega VARCHAR(100) NOT NULL,
    cidade_entrega VARCHAR(100) NOT NULL,
    uf_entrega CHAR(2) NOT NULL,
    cep_entrega VARCHAR(9) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE pedidos_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    variacao_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (variacao_id) REFERENCES variacoes_calcado(id)
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    acao VARCHAR(255) NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    linha_afetada_id INT,
    tabela_afetada VARCHAR(50),
    FOREIGN KEY (admin_id) REFERENCES admins(id)
);

CREATE TABLE recuperacao_senhas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    data_expiracao DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (token)
);