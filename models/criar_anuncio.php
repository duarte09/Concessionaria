<?php

class Anuncio
{
    static function Create($pdo,
        $marca, $modelo, $ano, $cor, $km, $descricao, $valor,
        $estado, $cidade, $nomearqfoto)

    {
        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare(
                <<<SQL
                INSERT INTO anuncio (marca, modelo, ano, cor, km, descricao, valor, estado, cidade)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                SQL
            );
            $stmt1->execute([ $marca, $modelo, $ano, $cor, $km, $descricao, $valor,$estado, $cidade]);

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
}