<?php
require_once 'conexaoMysql.php';
$pdo = mysqlConnect();

// Obtém o ID do anúncio da URL
$anuncioId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($anuncioId > 0) {
    $sql = "SELECT a.*, an.nome AS nome_anunciante, an.email AS email_anunciante, f.nomearqfoto
            FROM anuncio a
            LEFT JOIN anunciante an ON a.idanunciante = an.id
            LEFT JOIN foto f ON a.id = f.idanuncio
            WHERE a.id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$anuncioId]);
    $anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($anuncio) {
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detalhes do Anúncio</title>
            <link rel="stylesheet" href="styles/detalheAnuncio.css">
        </head>
        <body>
            <div class="main-container">
                <h1>Detalhes do Veículo</h1>
                <div class="card">
                    <img src="images/<?=$anuncio['nomearqfoto']?>" alt="Foto do veículo" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?=$anuncio['marca']?> <?=$anuncio['modelo']?> <?=$anuncio['ano']?></h5>
                        <p><strong>Cor:</strong> <?=$anuncio['cor']?></p>
                        <p><strong>Quilometragem:</strong> <?=$anuncio['km']?> km</p>
                        <p><strong>Descrição:</strong> <?=$anuncio['descricao']?></p>
                        <p><strong>Localização:</strong> <?=$anuncio['cidade']?>, <?=$anuncio['estado']?></p>
                        <p><strong>Valor:</strong> R$ <?=number_format($anuncio['valor'], 2, ',', '.')?></p>
                        <p><strong>Contato:</strong> <?=$anuncio['nome_anunciante']?> (<?=$anuncio['email_anunciante']?>)</p>
                        <a href="index.html" class="btn-voltar">Voltar</a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Anúncio não encontrado.";
    }
} else {
    echo "ID inválido.";
}
?>
