<?php session_start();

    $area = isset($_SESSION['area']) ? (int) $_SESSION['area'] : 0;
    $origen = "inciso";

    if (!isset($_SESSION['usr']) or $area != 1) {
        header("Location: ../index.php");
        die();
        return;
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
            <?php include '../php/include-header.php';?>

            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>

            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Incisos</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="formIncisos" id="formIncisos" method="post">
                                    <input name="xCveInciso" id="xCveInciso" type="hidden" class="form-control" value="0" readonly="">
                                    <div class="form-group">
                                        <label for="cmbArticulo">Artículo:</label>
                                        <select id="xCveArticulo" name="xCveArticulo" class="form-control">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                            <?php  
                                                $sql = "SELECT CVE_ARTICULO IDART,NOMBRE,DESCRIPCION,ACTIVO FROM ARTICULOS WHERE ACTIVO = 1 ORDER BY DESCRIPCION";
                                                $rst = UtilDB::ejecutaConsulta($sql);
                                                if ($rst->rowCount() != 0) {
                                                    foreach($rst as $row) {
                                            ?>
                                            <option value="<?php echo($row["IDART"]);?>"><?php echo($row["NOMBRE"]);?></option>                           
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
                                        <label for="cmbArticulo">Fracción:</label>
                                        <select name="xCveFraccion" class="form-control" id="xCveFraccion">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                        </select>
                                        <input type="hidden" size="5" name="xCveFraccionV" id="xCveFraccionV" value="0">
                                    </div>

                                    <div class="form-group">
                                        <label for="txtDescripcion">Descripción:</label>
                                        <textarea name="xDescripcion" id="xDescripcion" cols="38" rows="5" maxlength="500" class="form-control" value=""></textarea>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="xActivo" id="xActivo" value="1" onclick="Activo(this.checked);"/>
                                            <input name="xActivoVal" id="xActivoVal" type="hidden" class="form-control" value="">
                                            Activo
                                        </label>
                                    </div>
                                    <div class="form-group" style="text-align: center">
                                        <input class="btn btn-block btn-success" value="Guardar" readonly="" onclick="grabar()"/>
                                        <input class="btn btn-block btn-primary" value="Limpiar" readonly="" onclick="limpiar()"/>                                      
                                    </div>

                                    <div style="width:100%" id="alertaIncisos" align="center">
                                        <div style="clear:both;"></div>
                                    </div> 

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="muestra" style="width:100%">
                <div id="muestraIncisos">

                </div> 
            </div>

        </div>

        <?php include '../php/include-footer.php';?>
        <script src="../js/jquery-3.2.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>        
        <script src="../js/bootstrap-datepicker.min.js"></script>
        <script src="../js/bootstrap-datepicker.es.min.js"></script>
        <script src="../js/jquery.dataTables.min.js"></script>
        <script src="../js/dataTables.bootstrap.min.js"></script>
        <script src="../js/infoITAIP.min.js"></script>
        <script>

                                            $(document).ready(function () {

                                                $('.input-group.date').datepicker({
                                                    format: 'dd/mm/yyyy',
                                                    language: 'es',
                                                    todayHighlight: true
                                                });
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
                                                                        $.post("acciones.jsp", {cveArt: elegido, xAccion: "cargaComboFracciones"}, function (data) {
                                                                            $("#xCveFraccion").html(data);
                                                                            $("#xCveFraccion").attr("disabled", false);
                                                                        });
                                                                    }
                                                            );
                                                        });

                                                $("#xCveFraccion").change(
                                                        function ()
                                                        {
                                                            $("#xCveFraccion option:selected").each(
                                                                    function ()
                                                                    {
                                                                        elegido = $(this).val();
                                                                        $("#xCveFraccionV").val(elegido);
                                                                    }
                                                            );
                                                        });

                                            });

                                            function limpiar()
                                            {
                                                $('#xCveInciso').val(0);
                                                $('#xCveArticulo').val(0);
                                                $('#xCveArticuloV').val(0);
                                                $("#xCveFraccion").attr("disabled", true)
                                                $('#xCveFraccion').val(0);
                                                $('#xCveFraccionV').val(0);
                                                $('#xDescripcion').val('');
                                                $("#xActivo").prop("checked", "checked");
                                                $('#xActivoVal').val(1);

                                                $('#xDescripcion').focus();

                                                $('#alertaIncisos').html('');
                                                muestraIncisos();
                                            }

                                            function Activo(v)
                                            {
                                                if (v === true) {
                                                    $('#xActivoVal').val(1);
                                                } else {
                                                    $('#xActivoVal').val(0);
                                                }
                                            }

                                            function grabar()
                                            {

                                                if ($("#xCveArticuloV").val() === "0") {
                                                    $("#alertaIncisos").html("<span class='custom critical'>Debe seleccionar un artículo.</span>");
                                                    $("#xCveArticulo").focus();
                                                    return;
                                                }
                                                if ($("#xCveFraccionV").val() === "0") {
                                                    $("#alertaIncisos").html("<span class='custom critical'>Debe seleccionar una fracción.</span>");
                                                    $("#xCveFraccion").focus();
                                                    return;
                                                }
                                                if ($("#xDescripcion").val() === "") {
                                                    $("#alertaIncisos").html("<span class='custom critical'>Debe capturar algo en el campo descripción.</span>");
                                                    $("#xDescripcion").focus();
                                                    return;
                                                }

                                                var datos = $("#formIncisos").serialize();
                                                datos += "&xAccion=grabaIncisos";
                                                $.ajax(
                                                        {
                                                            url: "acciones.jsp", type: "POST", data: datos, success: function (result)
                                                            {
                                                                var n = result.trim();
                                                                var no = n.split('|');
                                                                $("#alertaIncisos").html("<span class='custom entrar'>" + no[0] + "</span>");
                                                                $("#xCveInciso").val(no[1]);
                                                                muestraIncisos();
                                                                setTimeout(function () {
                                                                    limpiar()();
                                                                }, 2000);
                                                            }
                                                        }
                                                );
                                            }

                                            function muestraIncisos() {
                                                $.post("acciones.jsp", {xAccion: 'muestraIncisos'}, function (data) {
                                                    $("#muestraIncisos").html(data);
                                                });
                                            }

                                            function recargaInciso(url, cve)
                                            {
                                                $.post(url, {cve: cve}, function (data) {
                                                    $("#alertaIncisos").html('');
                                                    $("#xCveInciso").val(data.xCveInciso);
                                                    $("#xCveArticulo").val(data.xCveArticulo);
                                                    $("#xCveArticuloV").val(data.xCveArticulo);

                                                    $("#xCveFraccion").attr("disabled", false)
                                                    $("#xCveFraccion").val(data.xCveFraccion);
                                                    $("#xCveFraccionV").val(data.xCveFraccion);

                                                    $.post("acciones.jsp", {cveArt: data.xCveArticulo, cveFrac: data.xCveFraccion, xAccion: "cargaComboFracciones"}, function (data) {
                                                        $("#xCveFraccion").html(data);
                                                        $("#xCveFraccion").attr("disabled", false)
                                                    });

                                                    $("#xDescripcion").val(data.xDescripcion);
                                                    $("#xDescripcion").select();

                                                    if (data.xActivo === true) {
                                                        $("#xActivo").prop("checked", "checked");
                                                        $('#xActivoVal').val(1);
                                                    } else {
                                                        $("#xActivo").prop("checked", "");
                                                        $('#xActivoVal').val(0);
                                                    }
                                                }, "json");
                                            }

        </script>
    </body>
</html>