<%-- 
    Document   : usuarios
    Created on : 28-mar-2017, 13:58:45
    Author     : Roberto Eder Weiss Juárez
--%>
<%@page import="mx.edu.uttab.transparencia.comun.ErrorSistema"%>
<%@page import="mx.edu.uttab.transparencia.base.Usuarios"%>
<%@page import="mx.edu.uttab.transparencia.comun.UtilDB"%>
<%@page import="mx.edu.uttab.transparencia.comun.Resultados"%>
<%@page import="mx.edu.uttab.transparencia.comun.Sesiones"%>
<%@page contentType="text/html" pageEncoding="UTF-8" %>
<%  HttpSession httpSession = request.getSession(false);
    int area = httpSession.getAttribute(Sesiones.AREA) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.AREA).toString()) : 0;

    if (httpSession.getAttribute(Sesiones.USUARIO) == null || area != 1) {
        response.sendRedirect("../index.jsp");
        return;
    }

    String sql = "";
    Resultados rst = null;
    boolean grabo = false;
    int count = 0;
    String accion = request.getParameter("xAccion") != null ? request.getParameter("xAccion") : "";
    Usuarios usuario = new Usuarios(request.getParameter("xCveUsuario") != null ? (Integer.parseInt(request.getParameter("xCveUsuario"))) : 0);

    if (accion.equals("grabar")) {
        usuario.setCveArea(Integer.parseInt(request.getParameter("cmbArea")));
        usuario.setNombre(request.getParameter("txtNombre"));
        if (usuario.getCveUsuario() == 0) {
            usuario.setLogin(request.getParameter("txtLogin"));
            usuario.setPass(request.getParameter("txtPassword"));
        }
        usuario.setActivo(request.getParameter("cbxActivo") != null ? true : false);

        ErrorSistema err = usuario.grabar();
        if (err.getNumeroError() == 0) {
            grabo = true;
        }

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
        <link href="${pageContext.request.contextPath}/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
        <link href="${pageContext.request.contextPath}/css/infoITAIP.min.css" rel="stylesheet"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <body>
        <div class="container-fluid">
            <jsp:include page="include-header.jsp">
                <jsp:param name="o" value="usuario" />
            </jsp:include>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Usuario(s)</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="frmUsuario" id="frmUsuario" method="post">
                                    <div class="form-group">
                                        <input type="hidden" name="xCveUsuario" id="xCveUsuario" value="<%= usuario.getCveUsuario()%>" />
                                        <input type="hidden" name="xAccion" id="xAccion" value="0" />
                                        <label for="cmbArea">Área:</label>
                                        <select name="cmbArea" id="cmbArea" class="form-control">                                            
                                            <option value="0">---------- SELECCIONE UNA OPCIÓN -----------</option>
                                            <% sql = "SELECT * FROM areas WHERE activo = 1 ORDER BY descripcion";
                                                rst = UtilDB.ejecutaConsulta(sql);
                                                while (rst.next()) {
                                                    out.println("<option value=\"" + rst.getInt("cve_area") + "\" " + (usuario.getCveArea() == rst.getInt("cve_area") ? "selected" : "") + ">" + rst.getString("descripcion") + "</option>");
                                                }
                                                rst.close();
                                            %>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="txtNombre">Nombre completo:</label>
                                        <input name="txtNombre" id="txtNombre" type="text" class="form-control" value="<%= usuario.getNombre()%>" maxlength="100">
                                    </div>
                                    <% if (usuario.getCveArea() == 0) {%>
                                    <div class="form-group">
                                        <label for="txtLogin">Usuario:</label>
                                        <input name="txtLogin" id="txtLogin" type="text" class="form-control" value="<%= usuario.getLogin()%>" maxlength="30">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtPassword">Contraseña:</label>
                                        <input name="txtPassword" id="txtPassword" type="password" class="form-control" value="" maxlength="10">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtPassword2">Ingrese nuevamente la contraseña:</label>
                                        <input name="txtPassword2" id="txtPassword2" type="password" class="form-control" value="" maxlength="10">
                                    </div>
                                    <%}%>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="cbxActivo" id="cbxActivo" value="1"  <%= usuario.getCveUsuario() != 0 ? (usuario.isActivo() ? "checked" : "") : "checked"%>/>
                                            Activo
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-md btn-success btn-block" value="Guardar" onclick="grabar()"/>
                                        <input class="btn btn-md btn-primary btn-block" value="Limpiar" onclick="limpiar()"/>                                        
                                    </div>
                                </form>
                                <div class="alert alert-success" id="alert-ok" style="display:none;">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    <strong>Éxito!</strong> Usuario guardado
                                </div>
                                <div class="alert alert-danger" id="alert-ko" style="display:none;">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    <strong>Error!</strong> Usuario no guardado
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
                            <h5 class="text-center text-uppercase">Usuario(s)</h5>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <table id="tabla_usuarios" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre completo</th>
                                            <th>Área</th>
                                            <th>Activo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <% sql = "SELECT u.cve_usuario,u.nombre_completo, a.descripcion AS area, u.activo FROM usuarios AS u INNER JOIN areas AS a ON a.cve_area = u.cve_area ORDER BY nombre_completo";
                                            rst = UtilDB.ejecutaConsulta(sql);
                                            while (rst.next()) {
                                                out.println("<tr>");
                                                out.println("<td>" + (++count) + "</td>");
                                                out.println("<td><a href=\"javascript:void(0);\" onclick=\"cargar(" + rst.getInt("cve_usuario") + ");\">" + rst.getString("nombre_completo") + "</a></td>");
                                                out.println("<td>" + rst.getString("area") + "</td>");
                                                out.println("<td>" + (rst.getBoolean("activo") ? "<span class=\"glyphicon glyphicon-ok-circle\"></span>" : "<span class=\"glyphicon glyphicon-remove-circle\"></span>") + "</td>");
                                                out.println("</tr>");
                                            }
                                            rst.close();
                                            count = 0;
                                        %>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>    
        </div>
        <jsp:include page="include-footer.jsp" />
        <script src="${pageContext.request.contextPath}/js/jquery-3.2.1.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/bootstrap.min.js"></script>        
        <script src="${pageContext.request.contextPath}/js/jquery.dataTables.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/dataTables.bootstrap.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/infoITAIP.min.js"></script>
        <script>
                                            $(document).ready(function () {

                                                $('#tabla_usuarios').DataTable();

                                                <% if (grabo) {%>
                                                $("#alert-ok").alert();
                                                $("#alert-ok").fadeTo(2000, 700).slideUp(700, function () {
                                                    $("#alert-ok").slideUp(700);
                                                });
                                                <%}else if(!grabo && accion.equals("grabar")){%>
                                                $("#alert-ko").alert();
                                                $("#alert-ko").fadeTo(2000, 700).slideUp(700, function () {
                                                    $("#alert-ko").slideUp(700);
                                                });   
                                                <%}%>    

                                            });

                                            function limpiar()
                                            {
                                                $("#xCveUsuario").val("0");
                                                $("#xAccion").val("0");
                                                $("#cmbArea").val("0");
                                                $("#txtNombre").val("");
                                                $("#txtLogin").val("");
                                                $("#txtPassword").val("");
                                                $("#txtPassword2").val("");
                                                $("#frmUsuario").submit();
                                            }

                                            function cargar(id)
                                            {
                                                $("#xCveUsuario").val(id);
                                                $("#frmUsuario").submit();

                                            }

                                            function grabar()
                                            {
                                                if (valido()) {
                                                    $("#xAccion").val("grabar");
                                                    $("#frmUsuario").submit();
                                                }


                                            }

                                            function valido()
                                            {
                                                var valido = true;
                                                var msg = "";
                                                if ($("#cmbArea").val() === "0")
                                                {
                                                    msg += "Seleccione un área. \n";
                                                    valido = false;
                                                }
                                                if ($("#txtNombre").val() === "")
                                                {
                                                    msg += "Ingrese su nombre completo. \n";
                                                    valido = false;
                                                }
                                                if ($("#txtLogin").val() === "")
                                                {
                                                    msg += "Ingrese su usuario. \n";
                                                    valido = false;
                                                }
                                                if ($("#txtPassword").val() === "")
                                                {
                                                    msg += "Ingrese su contraseña. \n";
                                                    valido = false;
                                                }
                                                if ($("#txtPassword2").val() === "")
                                                {
                                                    msg += "Ingrese nuevamente la contraseña. \n";
                                                    valido = false;
                                                }

                                                if ($("#txtPassword").val() !== "" && $("#txtPassword2").val() !== "")
                                                {
                                                    if ($("#txtPassword2").val() !== $("#txtPassword").val())
                                                    {
                                                        $("#txtPassword2").focus();
                                                        msg += "Las contraseñas no coinciden. \n";
                                                        valido = false;
                                                    }
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