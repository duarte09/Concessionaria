<?php

require "../conexaoMysql.php";
require "../models/criar_anuncio.php";

// resgata a ação a ser executada
$acao = $_GET['acao'];

// conecta ao servidor do MySQL
$pdo = mysqlConnect();

switch ($acao) {

  case "criarAnuncio":
    $marca = $_POST["marca"] ?? "";
    $modelo = $_POST["modelo"] ?? "";
    $ano = $_POST["ano"] ?? "";
    $cor = $_POST["cor"] ?? "";
    $km = $_POST["km"] ?? "";
    $descricao = $_POST["descricao"] ?? "";
    $valor = $_POST["valor"] ?? "";
    $estado = $_POST["estado"] ?? "";
    $cidade = $_POST["cidade"] ?? "";

    // Resgatar name -> fotos
    $nomearqfoto = $_POST["fotos"] ?? "";

    try {
      // Insere os dados nas tabelas
      Cliente::Create($pdo, $marca, $modelo, $ano, $cor, $km, $descricao, $valor, $estado, $cidade, $nomearqfoto);
      header("location: clientes.html"); // ARRUMAR AQUI
      
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;

  case "listarAnuncios":
    //--------------------------------------------------------------------------------------
    try {
      $arrayClientes = Cliente::GetFirst30($pdo);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($arrayClientes);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;

    //-----------------------------------------------------------------
  default:
    exit("Ação não disponível");
}
