<?php

require "../conexaoMysql.php";
require "../models/criar_anuncio.php";

function validaFoto($arquivoImagem)
{
   if (!is_uploaded_file($arquivoImagem))
      throw new InvalidArgumentException("Falha ao carregar o arquivo de imagem");

   // Resgata e verifica o tamanho da imagem
   list($width, $height, $type) = getimagesize($arquivoImagem);
   if (empty($width) || empty($height))
      throw new InvalidArgumentException("O arquivo informado não corresponde a uma imagem válida");

   // Verifica o formato do arquivo de imagem
   $imageType = image_type_to_mime_type($type);
   if ($imageType != "image/jpeg" && $imageType != "image/png")
      throw new InvalidArgumentException("A foto deve estar no formato JPEG ou PNG");

   // Verifica o tamanho do arquivo de imagem
   if (filesize($arquivoImagem) > 5 * 1024 * 1024)
      throw new InvalidArgumentException("A foto não deve ultrapassar 5MB");

   return $imageType;
}

// Resgata a ação a ser executada
$acao = $_GET['acao'];

// Conecta ao servidor do MySQL
$pdo = mysqlConnect();

switch ($acao) {

   case "criarAnuncio":
      $marca = htmlspecialchars($_POST["marca"] ?? "");
      $modelo = htmlspecialchars($_POST["modelo"] ?? "");
      $ano = htmlspecialchars($_POST["ano"] ?? "");
      $cor = htmlspecialchars($_POST["cor"] ?? "");
      $km = htmlspecialchars($_POST["km"] ?? "");
      $descricao = htmlspecialchars($_POST["descricao"] ?? "");
      $valor = htmlspecialchars($_POST["valor"] ?? "");
      $estado = htmlspecialchars($_POST["estado"] ?? "");
      $cidade = htmlspecialchars($_POST["cidade"] ?? "");

      // Resgata o arquivo enviado no formulário (como arquivo temporário)
      $arquivoImagemTemp = $_FILES["fotos"]["tmp_name"] ?? "";

      // Dados para compor o nome final do arquivo
      $pasta = "images";
      $dataHora = date('Ymd_His', timestamp: time());
      $microtime = microtime(true);

      try {
         // Chama a função de validação da foto
         $tipoArquivoImagem = validaFoto($arquivoImagemTemp);

         // Extensão do arquivo (jpeg ou png)
         $extensao = substr($tipoArquivoImagem, 6);
         $pastaDestino = "$pasta/{$dataHora}-{$microtime}.{$extensao}"; 

         // Move o arquivo temporário para a pasta/nome final
         if (move_uploaded_file($arquivoImagemTemp, $pastaDestino)) {
            $nomearqfoto = $pastaDestino;
         }

         // Insere na tabela
         Anuncio::CreateAnuncio($pdo, $marca, $modelo, $ano, $cor, $km, $descricao, $valor, $estado, $cidade, $nomearqfoto);
         header("location: ../pages/privada/interna.html");

      } catch (Exception $e) {
         throw new Exception($e->getMessage());
      }
      break;

   case "listarAnuncios":
      try {   
         $arrayAnuncio = Anuncio::CreateListar30($pdo);
         header('Content-Type: application/json; charset=utf-8');
         echo json_encode($arrayAnuncio);
      } catch (Exception $e) {
         throw new Exception($e->getMessage());
      }
      break;

   case "criarInteresse":
      try {
         $nome = $_POST["nome"] ?? "";
         $telefone = $_POST["telefone"] ?? "";
         $mensagem = $_POST["mensagem"] ?? "";

         // Insere na tabela de interesses
         Interesse::CreateInteresse($pdo, $nome, $telefone, $mensagem);

         header("location: ../pages/index.html");
      } catch (Exception $e) {
         throw new Exception($e->getMessage());
      }
      break;

   default:
      exit("Ação não disponível");
}