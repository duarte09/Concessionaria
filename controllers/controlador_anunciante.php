<?php

require "../conexaoMysql.php";
require "../models/anunciante.php";

class LoginResult
{
  public $success;
  public $newLocation;

  function __construct($success, $newLocation)
  {
    $this->success = $success;
    $this->newLocation = $newLocation;
  }
}

// resgata a ação a ser executada
$acao = $_GET['acao'];

$pdo = mysqlConnect();

switch ($acao) {

    case "cadastrarAnunciante":
        $nome = htmlspecialchars($_POST["nome"] ?? '');
        $cpf = htmlspecialchars($_POST["cpf"] ?? '');
        $email = htmlspecialchars($_POST["email"] ?? '');
        $senha = htmlspecialchars($_POST["senha"] ?? '');
        $telefone = htmlspecialchars($_POST["telefone"] ?? '');

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        Anunciante::Create(
            $pdo,
            $nome,
            $cpf,
            $email,
            $senhaHash,
            $telefone
        );
        header('Location: ../index.html');
        break;

    case "login":
        $email = $_POST["email"] ?? '';
        $senha = $_POST["senha"] ?? '';

        $anunciante = new Anunciante();
        $credenciaisValidas = $anunciante->checkUserCredentials($pdo, $email, $senha);
        
        if($credenciaisValidas){
            $cookieParams = session_get_cookie_params();
            $cookieParams['httponly'] = true;
            session_set_cookie_params($cookieParams);
            
            session_start();
            $_SESSION['loggedIn'] = true;
            $_SESSION['user'] = $email;
            $response = new LoginResult(true, '/../pages/privada/interna.html');
          } 
          else
            $response = new LoginResult(false, ''); 
          
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($response);
        break;

    case "logout":
        session_unset();   // Remove todas as variáveis de sessão
        session_destroy();
        
        header('Location: login.html');
        exit();
        break;

    default:
        echo json_encode(["error" => "Ação não disponível"]);
        break;
        
}


