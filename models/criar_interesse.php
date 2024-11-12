<?php

class Interesse
{
    static function CreateInteresse($pdo,
        $nome, $telefone, $mensagem, $idanuncio)

    {
        try {
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare(
                <<<SQL
                INSERT INTO interesse (nome, telefone, mensagem, idanuncio)
                VALUES (?, ?, ?, ?)
                SQL
            );
            $stmt1->execute([$nome, $telefone, $mensagem, $idanuncio]);

            // O id do novo cliente gerado automaticamente na inserção anterior 
            // é resgatado por meio do método lastInsertId().
            $idNovoAnuncio = $pdo->lastInsertId();

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