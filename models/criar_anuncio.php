<?php

class Anuncio
{
    static function CreateAnuncio($pdo,
        $marca, $modelo, $ano, $cor, $km, $descricao, $valor, $datahora,
        $estado, $cidade, $nomearqfoto)

    {
        try {
            $pdo->beginTransaction();
            // O campo DataHora da tabela anuncio deve ser utilizado para armazenar a data e a hora em que o
            // anúncio foi criado. Essa informação não deve ser solicitada ao usuário (pode-se utilizar a função now()
            // do MySQL).

            $stmt1 = $pdo->prepare(
                <<<SQL
                INSERT INTO anuncio (marca, modelo, ano, cor, km, descricao, valor, datahora, estado, cidade)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
                SQL
            );
            $stmt1->execute([ $marca, $modelo, $ano, $cor, $km, $descricao, $valor, $datahora, $estado, $cidade]);

            // O id do novo cliente gerado automaticamente na inserção anterior 
            // é resgatado por meio do método lastInsertId().
            $idNovoAnuncio = $pdo->lastInsertId();

            $stmt2 = $pdo->prepare(
                <<<SQL
                INSERT INTO foto (idanuncio, nomearqfoto)
                VALUES (?,?)
                SQL
            );
            $stmt2->execute([$idNovoAnuncio, $nomearqfoto]);

            // Efetiva as operações
            $pdo->commit();
        } 
        catch (Exception $e) {
            // Caso ocorra alguma falha nas operações da transação, a operação
            // rollback irá desfazer as operações que eventualmente tenham sido feitas,
            // voltando o BD ao estado em que se encontrava antes da chamada
            // de beginTransaction.
            $pdo->rollBack();
            throw $e;
          }
      
    }
    
    static function CreateListar30($pdo)
    {
    
    $stmt = $pdo->query(
        <<<SQL
        SELECT anuncio.id, marca, modelo, ano, cor, km, descricao, valor, estado, cidade, nomearqfoto
        FROM anuncio 
        LEFT JOIN foto ON anuncio.id = foto.idanuncio
        LIMIT 30
        SQL
    );

    // Resgata os dados dos anúncios como um array de objetos
    $arrayAnuncios = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $arrayAnuncios;
    }
}