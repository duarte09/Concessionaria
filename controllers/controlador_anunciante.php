<?php
//session_start();
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
        $nome = $_POST["nome"] ?? '';
        $cpf = $_POST["cpf"] ?? '';
        $email = $_POST["email"] ?? '';
        $senha = $_POST["senha"] ?? '';
        $telefone = $_POST["telefone"] ?? '';

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        Anunciante::Create(
            $pdo,
            $nome,
            $cpf,
            $email,
            $senhaHash,
            $telefone
        );
        echo json_encode(["success" => true]);
        break;

    case "login":
        $email = $_POST["email"] ?? '';
        $senha = $_POST["senha"] ?? '';

        $anunciante = new Anunciante();
        $credenciaisValidas = $anunciante->checkUserCredentials($pdo, $email, $senha);
        
        if($credenciaisValidas){
            session_start();
            $_SESSION['loggedIn'] = true;
            $_SESSION['user'] = $email;

            header('Location: ../pages/privada/interna.html');
            exit();
        } else {
            // Credenciais inválidas, redireciona para a página de login com erro
            header('Location: login.html?erro=1');
            exit();
        }
        break;
        
        /*
        $anunciante = Anunciante::findByEmail($pdo, $email);

        if ($anunciante && password_verify($senha, $anunciante->senhaHash)) {
            $_SESSION['anuncianteId'] = $anunciante->id;
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Credenciais inválidas"]);
        }
        break;*/

        /* if (checkUserCredentials($pdo, $email, $senha)) {
            // Define o parâmetro 'httponly' para o cookie de sessão, para que o cookie
            // possa ser acessado apenas pelo navegador nas requisições http (e não por código JavaScript).
            // Aumenta a segurança evitando que o cookie de sessão seja roubado por eventual
            // código JavaScript proveniente de ataq. X S S.
            $cookieParams = session_get_cookie_params();
            $cookieParams['httponly'] = true;
            session_set_cookie_params($cookieParams);

            session_start();
            $_SESSION['loggedIn'] = true;
            $_SESSION['user'] = $email;
            $response = new LoginResult(true, 'home.php');
        } else
            $response = new LoginResult(false, '');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        */

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


