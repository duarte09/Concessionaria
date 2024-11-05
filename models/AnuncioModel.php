<?php
// models/AnuncioModel.php
require_once 'config/Database.php';

class AnuncioModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function cadastrarAnuncio($titulo, $descricao, $preco, $anunciante_id, $fotos) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO anuncio (titulo, descricao, preco, anunciante_id, dataHora) VALUES (:titulo, :descricao, :preco, :anunciante_id, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':anunciante_id', $anunciante_id);
            $stmt->execute();
            $anuncio_id = $this->conn->lastInsertId();

            foreach ($fotos['name'] as $index => $nomeFoto) {
                $caminhoFoto = 'uploads/' . uniqid() . '-' . basename($nomeFoto);
                move_uploaded_file($fotos['tmp_name'][$index], $caminhoFoto);

                $queryFoto = "INSERT INTO foto (anuncio_id, caminho) VALUES (:anuncio_id, :caminho)";
                $stmtFoto = $this->conn->prepare($queryFoto);
                $stmtFoto->bindParam(':anuncio_id', $anuncio_id);
                $stmtFoto->bindParam(':caminho', $caminhoFoto);
                $stmtFoto->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>