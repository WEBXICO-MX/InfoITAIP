<%-- 
    Document   : articulos
    Created on : 23/03/2017, 12:44:04 PM
    Author     : Viruliento
--%>

<%@page import="mx.edu.uttab.transparencia.comun.Sesiones"%>
<%@page contentType="text/html" pageEncoding="UTF-8" %>
<%  HttpSession httpSession = request.getSession(false);

    int area = httpSession.getAttribute(Sesiones.AREA) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.AREA).toString()) : 0;

    if (httpSession.getAttribute(Sesiones.USUARIO) == null || area != 1) {
        response.sendRedirect("../index.jsp");
        return;
    }

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
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <body>
        <div class="container-fluid">

            <jsp:include page="include-header.jsp">
                <jsp:param name="o" value="articulo" />
            </jsp:include>

            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>

            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Artículos</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="formArticulos" id="formArticulos" method="post">
                                    <input name="xCveArticulo" id="xCveArticulo" type="hidden" class="form-control" value="0" readonly="">
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
                                    <div class="form-group">                                        
                                        <input class="btn btn-block btn-success" value="Guardar" readonly="" onclick="grabar()"/>
                                        <input class="btn btn-block btn-primary" value="Limpiar" readonly="" onclick="limpiar()"/>
                                    </div>

                                    <div style="width:100%" id="alertaArticulos" align="center">
                                        <div style="clear:both;"></div>
                                    </div> 

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="muestra" style="width:100%">
                <div id="muestraArtirulos">

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

                                            $(document).ready(function () {

                                                $('.input-group.date').datepicker({
                                                    format: 'dd/mm/yyyy',
                                                    language: 'es',
                                                    todayHighlight: true
                                                });
                                                limpiar();

                                            });

                                            function limpiar()
                                            {
                                                $('#xCveArticulo').val(0);
                                                $('#xNombre').val('');
                                                $('#xDescripcion').val('');
                                                $("#xActivo").prop("checked", "checked");
                                                $('#xActivoVal').val(1);

                                                $('#xNombre').focus();

                                                $('#alertaArticulos').html('');
                                                muestraArticulos();
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
                                                if ($("#xNombre").val() == "") {
                                                    $("#alertaArticulos").html("<span class='custom critical'>Debe capturar algo en el campo nombre.</span>");
                                                    $("#xNombre").focus();
                                                    return
                                                }
                                                if ($("#xDescripcion").val() == "") {
                                                    $("#alertaArticulos").html("<span class='custom critical'>Debe capturar algo en el campo descripción.</span>");
                                                    $("#xDescripcion").focus();
                                                    return
                                                }

                                                var datos = $("#formArticulos").serialize();
                                                $.ajax(
                                                        {
                                                            url: "acciones.jsp?xAccion=grabaArticulo", type: "POST", data: datos, success: function (result)
                                                            {
                                                                var n = result.trim();
                                                                var no = n.split('|');
                                                                $("#alertaArticulos").html("<span class='custom entrar'>" + no[0] + "</span>");
                                                                $("#xCveArticulo").val(no[1]);
                                                                muestraArticulos();
                                                                setTimeout(function () {
                                                                    limpiar()();
                                                                }, 2000);
                                                            }
                                                        }
                                                );
                                            }

                                            function muestraArticulos() {
                                                $.post("acciones.jsp", {xAccion: 'muestraArticulos'}, function (data) {
                                                    $("#muestraArtirulos").html(data);
                                                });
                                            }

                                            function recargaArticulo(url, cve)
                                            {
                                                $.post(url, {cve: cve}, function (data) {
                                                    $("#alertaArticulos").html('');
                                                    $("#xCveArticulo").val(data.xCveArticulo);
                                                    $("#xDescripcion").val(data.xDescripcion);
                                                    $("#xNombre").val(data.xNombre);
                                                    $("#xDescripcion").select();
                                                    
                                                    if(data.xActivo == true){
                                                        $("#xActivo").prop("checked", "checked");
                                                        $('#xActivoVal').val(1);
                                                    }else{
                                                        $("#xActivo").prop("checked", "");
                                                        $('#xActivoVal').val(0);
                                                    }
                                                }, "json");
                                            }

        </script>
    </body>
</html>