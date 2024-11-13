<?php

class Anuncio
{
    static function CreateAnuncio($pdo,
        $marca, $modelo, $ano, $cor, $km, $descricao, $valor,
        $estado, $cidade, $nomearqfoto, $idanuciante)

    {
        try {
            $pdo->beginTransaction();
            // O campo DataHora da tabela anuncio deve ser utilizado para armazenar a data e a hora em que o
            // anúncio foi criado. Essa informação não deve ser solicitada ao usuário (pode-se utilizar a função now()
            // do MySQL).

            $stmt1 = $pdo->prepare(
                <<<SQL
                INSERT INTO anuncio (marca, modelo, ano, cor, km, descricao, valor, estado, cidade, idanunciante)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
/*
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

    // Buscar as localizações
    public static function GetLocalizacoes($pdo)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT DISTINCT cidade FROM anuncio
            SQL    
        );
        $stmt->execute();
        
        $localizacoes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $localizacoes[] = $row['cidade'];
        }

        return json_encode($localizacoes);
    }

    // Buscar veículos por marca, modelo e localização
    public static function GetVeiculos($pdo, $marca, $modelo, $localizacao)
    {
        $query = "SELECT marca, modelo, ano, cor, km, descricao, valor, estado, cidade FROM anuncio WHERE 1=1";
        $params = [];

        if ($marca) {
            $query .= " AND marca = ?";
            $params[] = $marca;
        }

        if ($modelo) {
            $query .= " AND modelo = ?";
            $params[] = $modelo;
        }

        if ($localizacao) {
            $query .= " AND cidade = ?";
            $params[] = $localizacao;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        
        $veiculos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $veiculos[] = $row;
        }

        return json_encode($veiculos);
    }
    }

    // Executar a função de acordo com o parâmetro 'func'
    try {
    if (!isset($_GET['func'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Parâmetro "func" é obrigatório']);
        exit;
    }

    $func = $_GET['func'];

    switch ($func) {
        case 'GetMarcas':
            echo Anuncio::GetMarcas($pdo);
            break;

        case 'GetModelos':
            if (!isset($_GET['marca'])) {
                throw new Exception("Parâmetro 'marca' é obrigatório para GetModelos");
            }
            echo Anuncio::GetModelos($pdo, $_GET['marca']);
            break;

        case 'GetLocalizacoes':
            echo Anuncio::GetLocalizacoes($pdo);
            break;

        case 'GetVeiculos':
            $marca = $_GET['marca'] ?? null;
            $modelo = $_GET['modelo'] ?? null;
            $localizacao = $_GET['localizacao'] ?? null;
            echo Anuncio::GetVeiculos($pdo, $marca, $modelo, $localizacao);
            break;

        default:
            throw new Exception("Função desconhecida: $func");
            }
        } catch (Exception $e) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => $e->getMessage()]);
        }
            */
}        
?>

