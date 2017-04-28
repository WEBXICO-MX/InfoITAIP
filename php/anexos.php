<%-- 
    Document   : anexos
    Created on : 23-mar-2017, 10:23:45
    Author     : Roberto Eder Weiss Juárez
--%>

<%@page import="java.util.Calendar"%>
<%@page import="mx.edu.uttab.transparencia.comun.*"%>
<%@page contentType="text/html" pageEncoding="UTF-8" %>
<%  HttpSession httpSession = request.getSession(false);

    if (httpSession.getAttribute(Sesiones.USUARIO) == null) {
        response.sendRedirect("../index.jsp");
        return;
    }

    int usuario = httpSession.getAttribute(Sesiones.USUARIO) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.USUARIO).toString()) : 0;
    int anio = httpSession.getAttribute(Sesiones.ANIO) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.ANIO).toString()) : 0;
    int trimestre = httpSession.getAttribute(Sesiones.TRIMESTRE) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.TRIMESTRE).toString()) : 0;
    int area = httpSession.getAttribute(Sesiones.AREA) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.AREA).toString()) : 0;

    if (anio == 0 || trimestre == 0) {
        response.sendRedirect("elegir-anio-trimestre.jsp");
        return;
    }
    
   String[] trimestres = {"1er trimestre (enero-marzo)", "2do trimestre (abril-junio)", "3er trimestre (julio-septimebre)", "4to trimestre (octubre-diciembre)"};

%>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>UTTAB | Universidad Tecnológica de Tabasco</title>
        <link href="${pageContext.request.contextPath}/img/favicon.ico" rel="icon" >
        <link href="${pageContext.request.contextPath}/css/bootstrap.min.css" rel="stylesheet">
        <link href="${pageContext.request.contextPath}/css/bootstrap-datepicker.min.css" rel="stylesheet"> 
        <link href="${pageContext.request.contextPath}/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
        <link href="${pageContext.request.contextPath}/css/infoITAIP.css" rel="stylesheet"/>
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

            <jsp:include page="include-header.jsp">
                <jsp:param name="o" value="anexos" />
            </jsp:include>

            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                     <h2 class="text-center"><%= trimestres[trimestre - 1]%> <%= anio%></h2>
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
                                    <input name="xCveUsuario" id="xCveUsuario" type="hidden" value="<%=usuario%>" readonly="">
                                    <br>
                                    <input name="xCveAnio" id="xCveAnio" type="hidden" value="<%=anio%>" readonly="">
                                    <input name="xCveTrimestre" id="xCveTrimestre" type="hidden" value="<%=trimestre%>" readonly="">
                                    <input name="xCveDocumento" id="xCveDocumento" type="hidden" value="0" readonly="">

                                    <input name="xCveUsuario2" id="xCveUsuario2" type="hidden" value="<%=usuario%>" readonly="">
                                    <input name="xFechaActualizacionR" id="xFechaActualizacionR" type="hidden" value="" readonly="">

                                    <div class="form-group">
                                        <label for="cmbArticulo">Artículo:</label>
                                        <select id="xCveArticulo" name="xCveArticulo" class="form-control">
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                            <%                                                Resultados rs = new Resultados();
                                                StringBuilder sql = new StringBuilder("SELECT CVE_ARTICULO IDART,DESCRIPCION,NOMBRE,ACTIVO FROM ARTICULOS WHERE ACTIVO = 1  ");
                                                if (area != 1) {
                                                    sql.append("AND CVE_ARTICULO IN (SELECT cve_articulo FROM permisos WHERE activo = 1 AND cve_area = ").append(area).append(" GROUP BY cve_articulo) ");
                                                }
                                                sql.append("ORDER BY cve_articulo");
                                                rs = UtilDB.ejecutaConsulta(sql.toString());
                                                if (rs.recordCount() != 0) {
                                                    while (rs.next()) {
                                            %>
                                            <option value="<%=(rs.getInt("IDART"))%>"><%=(rs.getString("NOMBRE"))%></option>                           
                                            <%
                                                }
                                            } else {
                                            %>
                                            <option value="0" selected>No existen artículos.</option>
                                            <%
                                                }
                                                rs.close();
                                                rs = null;
                                                sql = new StringBuilder();
                                            %>                                        
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

        <jsp:include page="include-footer.jsp" />
        <script src="${pageContext.request.contextPath}/js/jquery-3.2.1.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/bootstrap.min.js"></script>        
        <script src="${pageContext.request.contextPath}/js/bootstrap-datepicker.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/bootstrap-datepicker.es.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/jquery.dataTables.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/dataTables.bootstrap.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/infoITAIP.min.js"></script>
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

                                                                        $.post("acciones.jsp", {cveArt: elegido, xAccion: "cargaComboFracciones"}, function (data) {
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

                                                                        $.post("acciones.jsp", {cveArt: cveArt, cveFrac: elegido, xAccion: "cargaComboIncisos"}, function (data) {
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

                                                                        $.post("acciones.jsp", {cveArt: cveArt, cveFrac: cveFrac, cveInc: cveInc, xAccion: "cargaComboApartado"}, function (data) {
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
                                                $.ajax(
                                                        {
                                                            url: "acciones.jsp?xAccion=grabaTransparencia&xPagina=1", type: "POST", data: datos, success: function (result)
                                                            {
                                                                var n = result.trim();
                                                                var no = n.split('|');
                                                                $("#alertaDocumento").html("<span class='custom entrar'>" + no[0] + "</span>");
                                                                $("#xCveDocumento").val(no[1]);
                                                                muestraDoctos();
                                                                setTimeout(function () {
                                                                    limpiar()();
                                                                }, 2000);
                                                            }
                                                        }
                                                );
                                            }

                                            function muestraDoctos() {
                                                $.post("acciones.jsp", {xAccion: 'muestraDoctos', xPagina: 1}, function (data) {
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

                                                    $.post("acciones.jsp", {cveArt: data.xCveArticulo, cveFrac: data.xCveFraccion, xAccion: "cargaComboFracciones"}, function (data) {
                                                        $("#xCveFraccion").html(data);
                                                        $("#xCveFraccion").attr("disabled", false)
                                                    });

                                                    $("#xCveInciso").attr("disabled", false)
                                                    $("#xCveInciso").val(data.xCveInciso);
                                                    $("#xCveIncisoV").val(data.xCveInciso);

                                                    $.post("acciones.jsp", {cveArt: data.xCveArticulo, cveFrac: data.xCveFraccion, cveInc: data.xCveInciso, xAccion: "cargaComboIncisos"}, function (data) {
                                                        $("#xCveInciso").html(data);
                                                        $("#xCveInciso").attr("disabled", false)
                                                    });

                                                    $("#xCveApartado").attr("disabled", false)
                                                    $("#xCveApartado").val(data.xCveApartado);
                                                    $("#xCveApartadoV").val(data.xCveApartado);
                                                    $("#xCveUsuario").val(data.xCveUsuario);

                                                    $.post("acciones.jsp", {cveArt: data.xCveArticulo, cveFrac: data.xCveFraccion, cveInc: data.xCveInciso, cveApa: data.xCveApartado, xAccion: "cargaComboApartado"}, function (data) {
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
                                                $('#mMiModal').modal('show').find('.modal-content').load("frm-subir-archivo.jsp", {"xOrigen": "anexos", xcveDoc: cveDoc, nombre: nombre, xPagina: 1});
                                            }

        </script>
    </body>
</html>