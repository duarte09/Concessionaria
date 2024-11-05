<?php
// controllers/AnuncianteController.php (atualizado)
require_once 'models/AnuncianteModel.php';

class AnuncianteController {
    private $model;

    public function __construct() {
        $this->model = new AnuncianteModel();
    }

    // Método de login
    public function loginAnunciante() {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $anunciante = $this->model->verificarLogin($email, $senha);
        
        if ($anunciante) {
            session_start();
            $_SESSION['anunciante_id'] = $anunciante['id'];
            echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email ou senha incorretos']);
        }
    }

    // Método de logout
    public function logout() {
        session_start();
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logout realizado com sucesso']);
    }
}
?>