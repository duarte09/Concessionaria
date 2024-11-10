<?php

session_start();

if (!isset($_SESSION['loggedIn'])) {
    header('Location: login.html');
    exit();
}

// Conteúdo da área restrita
echo "Olá, " . htmlspecialchars($_SESSION['user']);
echo '<a href="controlador-anunciante.php?action=logout">Sair</a>';  // Botão de logoff


?>