<?php

class Anunciante
{
    static function Create(
        $pdo,
        $nome,
        $cpf,
        $email,
        $senhaHash,
        $telefone
    ) {
        $stmt = $pdo->prepare(<<<SQL
            INSERT INTO anunciante (nome, cpf, email, senha, telefone) 
            VALUES (?, ?, ?, ?, ?)
            SQL
        );

        $stmt->execute([$nome, $cpf, $email, $senhaHash, $telefone]);

        return $pdo->lastInsertId();  // Retorna o ID do novo anunciante
    }

    function checkUserCredentials($pdo, $email, $senha)
    {
        $sql = <<<SQL
            SELECT senhaHash 
            FROM anunciante
            WHERE email = ?
            SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $senhaHash = $stmt->fetchColumn();

            if (!$senhaHash)
                return false; // a consulta não retornou nenhum resultado (email não encontrado)

            if (!password_verify($senha, $senhaHash))
                return false; // email e/ou senha incorreta

            return true;
        } catch (Exception $e) {
            exit('Falha inesperada: ' . $e->getMessage());
        }
    }

}
