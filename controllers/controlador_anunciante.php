<?php
session_start();
require "../conexaoMysql.php";
require "anunciante.php";

// resgata a ação a ser executada
$acao = $_GET['acao'];

$pdo = mysqlConnect();

switch ($acao) {

    case "cadastrarAnunciante":
        $nome = $_POST["nome"] ?? '';
        $cpf = $_POST["cpf"] ?? '';
        $email = $_POST["email"] ?? '';
        $senha = $_POST["senha"] ?? '';
        $telefone = $_POST["telefone"] ?? '';

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        Anunciante::Create($pdo, $nome, $cpf, 
        $email, $senhaHash, $telefone);
        echo json_encode(["success" => true]);
        break;

    case "login":
        $email = $_POST["email"] ?? '';
        $senha = $_POST["senha"] ?? '';

        $anunciante = Anunciante::findByEmail($pdo, $email);

        if ($anunciante && password_verify($senha, $anunciante->senhaHash)) {
            $_SESSION['anuncianteId'] = $anunciante->id;
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Credenciais inválidas"]);
        }
        break;

    case "logout":
        session_destroy();
        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["error" => "Ação não disponível"]);
        break;
}
