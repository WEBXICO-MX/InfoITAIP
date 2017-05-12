<?php
require_once '../class/Area.php';
$sql = "";
$rst = NULL;
$rst2 = NULL;
$rst3 = NULL;
$rst4 = NULL;
$html = "";
$html2 = "";
$count = 0;
$count2 = 0;
$anio_actual = date("Y");
$mes_actual = date("m");
$trimestre_actual = getTrimestreActual($mes_actual);
$anios = [2015, 2016, 2017];
$trimestres = ["1er. Trimestre (enero-marzo)", "2do. Trimestre (abril-junio)", "3er. Trimestre (julio-septiembre)", "4to. Trimestre (octubre-diciembre)"];
$origen = "informacion-publica";
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
        <style>
            body { 
                background: url(../img/fondo-pantalla.jpg) no-repeat  center 130px;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size:cover;
                font-family: roboto;    /* Margin bottom by footer height */
                margin-bottom: 301.5px;
            }
        </style>
    <body>
        <div class="container-fluid">
            <?php require_once 'include-header.php'; ?>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Información pública</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <?php
                                $sql = "SELECT * FROM articulos WHERE activo = 1 ORDER BY cve_articulo ASC";
                                $rst = UtilDB::ejecutaConsulta($sql);

                                if ($rst->rowCount() != 0) {
                                    $html .= "<ul class=\"nav nav-tabs\">";
                                    $html2 .= "<div class=\"tab-content\">";
                                    foreach ($rst as $row) {
                                        $html .= "<li " . ($count == 0 ? "class=\"active\"" : "") . "><a data-toggle=\"tab\" href=\"#articulo" . $row["cve_articulo"] . "\"><span class=\"glyphicon glyphicon-tasks\"></span> " . $row["nombre"] . "</a></li>";

                                        $html2 .= "<div id=\"articulo" . $row["cve_articulo"] . "\" class=\"tab-pane fade " . ($count == 0 ? "in active" : "") . "\">";
                                        $html2 .= "<p>" . $row["descripcion"] . "</p>";
                                        $sql = "SELECT * FROM fracciones WHERE activo = 1 AND cve_articulo = " . $row["cve_articulo"] . " ORDER BY cve_fraccion ASC";
                                        $rst2 = UtilDB::ejecutaConsulta($sql);

                                        if ($rst2->rowCount() != 0) {
                                            $html2 .= "<div class=\"panel-group\" id=\"accordion-articulo-" . $row["cve_articulo"] . "\">";
                                            foreach ($rst2 as $row2) {
                                                $html2 .= "<div class=\"panel panel-default\">";
                                                $html2 .= "<div class=\"panel-heading\">";
                                                $html2 .= "<h4 class=\"panel-title\">";
                                                $html2 .= "<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion-articulo-" . $row["cve_articulo"] . "\" href=\"#collapse-fraccion-" . $row2["cve_fraccion"] . "\">";
                                                $html2 .= "<span class=\"glyphicon glyphicon-bookmark\"></span> " . $row2["nombre"];
                                                $html2 .= "</a>";
                                                $html2 .= "</h4>";
                                                $html2 .= "</div>";
                                                $html2 .= "<div id=\"collapse-fraccion-" . $row2["cve_fraccion"] . "\" class=\"panel-collapse collapse " . ($count2 == 0 ? "in" : "") . "\">";
                                                //$html2 .= "<div id=\"collapse-fraccion-".$row2["cve_fraccion"]."\" class=\"panel-collapse collapse \">";
                                                $html2 .= "<div class=\"panel-body\">";
                                                $html2 .= "<p>" . $row2["descripcion"] . "</p><br/><br/>";

                                                $sql = "SELECT * FROM incisos WHERE cve_articulo = " . $row["cve_articulo"] . " AND cve_fraccion = " . $row2["cve_fraccion"] . " AND activo = 1 ORDER BY cve_inciso ASC";
                                                $rst3 = UtilDB::ejecutaConsulta($sql);

                                                if ($rst3->rowCount() != 0) {
                                                    $html2 .= "<div class=\"panel-group\" id=\"accordion-incisos-" . $row2["cve_fraccion"] . "\">";
                                                    foreach ($rst3 as $row3) {
                                                        $html2 .= "<div class=\"panel panel-success\">";
                                                        $html2 .= "<div class=\"panel-heading\">";
                                                        $html2 .= "<h4 class=\"panel-title\">";
                                                        $html2 .= "<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion-incisos-" . $row2["cve_fraccion"] . "\" href=\"#collapse-inciso-" . $row3["cve_inciso"] . "\">";
                                                        $html2 .= "<span class=\"glyphicon glyphicon-asterisk\"></span> " . $row3["descripcion"];
                                                        $html2 .= "</a>";
                                                        $html2 .= "</h4>";
                                                        $html2 .= "</div>";
                                                        $html2 .= "<div id=\"collapse-inciso-" . $row3["cve_inciso"] . "\" class=\"panel-collapse collapse \">";
                                                        $html2 .= "<div class=\"panel-body\">";

                                                        $sql = "SELECT * FROM apartados WHERE cve_articulo = " . $row["cve_articulo"] . " AND cve_fraccion = " . $row2["cve_fraccion"] . " AND cve_inciso = " . $row3["cve_inciso"] . " AND activo = 1 ORDER BY cve_apartado ASC";
                                                        $rst4 = UtilDB::ejecutaConsulta($sql);

                                                        if ($rst4->rowCount() != 0) {
                                                            $html2 .= " <div class=\"panel-group\" id=\"accordion-apartados-" . $row3["cve_inciso"] . "\">";
                                                            foreach ($rst4 as $row4) {
                                                                $html2 .= "<div class=\"panel panel-info\">";
                                                                $html2 .= "<div class=\"panel-heading\">";
                                                                $html2 .= "<h4 class=\"panel-title\">";
                                                                $html2 .= "<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion-apartados-" . $row3["cve_inciso"] . "\" href=\"#collapse-apartado-" . $row4["cve_apartado"] . "\">";
                                                                $html2 .= "<span class=\"glyphicon glyphicon-th-list\"></span> " . $row4["descripcion"];
                                                                $html2 .= "</a>";
                                                                $html2 .= "</h4>";
                                                                $html2 .= "</div>";
                                                                $html2 .= "<div id=\"collapse-apartado-" . ($row4["cve_apartado"]) . "\" class=\"panel-collapse collapse\">";
                                                                $html2 .= "<div class=\"panel-body\">";
                                                                $html2 .= (getAniosTrimestres($row["cve_articulo"], $row2["cve_fraccion"], $row3["cve_inciso"], $row4["cve_apartado"], "panel-default", $anio_actual, $mes_actual, $trimestre_actual, $anios, $trimestres));
                                                                $html2 .= "</div>";
                                                                $html2 .= "</div>";
                                                                $html2 .= "</div>";
                                                            }
                                                            $html2 .= "</div>";
                                                        } else {
                                                            $html2 .= (getAniosTrimestres($row["cve_articulo"], $row2["cve_fraccion"], $row3["cve_inciso"], 0, "panel-info", $anio_actual, $mes_actual, $trimestre_actual, $anios, $trimestres));
                                                        }
                                                        $rst4->closeCursor();

                                                        $html2 .= "</div>";
                                                        $html2 .= "</div>";
                                                        $html2 .= "</div>";
                                                    }
                                                    $html2 .= "</div>";
                                                } else {
                                                    $html2 .= (getAniosTrimestres($row["cve_articulo"], $row2["cve_fraccion"], 0, 0, "panel-success", $anio_actual, $mes_actual, $trimestre_actual, $anios, $trimestres));
                                                }

                                                $rst3->closeCursor();

                                                $html2 .= "</div>";
                                                $html2 .= "</div>";
                                                $html2 .= "</div>";
                                                $count2++;
                                            }
                                            $html2 .= "</div>";
                                            $count2 = 0;
                                        }

                                        $rst2->closeCursor();

                                        $html2 .= "</div>";
                                        $count++;
                                    }
                                    $html .= "</div>";
                                    $html .= "</ul>";
                                    $html .= $html2;
                                    $count = 0;



                                    $rst->closeCursor();
                                } else {
                                    $html .= "<h3 class=\"text-center text-uppercase\">No hay datos para mostrar por el momento</h3>";
                                }

                                echo($html);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once 'include-footer.php'; ?>
        <script src="../js/jquery-2.2.4.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>        
        <script src="../js/infoITAIP.min.js"></script>
        <script>
            var accordion = "";
            var articulo = 0;
            var fraccion = 0;
            var incisio = 0;
            var apartado = 0;
            var anio = 0;
            var trimestre = 0;

            $(document).ready(function () {
                $("[id*='art-'] .panel-group").each(function () {
                    $('#' + this.id).on('shown.bs.collapse', function (e) {
                        accordion = $(e.target).attr("id");
                        $('#' + accordion + " div.panel-body").html("<p><img src=\"../img/ajax-loading.gif\" alt=\"cargando\"/> cargando ...</p>");
                        articulo = parseInt(accordion.substring(accordion.indexOf("art") + 4, accordion.indexOf("art") + 6));
                        fraccion = parseInt(accordion.substring(accordion.indexOf("frac") + 5, accordion.indexOf("frac") + 7));
                        inciso = parseInt(accordion.substring(accordion.indexOf("inc") + 4, accordion.indexOf("inc") + 6));
                        apartado = parseInt(accordion.substring(accordion.indexOf("apt") + 4, accordion.indexOf("apt") + 6));
                        anio = parseInt(accordion.substring(accordion.indexOf("anio") + 5, accordion.indexOf("anio") + 9));
                        trimestre = parseInt(accordion.substring(accordion.indexOf("trim") + 5, accordion.indexOf("trim") + 7));
                        //console.log(accordion);
                        //console.log("articulo:"+articulo+",fraccion:"+fraccion+",inciso:"+inciso+",apartado:"+apartado+",año:"+anio+",trimestre:"+trimestre);
                        $('#' + accordion + " div.panel-body").load("ajax-informacion-publica.php", {"xAccion": "getDocumentos", "xPageContext": "...", "xArticulo": articulo, "xFraccion": fraccion, "xInciso": inciso, "xApartado": apartado, "xAnio": anio, "xTrimestre": trimestre});

                    });
                });
            });
        </script>
    </body>
</html>
<?php

function getTrimestreActual($mes) {
    $trimestre = 0;
    if ($mes >= 0 and $mes < 3) {
        $trimestre = 1;
    } elseif ($mes >= 3 and $mes < 6) {
        $trimestre = 2;
    } elseif ($mes >= 6 and $mes < 9) {
        $trimestre = 3;
    } else {
        $trimestre = 4;
    }
    return $trimestre;
}

function getAniosTrimestres($art, $frac, $inc, $apt, $panel_style, $anio_actual, $mes_actual, $trimestre_actual, $anios, $trimestres) {
    $html = "";
    $html2 = "";
    $count_trimestre = 0;
    $identificador = "art-" . ($art < 10 ? "0" . $art : $art) . "-frac-" . ($frac < 10 ? "0" . $frac : $frac) . "-inc-" . ($inc < 10 ? "0" . $inc : $inc) . "-apt-" . ($apt < 10 ? "0" . $apt : $apt);
    $html .= "<ul class=\"nav nav-tabs\">";
    $html2 .= "<div class=\"tab-content\">";
    foreach ($anios as $anio) {
        $html .= "<li " . ($anio == $anio_actual ? "class=\"active\"" : "") . "><a data-toggle=\"tab\" href=\"#" . ($identificador . "-tabs-anio-" . $anio) . "\"><span class=\"glyphicon glyphicon-calendar\"></span> " . $anio . "</a></li>";

        $html2 .= "<div id=\"" . ($identificador . "-tabs-anio-" . $anio) . "\" class=\"tab-pane fade " . ($anio == $anio_actual ? "in active" : "") . "\">";
        $html2 .= "<br/><br/>";
        $html2 .= "<div class=\"panel-group\" id=\"" . ($identificador . "-accordion-anio-" . $anio) . "\">";

        foreach ($trimestres as $trimestre) {
            $html2 .= "<div class=\"panel " . $panel_style . "\">";
            $html2 .= "<div class=\"panel-heading\">";
            $html2 .= "<h4 class=\"panel-title\">";
            $html2 .= "<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#" . ($identificador . "-accordion-anio-" . $anio) . "\" href=\"#" . ($identificador . "-collapse-anio-" . $anio . "-trim-0" . ($count_trimestre + 1)) . "\">";
            $html2 .= "<p><span class=\"glyphicon glyphicon-eye-open\"></span> " . $trimestre . "</p>";
            $html2 .= "</a>";
            $html2 .= "</h4>";
            $html2 .= "</div>";
            // HABILITAR LA LINEA QUE ESTA COMENTADA DEBAJO SI SE REQUIERE QUE ESTE ABIERTO POR DEFAULT EL TRIMESTRE ACTUAL EN EL AÑO ACTUAL
            //$html2 .= "<div id=\"".($identificador . "-collapse-anio-" . $anio . "-trim-0" . ($count_trimestre + 1))."\" class=\"panel-collapse collapse ".($anio_actual == $anio ? ($trimestre_actual == $count_trimestre + 1 ? "in" : "") : "")."\">";
            $html2 .= "<div id=\"" . ($identificador . "-collapse-anio-" . $anio . "-trim-0" . ($count_trimestre + 1)) . "\" class=\"panel-collapse collapse \">";
            $html2 .= "<div class=\"panel-body\">";

            $html2 .= "</div>";
            $html2 .= "</div>";
            $html2 .= "</div>";
            $count_trimestre++;
        }
        $count_trimestre = 0;
        $html2 .= "</div>";
        $html2 .= "</div>";
    }
    $html2 .= "</div>";
    $html .= "</ul>";
    $html .= $html2;

    return $html;
}
?>