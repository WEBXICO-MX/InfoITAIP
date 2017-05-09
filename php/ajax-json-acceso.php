<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once '../class/Area.php';
require_once '../class/Usuario.php';

$usr = new Usuario();
$json = "";

$usr->setLogin($_POST['txtLogin']);
$usr->cargarLoginUsuario();

if ($usr->get_existe()) {
    if ($usr->getCveArea()->getActivo()) {
        if ($usr->getActivo()) {
            if ($usr->getPassword() === $_POST["txtPassword"]) {
                $_SESSION['usr'] = $usr->getCveUsuario();
                $_SESSION['nombre'] = $usr->getNombreCompleto();
                $_SESSION['area'] = $usr->getCveArea()->getCveArea();
                $_SESSION['anio'] = 0;
                $_SESSION['trimestre'] = 0;
                $_SESSION['url_publica'] = "http://transparencia.uttab.edu.mx/";

                $json = '{"msg":"Bienvenid@ ' . $usr->getNombreCompleto() . '","c":' . $usr->getCveUsuario() . ',"valido":true}';
            } else {
                $json = '{"msg":"La contraseña no es correcta","c":' . $usr->getCveUsuario() . ',"valido":false}';
            }
        } else {
            $json = '{"msg":"Usuario desactivado","c":' . $usr->getCveUsuario() . ',"valido":false}';
        }
    } else {
        $json = '{"msg":"El área del usuario esta desactivada","c":' . $usr->getCveUsuario() . ',"valido":false}';
    }
} else {
    $json = '{"msg":"El usuario no existe","c":' . $usr->getCveUsuario() . ',"valido":false}';
}
echo($json);