<?php

session_start();
header('Content-Type: application/json; charset=utf-8');
require_once '../class/Area.php';
require_once '../class/Usuario.php';
require_once '../class/HistorialCambioContrasena.php';

$usr = new Usuario((int) $_SESSION['usr']);
$hcc = new HistorialCambioContrasena();
$json = "";

if ($usr->getPassword() === $_POST['xContrasenaActual']) {

    $usr->setPassword($_POST['xContrasenaNueva']);

    if ($usr->grabar() !== 0) {
        $json = '{"msg":"Contraseña actualizada con  exito","valido":true}';
        $hcc->setCveUsuario($usr);
        $hcc->setContrasenaNueva($_POST['xContrasenaNueva']);
        $hcc->setContrasenaAnterior($_POST['xContrasenaActual']);
        $hcc->grabar();
    } else {
        $json = '{"msg":"No se puedo realizar la actualización de la contraseña","valido":false}';
    }
} else {
    $json = '{"msg":"La contraseña no es correcta","valido":false}';
}
echo($json);
?>