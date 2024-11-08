CREATE DATABASE autoficticio;

USE autoficticio;

CREATE TABLE anunciante (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(15) NOT NULL
);

CREATE TABLE anuncio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    ano INT NOT NULL,
    cor VARCHAR(20),
    km INT,
    descricao TEXT,
    valor DECIMAL(10, 2),
    datahora TIMESTAMP DEFAULT NOW(),
    estado VARCHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    idanunciante INT NOT NULL,
    FOREIGN KEY (idanunciante) REFERENCES anunciante(id) ON DELETE CASCADE
);

CREATE TABLE interesse (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15),
    mensagem TEXT,
    datahora TIMESTAMP DEFAULT NOW(),
    idanuncio INT NOT NULL,
    FOREIGN KEY (idanuncio) REFERENCES anuncio(id)
);

CREATE TABLE foto (
    idanuncio INT NOT NULL,
    nomearqfoto VARCHAR(255) NOT NULL,
    PRIMARY KEY (idanuncio, nomearqfoto),
    FOREIGN KEY (idanuncio) REFERENCES anuncio(id)
);