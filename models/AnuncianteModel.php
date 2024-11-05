<?php
// models/AnuncianteModel.php
require_once 'config/Database.php';

class AnuncianteModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function cadastrarAnunciante($nome, $email, $senha) {
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
        $query = "INSERT INTO anunciante (nome, email, senhaHash) VALUES (:nome, :email, :senhaHash)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senhaHash', $senhaHash);
        
        return $stmt->execute();
    }

    public function verificarLogin($email, $senha) {
        $query = "SELECT * FROM anunciante WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $anunciante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($anunciante && password_verify($senha, $anunciante['senhaHash'])) {
            return $anunciante;
        }
        return false;
    }
}