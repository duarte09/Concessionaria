<?php
class Anunciante
{
    public static function Create($pdo, $nome, $cpf, $email, $senhaHash, $telefone)
    {
        $stmt = $pdo->prepare(
            "INSERT INTO anunciante (nome, cpf, email, senhaHash, telefone) 
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([$nome, $cpf, $email, $senhaHash, $telefone]);

        return $pdo->lastInsertId();  // Retorna o ID do novo anunciante
    }

    public static function verifyLogin($pdo, $email, $senhaFornecida)
    {
    $stmt = $pdo->prepare("SELECT senhaHash FROM anunciante WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() == 0) return false; // E-mail não encontrado

    $senhaHash = $stmt->fetchColumn();

    // Verifica se a senha fornecida corresponde ao hash armazenado
    return password_verify($senhaFornecida, $senhaHash);
    }

}
?>