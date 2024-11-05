<?php
// controllers/BuscaController.php
require_once 'models/BuscaModel.php';

class BuscaController {
    private $model;

    public function __construct() {
        $this->model = new BuscaModel();
    }

    public function obterMarcas() {
        $marcas = $this->model->obterMarcas();
        echo json_encode($marcas);
    }

    public function obterModelos() {
        $marca = $_GET['marca'];
        $modelos = $this->model->obterModelos($marca);
        echo json_encode($modelos);
    }

    public function obterLocalizacoes() {
        $modelo = $_GET['modelo'];
        $localizacoes = $this->model->obterLocalizacoes($modelo);
        echo json_encode($localizacoes);
    }

    public function buscarVeiculos() {
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $localizacao = $_POST['localizacao'];
        
        $resultados = $this->model->buscarVeiculos($marca, $modelo, $localizacao);
        echo json_encode($resultados);
    }
}
?>