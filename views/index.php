<?php
// index.php
session_start();
if (isset($_SESSION['anunciante_id'])) {
    header("Location: views/restricted/cadastrarAnuncio.php");
} else {
    header("Location: views/public/login.php");
}
exit();
