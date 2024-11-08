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

            $idNovoCliente = $pdo->lastInsertId();

            $stmt2 = $pdo->prepare(
                <<<SQL
                INSERT INTO foto (nomearqfoto)
                VALUES (?)
                SQL
            );
            $stmt2->execute([$nomearqfoto]);

            // Efetiva as operações
            $pdo->commit();

        } 
    } 
}