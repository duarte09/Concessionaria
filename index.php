<?php

//require_once 'controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['action'] === 'register') {
        (new UserController())->register();
    } elseif ($_GET['action'] === 'login') {
        (new UserController())->login();
    }
}
?>
