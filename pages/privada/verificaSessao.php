<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../publica/login.html");
    exit();
}

// Este arquivo verifica se o usuário está logado, 
// e deve ser incluído no início das páginas privadas, como "interna.html".

// EXEMPLO DANIEL
function exitWhenNotLoggedIn()
{ 
  if (!isset($_SESSION['loggedIn'])) {
    header("Location: index.html");
    exit();  
  }
}
