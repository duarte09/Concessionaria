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

      // resgata o arquivo enviado no formulário (como arquivo temporário)
      $arquivoImagemTemp = $_FILES["fotos"]["tmp_name"] ?? "";

      // dados para compor o nome final do arquivo
      $pasta = "images";
      //$dataHora = date('Ymd_His', timestamp: time());
      $microtime = microtime(true);

      try {
         // chamar funcao
         $tipoArquivoImagem = validaFoto($arquivoImagemTemp);

         // extensão vai pegar se é jpeg ou png para colocar no fim do nome na pasta images
         $extensao = substr($tipoArquivoImagem, 6);
         $pastaDestino = "$pasta/{$microtime}.{$extensao}"; 
         //$pastaDestino = "$pasta/{$dataHora}-{$microtime}.{$extensao}"; 

         // move o arquivo temporário para a pasta/nome final
         if (move_uploaded_file($arquivoImagemTemp, $pastaDestino)) {
            $nomearqfoto = $pastaDestino;
         }

         // inserir na tabela
         Anuncio::CreateAnuncio($pdo, $marca, $modelo, $ano, $cor, $km, $descricao, $valor, $estado, $cidade, $nomearqfoto);
         header("location: ../pages/publica/criarAnuncio.html"); // ARRUMAR AQUI

      } catch (Exception $e) {
         throw new Exception($e->getMessage());
      }
      break;

   case "listarAnuncios":
      //--------------------------------------------------------------------------------------
      try{   
         $arrayAnuncio = Anuncio::CreateListar30($pdo);
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
