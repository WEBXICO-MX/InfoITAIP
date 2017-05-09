<?php
session_start();
require_once '../class/Area.php';
require_once '../class/ChromePhp.php';
//ChromePhp::log("sesion usr: " . $_SESSION['usr']);
//ChromePhp::log("sesion area: " . $_SESSION['area']);
if (!isset($_SESSION['usr']) or $_SESSION['area'] != 1) {
    header('Location: ../index.php');
    die();
    return;
}

$origen = "anexos";
$usuario = isset($_SESSION['usr']) ? (int) $_SESSION['usr'] : 0;
$anio = isset($_SESSION['anio']) ? (int) $_SESSION['anio'] : 0;
$trimestre = isset($_SESSION['trimestre']) ? (int) $_SESSION['trimestre'] : 0;
$area_sesion = isset($_SESSION['area']) ? (int) $_SESSION['area'] : 0;
//ChromePhp::log("anio: $anio");
//ChromePhp::log("trimestre: $trimestre");

if ($anio == 0 || $trimestre == 0) {
    header('Location: elegir-anio-trimestre.php');
    die();
    return;
}

$trimestres = ["1er trimestre (enero-marzo)", "2do trimestre (abril-junio)", "3er trimestre (julio-septimebre)", "4to trimestre (octubre-diciembre)"];
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
        <link href="../css/bootstrap-datepicker.min.css" rel="stylesheet"> 
        <link href="../css/dataTables.bootstrap.min.css" rel="stylesheet"/>
        <link href="../css/infoITAIP.css" rel="stylesheet"/>
        <style>
            textarea {
                resize: none;
            }
        </style>
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
                <div class="col-md-12">
                    <h2 class="text-center"><?php echo($trimestres[$trimestre - 1]); ?> <?php echo($anio); ?></h2>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Anexos</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="formDocumento" id="formDocumento" method="post">
                                    <input name="xCveUsuario" id="xCveUsuario" type="hidden" value="<?php echo($usuario); ?>" readonly="">
                                    <br>
                                    <input name="xCveAnio" id="xCveAnio" type="hidden" value="<?php echo($anio); ?>" readonly="">
                                    <input name="xCveTrimestre" id="xCveTrimestre" type="hidden" value="<?php echo($trimestre); ?>" readonly="">
                                    <input name="xCveDocumento" id="xCveDocumento" type="hidden" value="0" readonly="">

                                    <input name="xCveUsuario2" id="xCveUsuario2" type="hidden" value="<?php echo($usuario); ?>" readonly="">
                                    <input name="xFechaActualizacionR" id="xFechaActualizacionR" type="hidden" value="" readonly="">

                                    <div class="form-group">
                                        <label for="cmbArticulo">Artículo:</label>
                                        <select id="xCveArticulo" name="xCveArticulo" class="form-control">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                            <?php
                                            $sql = "SELECT cve_articulo,DESCRIPCION,nombre,ACTIVO FROM ARTICULOS WHERE ACTIVO = 1  ";
                                            if ($area_sesion != 1) {
                                                $sql .= "AND CVE_ARTICULO IN (SELECT cve_articulo FROM permisos WHERE activo = 1 AND cve_area = " . $area_sesion . " GROUP BY cve_articulo) ";
                                            }
                                            $sql .= "ORDER BY cve_articulo";
                                            $rst = UtilDB::ejecutaConsulta($sql);
                                            if ($rst->rowCount() != 0) {
                                                foreach ($rst as $row) {
                                                    ?>
                                                    <option value="<?php echo($row["cve_articulo"]); ?>"><?php echo($row["nombre"]); ?></option>                           
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <option value="0" selected>No existen artículos.</option>
                                                <?php
                                            }
                                            $rst->closeCursor();
                                            ?>                                        
                                        </select>
                                        <input type="hidden" name="xCveArticuloV" id="xCveArticuloV" value="0" style="width:5%" readonly="">
                                    </div>

                                    <div class="form-group">
                                        <label for="cmbFraccion">Fracción:</label>
                                        <select name="xCveFraccion" class="form-control" id="xCveFraccion">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                        </select>
                                        <input type="hidden" size="5" name="xCveFraccionV" id="xCveFraccionV" value="0" readonly="">
                                    </div>

                                    <div class="form-group">
                                        <label for="cmbInciso">Inciso:</label>
                                        <select name="xCveInciso" class="form-control" id="xCveInciso">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                        </select>
                                        <input type="hidden" size="5" name="xCveIncisoV" id="xCveIncisoV" value="0" readonly="">
                                    </div>

                                    <div class="form-group">
                                        <label for="cmbApartado">Apartado:</label>
                                        <select name="xCveApartado" class="form-control" id="xCveApartado">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                        </select>
                                        <input type="hidden" size="5" name="xCveApartadoV" id="xCveApartadoV" value="0" readonly="">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtNombre">Nombre:</label>                                        
                                        <input name="xNombre" id="xNombre" type="text" maxlength="100" class="form-control" value="">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtFechaActualizacionDocumento">Fecha de actualización del documento:</label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control" name="xFechaActualizacion" id="xFechaActualizacion" value=""><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="txtRuta">Ruta del documento:</label>
                                        <input name="xRuta" id="xRuta" type="text" maxlength="100" class="form-control" value="---" readonly="">
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="xActivo" id="xActivo" value="1" onclick="Activo(this.checked);"/>
                                            <input name="xActivoVal" id="xActivoVal" type="hidden" class="form-control" value="" readonly="">
                                            Activo
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-block btn-success" value="Guardar" readonly="" onclick="grabar()"/>  
                                        <input class="btn btn-block btn-primary" value="Limpiar" readonly="" onclick="limpiar()"/>                                                                              
                                    </div>

                                    <div style="width:100%" id="alertaDocumento" align="center">
                                        <div style="clear:both;"></div>
                                    </div> 

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal fade" id="mMiModal" tabindex="-1" role="dialog" aria-labelledby="mMiModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="muestra" style="width:100%">
                <div id="muestraDocumentos">

                </div> 
            </div>

        </div>

        <?php require_once 'include-footer.php'; ?>
        <script src="../js/jquery-3.2.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>        
        <script src="../js/bootstrap-datepicker.min.js"></script>
        <script src="../js/bootstrap-datepicker.es.min.js"></script>
        <script src="../js/jquery.dataTables.min.js"></script>
        <script src="../js/dataTables.bootstrap.min.js"></script>
        <script src="../js/infoITAIP.min.js"></script>
        <script>

                                            var hoy = new Date();
                                            var dd = hoy.getDate();
                                            var mm = hoy.getMonth() + 1; //hoy es 0!
                                            var yyyy = hoy.getFullYear();
                                            if (dd < 10) {
                                                dd = '0' + dd
                                            }
                                            if (mm < 10) {
                                                mm = '0' + mm
                                            }
                                            hoy = dd + '/' + mm + '/' + yyyy;

                                            $(document).ready(function () {

                                                $('.input-group.date').datepicker({
                                                    format: 'dd/mm/yyyy',
                                                    language: 'es',
                                                    todayHighlight: true
                                                });

                                                var hoy = new Date();
                                                var dd = hoy.getDate();
                                                var mm = hoy.getMonth() + 1; //hoy es 0!
                                                var yyyy = hoy.getFullYear();
                                                if (dd < 10) {
                                                    dd = '0' + dd
                                                }
                                                if (mm < 10) {
                                                    mm = '0' + mm
                                                }
                                                hoy = dd + '/' + mm + '/' + yyyy;

                                                limpiar();

                                                $('textarea[maxlength]').keyup(function () {
                                                    var limit = parseInt($(this).attr('maxlength'));
                                                    var text = $(this).val();
                                                    var chars = text.length;
                                                    if (chars > limit) {
                                                        var new_text = text.substr(0, limit);
                                                        $(this).val(new_text);
                                                    }
                                                });

                                                $("#xCveArticulo").change(
                                                        function ()
                                                        {
                                                            $("#xCveArticulo option:selected").each(
                                                                    function ()
                                                                    {
                                                                        elegido = $(this).val();
                                                                        $("#xCveArticuloV").val(elegido);
                                                                        $("#xCveFraccionV").val(0);

                                                                        $("#xCveIncisoV").val(0);
                                                                        $("#xCveInciso").html("<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>")
                                                                        $("#xCveInciso").attr("disabled", true)

                                                                        $("#xCveApartadoV").val(0);
                                                                        $("#xCveApartado").html("<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>")
                                                                        $("#xCveApartado").attr("disabled", true)

                                                                        $.post("acciones.php", {cveArt: elegido, xAccion: "cargaComboFracciones"}, function (data) {
                                                                            $("#xCveFraccion").html(data);
                                                                            $("#xCveFraccion").attr("disabled", false)
                                                                        });
                                                                    }
                                                            );
                                                        })

                                                $("#xCveFraccion").change(
                                                        function ()
                                                        {
                                                            $("#xCveFraccion option:selected").each(
                                                                    function ()
                                                                    {
                                                                        elegido = $(this).val();
                                                                        $("#xCveFraccionV").val(elegido);
                                                                        $("#xCveIncisoV").val(0);
                                                                        $("#xCveApartadoV").val(0);
                                                                        $("#xCveApartado").html("<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>")
                                                                        $("#xCveApartado").attr("disabled", true)


                                                                        var cveArt = $("#xCveArticuloV").val();

                                                                        $.post("acciones.php", {cveArt: cveArt, cveFrac: elegido, xAccion: "cargaComboIncisos"}, function (data) {
                                                                            $("#xCveInciso").html(data);
                                                                            $("#xCveInciso").attr("disabled", false)
                                                                        });
                                                                    }
                                                            );
                                                        })

                                                $("#xCveInciso").change(
                                                        function ()
                                                        {
                                                            $("#xCveInciso option:selected").each(
                                                                    function ()
                                                                    {
                                                                        elegido = $(this).val();
                                                                        $("#xCveIncisoV").val(elegido);
                                                                        $("#xCveApartadoV").val(0);

                                                                        var cveArt = $("#xCveArticuloV").val();
                                                                        var cveFrac = $("#xCveFraccionV").val();
                                                                        var cveInc = $("#xCveIncisoV").val();

                                                                        $.post("acciones.php", {cveArt: cveArt, cveFrac: cveFrac, cveInc: cveInc, xAccion: "cargaComboApartado"}, function (data) {
                                                                            $("#xCveApartado").html(data);
                                                                            $("#xCveApartado").attr("disabled", false)
                                                                        });
                                                                    }
                                                            );
                                                        })

                                                $("#xCveApartado").change(
                                                        function ()
                                                        {
                                                            $("#xCveApartado option:selected").each(
                                                                    function ()
                                                                    {
                                                                        elegido = $(this).val();
                                                                        $("#xCveApartadoV").val(elegido);
                                                                    }
                                                            );
                                                        })

                                            });

                                            function limpiar()
                                            {
                                                $('#xCveDocumento').val(0);
                                                $('#xCveArticulo').val(0);
                                                $('#xCveArticuloV').val(0);

                                                $("#xCveFraccion").attr("disabled", true)
                                                $('#xCveFraccion').val(0);
                                                $('#xCveFraccionV').val(0);

                                                $("#xCveInciso").attr("disabled", true)
                                                $('#xCveInciso').val(0);
                                                $('#xCveIncisoV').val(0);

                                                $("#xCveApartado").attr("disabled", true)
                                                $('#xCveApartado').val(0);
                                                $('#xCveApartadoV').val(0);

                                                $('#xFechaActualizacion').val('');
                                                $('#xNombre').val('');
                                                $('#xRuta').val('---');
                                                $("#xActivo").prop("checked", "checked");
                                                $('#xActivoVal').val(1);
                                                $('#xFechaActualizacionR').val(hoy);

                                                $('#xNombre').focus();

                                                $('#alertaDocumento').html('');
                                                muestraDoctos();
                                            }

                                            function Activo(v)
                                            {
                                                if (v == true) {
                                                    $('#xActivoVal').val(1);
                                                } else {
                                                    $('#xActivoVal').val(0);
                                                }
                                            }

                                            function grabar()
                                            {

                                                if ($("#xCveArticuloV").val() == "0") {
                                                    $("#alertaDocumento").html("<span class='custom critical'>Debe seleccionar un artículo.</span>");
                                                    $("#xCveArticulo").focus();
                                                    return
                                                }
                                                if ($("#xCveFraccionV").val() == "0") {
                                                    $("#alertaDocumento").html("<span class='custom critical'>Debe seleccionar una fracción.</span>");
                                                    $("#xCveFraccion").focus();
                                                    return
                                                }
                                                /*if ($("#xCveIncisoV").val() == "0") {
                                                 $("#alertaDocumento").html("<span class='custom critical'>Debe seleccionar un inciso.</span>");
                                                 $("#xCveInciso").focus();
                                                 return
                                                 }
                                                 if ($("#xCveApartadoV").val() == "0") {
                                                 $("#alertaDocumento").html("<span class='custom critical'>Debe seleccionar un apartado.</span>");
                                                 $("#xCveApartado").focus();
                                                 return
                                                 }*/
                                                if ($("#xNombre").val() == "") {
                                                    $("#alertaDocumento").html("<span class='custom critical'>Debe capturar algo en el campo nombre.</span>");
                                                    $("#xNombre").focus();
                                                    return
                                                }
                                                if ($("#xFechaActualizacion").val() == "") {
                                                    $("#alertaDocumento").html("<span class='custom critical'>Debe capturar algo en el campo fecha.</span>");
                                                    $("#xFechaActualizacion").focus();
                                                    return
                                                }

                                                var datos = $("#formDocumento").serialize();
                                                datos += "&xAccion=grabaTransparencia&xPagina=1";
                                                console.log(datos);
                                                $.ajax(
                                                        {
                                                            url: "acciones.php", type: "POST", data: datos, success: function (result)
                                                            {
                                                                /*var n = result.trim();
                                                                var no = n.split('|');
                                                                $("#alertaDocumento").html("<span class='custom entrar'>" + no[0] + "</span>");
                                                                $("#xCveDocumento").val(no[1]);
                                                                muestraDoctos();
                                                                setTimeout(function () {
                                                                    limpiar();
                                                                }, 2000);*/
                                                            }
                                                        }
                                                );
                                            }

                                            function muestraDoctos() {
                                                $.post("acciones.php", {xAccion: 'muestraDoctos', xPagina: 1}, function (data) {
                                                    $("#muestraDocumentos").html(data);
                                                });
                                            }

                                            function recargaDocto(url, cve)
                                            {
                                                $.post(url, {cve: cve}, function (data) {
                                                    $("#alertaDocumento").html('');

                                                    $("#xCveDocumento").val(data.xCveDocumento);
                                                    $("#xCveArticulo").val(data.xCveArticulo);
                                                    $("#xCveArticuloV").val(data.xCveArticulo);

                                                    $("#xCveFraccion").attr("disabled", false)
                                                    $("#xCveFraccion").val(data.xCveFraccion);
                                                    $("#xCveFraccionV").val(data.xCveFraccion);

                                                    $.post("acciones.php", {cveArt: data.xCveArticulo, cveFrac: data.xCveFraccion, xAccion: "cargaComboFracciones"}, function (data) {
                                                        $("#xCveFraccion").html(data);
                                                        $("#xCveFraccion").attr("disabled", false)
                                                    });

                                                    $("#xCveInciso").attr("disabled", false)
                                                    $("#xCveInciso").val(data.xCveInciso);
                                                    $("#xCveIncisoV").val(data.xCveInciso);

                                                    $.post("acciones.php", {cveArt: data.xCveArticulo, cveFrac: data.xCveFraccion, cveInc: data.xCveInciso, xAccion: "cargaComboIncisos"}, function (data) {
                                                        $("#xCveInciso").html(data);
                                                        $("#xCveInciso").attr("disabled", false)
                                                    });

                                                    $("#xCveApartado").attr("disabled", false)
                                                    $("#xCveApartado").val(data.xCveApartado);
                                                    $("#xCveApartadoV").val(data.xCveApartado);
                                                    $("#xCveUsuario").val(data.xCveUsuario);

                                                    $.post("acciones.php", {cveArt: data.xCveArticulo, cveFrac: data.xCveFraccion, cveInc: data.xCveInciso, cveApa: data.xCveApartado, xAccion: "cargaComboApartado"}, function (data) {
                                                        $("#xCveApartado").html(data);
                                                        $("#xCveApartado").attr("disabled", false)
                                                    });


                                                    $("#xFechaActualizacion").val(data.xFechaActualizacion);
                                                    $("#xNombre").val(data.xNombre);
                                                    $("#xRuta").val(data.xRuta);
                                                    $("#xNombre").select();

                                                    if (data.xActivo == true) {
                                                        $("#xActivo").prop("checked", "checked");
                                                        $('#xActivoVal').val(1);
                                                    } else {
                                                        $("#xActivo").prop("checked", "");
                                                        $('#xActivoVal').val(0);
                                                    }
                                                }, "json");
                                            }

                                            function modalArchivos(cveDoc, nombre)
                                            {
                                                $('#mMiModal').modal('show').find('.modal-content').load("frm-subir-archivo.php", {"xOrigen": "anexos", xcveDoc: cveDoc, nombre: nombre, xPagina: 1});
                                            }

        </script>
    </body>
</html>