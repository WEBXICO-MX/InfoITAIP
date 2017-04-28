<?php

session_start();

if (isset($_SESSION['usr'])) {
    header('Location: php/elegir-anio-trimestre.php');
    die();
    return;
}

$origen = "index"; 
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UTTAB | Universidad Tecnológica de Tabasco</title>
        <link href="img/favicon.ico" rel="icon" >
        <link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link href="css/infoITAIP.min.css" rel="stylesheet"/>
        <link href="css/login.min.css" rel="stylesheet"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            body { 
                background: url(img/fondo-pantalla.jpg) no-repeat fixed center 130px;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size:cover;
                font-family: roboto;    /* Margin bottom by footer height */
                margin-bottom: 301.5px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <?php require_once 'php/include-header.php';?>
            <div class="row">
                <div class="col-md-12">
                    <div class="loginmodal-container">
                        <img src="img/btn-login.png" alt="Login" class="img-responsive" style="margin:0 auto 10px auto;"/>
                        <form name="frmLogin" id="frmLogin">
                            <input type="text" name="txtLogin" id="txtLogin" placeholder="Usuario" maxlength="30">
                            <input type="password" name="txtPassword" id="txtPassword" placeholder="Contraseña" maxlength="10">
                            <input type="submit" name="login" class="loginmodal-submit" value="Acceso">
                        </form>
                        <span id="msgbox" style="display:none"> </span>
                    </div>
                </div>
            </div>
        </div>
    <?php require_once 'php/include-footer.php';?>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/infoITAIP.min.js"></script>
    <script src="js/login.min.js"></script>
</body>
</html>