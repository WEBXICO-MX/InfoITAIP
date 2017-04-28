<%-- 
    Document   : usuario-contrasena
    Created on : 23-mar-2017, 10:23:45
    Author     : Roberto Eder Weiss Juárez
--%>
<%@page import="mx.edu.uttab.transparencia.comun.Sesiones"%>
<%@page contentType="text/html" pageEncoding="UTF-8" %>
<%  HttpSession httpSession = request.getSession(false);

    if (httpSession.getAttribute(Sesiones.USUARIO) == null) {
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
        <link href="${pageContext.request.contextPath}/css/login.min.css" rel="stylesheet"/>
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
                <jsp:param name="o" value="usuario-contrasena" />
            </jsp:include>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Cambiar contraseña</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <form action="" name="frmUsuarioContrasena" id="frmUsuarioContrasena" method="post">
                                    <div class="form-group">
                                        <label for="txtContrasenaActual">Ingresar contraseña actual:</label>
                                        <input name="txtContrasenaActual" id="txtContrasenaActual" type="password" class="form-control" value="" maxlength="10">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtContrasenaNueva">Ingresar contraseña nueva:</label>
                                        <input name="txtContrasenaNueva" id="txtContrasenaNueva" type="password" class="form-control" value="" maxlength="10">
                                    </div>
                                    <div class="form-group">
                                        <label for="txtContrasenaNueva2">Ingresar nuevamente contraseña:</label>
                                        <input name="txtContrasenaNueva2" id="txtContrasenaNueva2" type="password" class="form-control" value="" maxlength="10">
                                    </div>
                                    <div class="form-group">
                                        <input class="btn btn-md btn-success btn-block" value="Guardar" onclick="cambiar()"/>
                                        <input class="btn btn-md btn-primary btn-block" value="Limpiar" onclick="limpiar()"/>
                                    </div>
                                </form>
                                <span id="msgbox" style="display:none"> </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <jsp:include page="include-footer.jsp" />
        <script src="${pageContext.request.contextPath}/js/jquery-3.2.1.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/bootstrap.min.js"></script>        
        <script src="${pageContext.request.contextPath}/js/cambiar-contrasena.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/infoITAIP.min.js"></script>
    </body>
</html>