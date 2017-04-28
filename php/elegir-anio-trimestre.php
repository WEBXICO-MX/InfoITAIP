<?php
session_start();
include '../class/ChromePhp.php';

if (!isset($_SESSION['usr'])) {
    header('Location: ../index.php');
    die();
    return;
}
$origen = "anio-trimestre";
$anio = isset($_SESSION['anio']) ? (int) $_SESSION['anio'] : 0;
$trimestre = isset($_SESSION['trimestre']) ? (int) $_SESSION['trimestre'] : 0;

$anios = [2015, 2016, 2017];
$trimestres = ["1er trimestre (enero-marzo)", "2do trimestre (abril-junio)", "3er trimestre (julio-septimebre)", "4to trimestre (octubre-diciembre)"];
ChromePhp::log("Prueba de consola");
if (isset($_POST["xAccion"])) {
   
    if ($_POST["xAccion"] === "cambiar") {
        ChromePhp::log('Entre a cambiar');
        $_SESSION['anio'] = (int) $_POST['cmbAnio'];
        $_SESSION['trimestre'] = (int) $_POST['cmbTrimestre'];
        header('Location: anexos.php');
        die();
        return;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UTTAB | Universidad Tecnológica de Tabasco</title>
        <link href="../img/favicon.ico" rel="icon" >
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/infoITAIP.min.css" rel="stylesheet"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <body>
        <div class="container-fluid">
            <?php require_once 'include-header.php'; ?>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Elegir año y trimestre</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="frmAnioTrimestre" id="frmAnioTrimestre" method="post">
                                    <div class="form-group">
                                        <label for="cmbAnio">Año:</label>
                                        <input type="hidden" name="xAccion" id="xAccion" value="0" />
                                        <select name="cmbAnio" id="cmbAnio" class="form-control">
                                            <option value="0" >---------- SELECCIONE UNA OPCIÓN -----------</option>
                                            <?php
                                            foreach ($anios as $a) {
                                                echo("<option value=\"" . $a . "\" " . ($a == $anio ? "selected" : "") . ">" . $a . "</option>");
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cmbTrimestre">Trimestre:</label>
                                        <select name="cmbTrimestre" id="cmbTrimestre" class="form-control">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                            <?php
                                            $count = 0;
                                            foreach ($trimestres as $t) {
                                                echo("<option value=\"" . ( ++$count) . "\" " . ($count == $trimestre ? "selected" : "") . ">" . $t . "</option>");
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-md btn-success btn-block" value="Cambiar" onclick="cambiar()"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once 'include-footer.php'; ?>
        <script src="../js/jquery-3.2.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>        
        <script src="../js/infoITAIP.min.js"></script>
        <script>


                                            function cambiar()
                                            {
                                                if (validar())
                                                {
                                                    $("#xAccion").val("cambiar");
                                                    $("#frmAnioTrimestre").submit();
                                                }

                                            }

                                            function validar()
                                            {
                                                var valido = true;
                                                var msg = "";
                                                if ($("#cmbAnio").val() === "0")
                                                {
                                                    msg += "Elija un año. \n";
                                                    valido = false;
                                                }
                                                if ($("#cmbTrimestre").val() === "0")
                                                {
                                                    msg += "Elija un trimestr. \n";
                                                    valido = false;
                                                }

                                                if (!valido)
                                                {
                                                    alert(msg);
                                                }

                                                return valido;

                                            }
        </script>
    </body>
</html>