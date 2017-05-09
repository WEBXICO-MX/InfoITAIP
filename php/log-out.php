<?php

session_start();

if (isset($_GET['logout'])) {
    unset($_SESSION['usr']);
    unset($_SESSION['nombre']);
    unset($_SESSION['area']);
    unset($_SESSION['anio']);
    unset($_SESSION['trimestre']);
    unset($_SESSION['url_publica']);
    header('Location: ../index.php');
    die();
    return;
}
?>