<?php session_start();

    $area = isset($_SESSION['area']) ? (int) $_SESSION['area'] : 0;
    $origen = "fraccion";

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
                            <h3 class="text-center text-uppercase">Fracciones</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="formFracciones" id="formFracciones" method="post">
                                    <input name="xCveFraccion" id="xCveFraccion" type="hidden" class="form-control" value="0" readonly="">
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
                                        <label for="txtNombre">Nombre</label>
                                        <input name="xNombre" id="xNombre" type="text" maxlength="15" class="form-control" value="">
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

                                    <div style="width:100%" id="alertaFracciones" align="center">
                                        <div style="clear:both;"></div>
                                    </div> 

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="muestra" style="width:100%">
                <div id="muestraFracciones">

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
                                                                    }
                                                            );
                                                        })

                                            });

                                            function limpiar()
                                            {
                                                $('#xCveFraccion').val(0);
                                                $('#xCveArticulo').val(0);
                                                $('#xCveArticuloV').val(0);
                                                $('#xNombre').val('');
                                                $('#xDescripcion').val('');
                                                $("#xActivo").prop("checked", "checked");
                                                $('#xActivoVal').val(1);

                                                $('#xNombre').focus();

                                                $('#alertaFracciones').html('');
                                                muestraFracciones();
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
                                                    $("#alertaFracciones").html("<span class='custom critical'>Debe seleccionar un artículo.</span>");
                                                    $("#xCveArticulo").focus();
                                                    return;
                                                }
                                                if ($("#xNombre").val() === "") {
                                                    $("#alertaFracciones").html("<span class='custom critical'>Debe capturar algo en el campo nombre.</span>");
                                                    $("#xNombre").focus();
                                                    return;
                                                }
                                                if ($("#xDescripcion").val() === "") {
                                                    $("#alertaFracciones").html("<span class='custom critical'>Debe capturar algo en el campo descripción.</span>");
                                                    $("#xDescripcion").focus();
                                                    return;
                                                }

                                                var datos = $("#formFracciones").serialize();
                                                datos += "&xAccion=grabaFracciones";
                                                $.ajax(
                                                        {
                                                            url: "acciones.jsp", type: "POST", data: datos, success: function (result)
                                                            {
                                                                var n = result.trim();
                                                                var no = n.split('|');
                                                                $("#alertaFracciones").html("<span class='custom entrar'>" + no[0] + "</span>");
                                                                $("#xCveFraccion").val(no[1]);
                                                                muestraFracciones();

                                                                setTimeout(function () {
                                                                    limpiar()();
                                                                }, 2000);
                                                            }
                                                        }
                                                );
                                            }

                                            function muestraFracciones() {
                                                $.post("acciones.jsp", {xAccion: 'muestraFracciones'}, function (data) {
                                                    $("#muestraFracciones").html(data);
                                                });
                                            }

                                            function recargaFraccion(url, cve)
                                            {
                                                $.post(url, {cve: cve}, function (data) {
                                                    $("#alertaFracciones").html('');
                                                    $("#xCveFraccion").val(data.xCveFraccion);
                                                    $("#xCveArticulo").val(data.xCveArticulo);
                                                    $("#xCveArticuloV").val(data.xCveArticulo);
                                                    $("#xNombre").val(data.xNombre);
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