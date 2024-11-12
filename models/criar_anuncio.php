<?php

class Anuncio
{
    static function CreateAnuncio($pdo,
        $marca, $modelo, $ano, $cor, $km, $descricao, $valor,
        $estado, $cidade, $nomearqfoto, $idanunciante)

    {
        try {
            $pdo->beginTransaction();
            // O campo DataHora da tabela anuncio deve ser utilizado para armazenar a data e a hora em que o
            // anúncio foi criado. Essa informação não deve ser solicitada ao usuário (pode-se utilizar a função now()
            // do MySQL).

            $stmt1 = $pdo->prepare(
                <<<SQL
                INSERT INTO anuncio (marca, modelo, ano, cor, km, descricao, valor, estado, cidade, idanunciante)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
                SQL
            );
            $stmt1->execute([ $marca, $modelo, $ano, $cor, $km, $descricao, $valor, $estado, $cidade, $idanunciante]);

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

    // Buscar marcas
    static function GetMarcas($pdo)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT DISTINCT marca FROM anuncio
            SQL
        );

        $stmt->execute();
        
        $marcas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $marcas[] = $row['marca'];
        }

        if (empty($marcas)) {
            throw new Exception("Nenhuma marca encontrada");
        }

        return json_encode($marcas);
    }

    // Buscar os modelos por marca
    static function GetModelos($pdo, $marca)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT DISTINCT modelo FROM anuncio WHERE marca = ?
            SQL
        );

        $stmt->execute([$marca]);
        
        $modelos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $modelos[] = $row['modelo'];
        }

        if (empty($modelos)) {
            throw new Exception("Nenhum modelo encontrado para a marca especificada");
        }

        return json_encode($modelos);
    }

    // Buscar os veículos por modelo
    static function GetVeiculos($pdo, $modelo)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT modelo, ano, cor, quilometragem FROM anuncio WHERE modelo = ?
            SQL
        );

        $stmt->execute([$modelo]);
        
        $veiculos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $veiculos[] = $row;
        }

        if (empty($veiculos)) {
            throw new Exception("Nenhum veículo encontrado para o modelo especificado");
        }

        return json_encode($veiculos);
    }
}
?>

