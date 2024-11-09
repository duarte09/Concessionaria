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
        $stmt = $pdo->prepare(
            <<<SQL
            INSERT INTO anunciante (nome, cpf, email, senhaHash, telefone) 
            VALUES (?, ?, ?, ?, ?)
            SQL
        );

        $stmt->execute([$nome, $cpf, $email, $senhaHash, $telefone]);

        return $pdo->lastInsertId();  // Retorna o ID do novo anunciante
    }

    static function verifyLogin($pdo, $email, $senhaFornecida)
    {
        $email = $_GET['email'] ?? "";

        $sql = <<<SQL
            SELECT senhaHash 
            FROM anunciante
            WHERE email = ?
        SQL;

        $stmt = $pdo->prepare;
        $stmt->execute([$email]);

        if ($stmt->rowCount() == 0)
            return false; // E-mail não encontrado

        $senhaHash = $stmt->fetchColumn();

        // Verifica se a senha fornecida corresponde ao hash armazenado
        return password_verify($senhaFornecida, $senhaHash);
    }

}
