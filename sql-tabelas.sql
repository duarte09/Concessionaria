CREATE DATABASE autoficticio;

USE autoficticio;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(15) NOT NULL
);

CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    cor VARCHAR(20),
    quilometragem INT,
    descricao TEXT,
    localizacao VARCHAR(50),
    valor DECIMAL(10, 2),
    imagem VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE interesses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    veiculo_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);