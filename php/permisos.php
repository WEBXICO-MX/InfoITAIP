<?php
session_start();
require_once '../class/Area.php';
require_once '../class/Articulo.php';
require_once '../class/Fraccion.php';
require_once '../class/Permiso.php';

$area = $_SESSION['area'] ? ((int) $_SESSION['area']) : 0;
$origen = "permisos";

if (!isset($_SESSION['usr']) or $area != 1) {
    header('Location: ../index.php');
    die();
    return;
}

$rst = NULL;
$sql = "";
$grabo = false;
$cargar = false;
$count = 0;

$accion = isset($_POST["xAccion"]) ? $_POST["xAccion"] : "";
$xCveArea = isset($_POST["xCveArea"]) ? (int) $_POST["xCveArea"] : 0;
$xCveArticulo = isset($_POST["xCveArticulo"]) ? (int) ($_POST["xCveArticulo"]) : 0;
$xCveFraccion = isset($_POST["xCveFraccion"]) ? (int) $_POST["xCveFraccion"] : 0;

$p = new Permiso(new Area($xCveArea), new Articulo($xCveArticulo), new Fraccion($xCveFraccion));

if ($accion === "grabar") {
    if ($p->getCveArea()->getCveArea() != 0 and $p->getCveArticulo()->getCveArticulo() != 0 and $p->getCveFraccion()->getCveFraccion() != 0) {
        $p->setCveUsuario2(new Usuario((int) $_SESSION['usr']));
        $p->setActivo(isset($_POST["cbxActivo"]) ? true : false);
        $cargar = true;
    } else {

        $p->setCveArea(new Area((int) $_POST["cmbArea"]));
        $p->setCveArticulo(new Articulo((int) $_POST["cmbArticulo"]));
        $p->setCveFraccion(new Fraccion((int) $_POST["cmbFraccion"]));
        $p->setCveUsuario(new Usuario((int) $_SESSION['usr']));
        $p->setActivo(isset($_POST["cbxActivo"]) ? true : false);
    }
    if ($p->grabar() !== 0) {
        $grabo = true;
    }
} else if ($accion === "cargar") {
    $cargar = true;
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
        <link href="../css/dataTables.bootstrap.min.css" rel="stylesheet"/>
        <link href="../css/infoITAIP.css" rel="stylesheet"/>
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
                            <h3 class="text-center text-uppercase">Permisos</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="frmPermisos" id="frmPermisos" method="post">
                                    <div class="form-group">
                                        <input type="hidden" name="xAccion" id="xAccion" value="0" />
                                        <input type="hidden" name="xCveArea" id="xCveArea" value="<?php echo($p->getCveArea()->getCveArea() != 0 ? ($p->getCveArea()->getCveArea()) : "0"); ?>" />
                                        <input type="hidden" name="xCveArticulo" id="xCveArticulo" value="<?php echo($p->getCveArticulo()->getCveArticulo() != 0 ? ($p->getCveArticulo()->getCveArticulo()) : "0"); ?>" />
                                        <input type="hidden" name="xCveFraccion" id="xCveFraccion" value="<?php echo($p->getCveFraccion()->getCveFraccion() != 0 ? ($p->getCveFraccion()->getCveFraccion()) : "0"); ?>" />                                        
                                        <label for="cmbArea">Área:</label>                                        
                                        <select id="cmbArea" name="cmbArea" class="form-control">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                            <?php
                                            $sql = "SELECT * FROM areas WHERE activo = 1 AND cve_area NOT IN (1)  ORDER BY descripcion";
                                            $rst = UtilDB::ejecutaConsulta($sql);

                                            foreach ($rst as $row) {
                                                echo("<option value=\"" . $row["cve_area"] . "\" " . ($p->getCveArea()->getCveArea() != 0 ? ($row["cve_area"] == $p->getCveArea()->getCveArea() ? "selected" : "") : "") . ">" . $row["descripcion"] . "</option>");
                                            }
                                            $rst->closeCursor();
                                            ?>                                    
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cmbArticulo">Artículo:</label>
                                        <select id="cmbArticulo" name="cmbArticulo" class="form-control">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option> 
                                            <?php
                                            $sql = "SELECT * FROM articulos WHERE activo = 1 ORDER BY descripcion";
                                            $rst = UtilDB::ejecutaConsulta($sql);
                                            foreach ($rst as $row) {
                                                echo("<option value=\"" . $row["cve_articulo"] . "\" " . ($p->getCveArea()->getCveArea() != 0 ? ($row["cve_articulo"] == $p->getCveArticulo()->getCveArticulo() ? "selected" : "") : "") . ">" . $row["nombre"] . "</option>");
                                            }
                                            $rst->closeCursor();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cmbFraccion">Fracción:</label>
                                        <select name="cmbFraccion" class="form-control" id="cmbFraccion" disabled>
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                        </select>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cbxActivo" id="cbxActivo" value="1" <?php echo($p->getCveArea()->getCveArea() != 0 ? ($p->getActivo() ? "checked" : "") : "checked"); ?>/>Activo
                                        </label>
                                    </div> 
                                    <div class="form-group" style="text-align: center">
                                        <input class="btn btn-block btn-success" value="Guardar" onclick="grabar()"/>
                                        <input class="btn btn-block btn-primary" value="Limpiar" onclick="limpiar()"/>                                       
                                    </div>
                                </form>
                                <div class="alert alert-success" id="alert-ok" style="display:none;">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    <strong>Éxito!</strong> permiso guardado
                                </div>
                                <div class="alert alert-danger" id="alert-ko" style="display:none;">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    <strong>Error!</strong> permiso no guardado
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h5 class="text-center text-uppercase">Permiso(s)</h5>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <table id="tabla_permisos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>                                            
                                            <th>Área</th>
                                            <th>Artículo</th>
                                            <th>Fracción</th>
                                            <th>Activo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql2 = "SELECT p.cve_area,p.cve_articulo,p.cve_fraccion,a.descripcion AS area, art.nombre AS articulo, f.nombre AS fraccion, p.activo ";
                                        $sql2 .= "FROM permisos AS p ";
                                        $sql2 .= "INNER JOIN areas AS a ON a.cve_Area = p.cve_area ";
                                        $sql2 .= "INNER JOIN articulos AS art ON art.cve_articulo = p.cve_articulo ";
                                        $sql2 .= "INNER JOIN fracciones AS f ON f.cve_fraccion = p.cve_fraccion ";
                                        $rst = UtilDB::ejecutaConsulta($sql2);

                                        foreach ($rst as $row) {
                                            echo("<tr>");
                                            echo("<td><a href=\"javascript:void(0);\" onclick=\"cargar(" . $row["cve_area"] . "," . $row["cve_articulo"] . "," . $row["cve_fraccion"] . ");\">" . ( ++$count) . "</a></td>");
                                            echo("<td>" . $row["area"] . "</td>");
                                            echo("<td>" . $row["articulo"] . "</td>");
                                            echo("<td>" . $row["fraccion"] . "</td>");
                                            echo("<td>" . ($row["activo"] ? "<span class=\"glyphicon glyphicon-ok-circle\"></span>" : "<span class=\"glyphicon glyphicon-remove-circle\"></span>") . "</td>");
                                            echo("</tr>");
                                        }
                                        $rst->closeCursor();
                                        $count = 0;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div> 
        </div>
        <?php require_once 'include-footer.php'; ?>
        <script src="../js/jquery-3.2.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>        
        <script src="../js/jquery.dataTables.min.js"></script>
        <script src="../js/dataTables.bootstrap.min.js"></script>
        <script src="../js/infoITAIP.min.js"></script>
        <script>

                                            $(document).ready(function () {

                                                $('#tabla_permisos').DataTable();
                                                    <?php if ($grabo) { ?>
                                                    $("#alert-ok").alert();
                                                    $("#alert-ok").fadeTo(2000, 700).slideUp(700, function () {
                                                        $("#alert-ok").slideUp(700);
                                                    });
                                                    <?php } elseif (!$grabo and $accion === "grabar") { ?>
                                                    $("#alert-ko").alert();
                                                    $("#alert-ko").fadeTo(2000, 700).slideUp(700, function () {
                                                        $("#alert-ko").slideUp(700);
                                                    });
                                                    <?php } ?>

                                                    <?php if ($cargar) { ?>
                                                    $.post("acciones.php", {cveArt: <?php echo($p->getCveArticulo()->getCveArticulo()); ?>, cveFrac:<?php echo($p->getCveFraccion()->getCveFraccion()); ?>, xAccion: "cargaComboFracciones"}, function (data) {
                                                        $("#cmbFraccion").html(data);
                                                        $("#cmbFraccion").attr("disabled", false);
                                                    });
                                                    <?php } ?>


                                                $("#cmbArticulo").change(
                                                        function ()
                                                        {
                                                            $("#cmbFraccion").html("<option value=\"0\">---------- SELECCIONE UNA OPCIÓN -----------</option>");
                                                            $("#cmbFraccion").attr("disabled", true);
                                                            $("#cmbArticulo option:selected").each(
                                                                    function ()
                                                                    {
                                                                        elegido = $(this).val();
                                                                        $.post("acciones.php", {cveArt: elegido, xAccion: "cargaComboFracciones"}, function (data) {
                                                                            $("#cmbFraccion").html(data);
                                                                            if ($("#cmbFraccion option").length > 1)
                                                                            {
                                                                                $("#cmbFraccion").attr("disabled", false);
                                                                            } else
                                                                            {
                                                                                $("#cmbFraccion").attr("disabled", true);
                                                                            }

                                                                        });
                                                                    }
                                                            );
                                                        });

                                            });

                                            function limpiar()
                                            {
                                                $('#cmbArticulo').val(0);
                                                $("#cmbFraccion").attr("disabled", true)
                                                $('#cmbFraccion').val(0);
                                                $("#cmbInciso").attr("disabled", true)
                                                $('#cmbInciso').val(0);
                                                $("#cmbApartado").attr("disabled", true)
                                                $('#cmbApartado').val(0);
                                            }

                                            function grabar()
                                            {
                                                if (valido()) {
                                                    $("#xAccion").val("grabar");
                                                    $("#frmPermisos").submit();
                                                }
                                            }

                                            function valido()
                                            {
                                                var valido = true;
                                                var msg = "";
                                                if ($("#cmbArea").val() === "0")
                                                {
                                                    msg += "Elija un área por favor. \n";
                                                    valido = false;
                                                }
                                                if ($("#cmbArticulo").val() === "0")
                                                {
                                                    msg += "Elija un artículo por favor. \n";
                                                    valido = false;
                                                }
                                                if ($("#cmbFraccion").val() === "0")
                                                {
                                                    msg += "Elija una fracción por favor. \n";
                                                    valido = false;
                                                }

                                                if (!valido)
                                                {
                                                    alert(msg);
                                                }

                                                return valido;
                                            }

                                            function cargar(xCveArea, xCveArticulo, xCveFraccion)
                                            {
                                                $("#xCveArea").val(xCveArea);
                                                $("#xCveArticulo").val(xCveArticulo);
                                                $("#xCveFraccion").val(xCveFraccion);
                                                $("#xAccion").val("cargar");
                                                $("#frmPermisos").submit();
                                            }

        </script>
    </body>
</html>