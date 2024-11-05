<?php
require_once 'models/AnuncioModel.php';

class AnuncioController {
    private $model;

    public function __construct() {
        $this->model = new AnuncioModel();
    }

    public function cadastrarAnuncio() {
        session_start();
        if (!isset($_SESSION['anunciante_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }

        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $anunciante_id = $_SESSION['anunciante_id'];
        $fotos = $_FILES['fotos'];

        if ($this->model->cadastrarAnuncio($titulo, $descricao, $preco, $anunciante_id, $fotos)) {
            echo json_encode(['success' => true, 'message' => 'Anúncio cadastrado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar anúncio']);
        }
    }
}
?>