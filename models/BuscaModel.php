<?php
require_once 'config/Database.php';

class BuscaModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function obterMarcas() {
        $query = "SELECT DISTINCT marca FROM anuncio";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function obterModelos($marca) {
        $query = "SELECT DISTINCT modelo FROM anuncio WHERE marca = :marca";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':marca', $marca);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function obterLocalizacoes($modelo) {
        $query = "SELECT DISTINCT localizacao FROM anuncio WHERE modelo = :modelo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function buscarVeiculos($marca, $modelo, $localizacao) {
        $query = "SELECT titulo, descricao, preco, (SELECT caminho FROM foto WHERE anuncio_id = anuncio.id LIMIT 1) AS foto FROM anuncio WHERE 1";
        
        if ($marca) {
            $query .= " AND marca = :marca";
        }
        if ($modelo) {
            $query .= " AND modelo = :modelo";
        }
        if ($localizacao) {
            $query .= " AND localizacao = :localizacao";
        }

        $query .= " ORDER BY dataHora DESC LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        if ($marca) $stmt->bindParam(':marca', $marca);
        if ($modelo) $stmt->bindParam(':modelo', $modelo);
        if ($localizacao) $stmt->bindParam(':localizacao', $localizacao);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>