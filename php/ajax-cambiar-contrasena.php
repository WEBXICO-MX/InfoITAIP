<%-- 
    Document   : ajax-cambiar-contrasena
    Created on : 23-mar-2017, 13:06:59
    Author     : Roberto Eder Weiss Juárez
--%>

<%@page import="mx.edu.uttab.transparencia.base.HistorialCambioContrasena"%>
<%@page import="mx.edu.uttab.transparencia.comun.ErrorSistema"%>
<%@page import="mx.edu.uttab.transparencia.comun.Sesiones"%>
<%@page import="mx.edu.uttab.transparencia.base.Usuarios"%>
<%@page import="org.json.simple.JSONObject"%>
<%@page contentType="application/json" pageEncoding="UTF-8" %>
<%
    HttpSession httpSession = request.getSession(false);

    Usuarios usr = new Usuarios(Integer.parseInt(httpSession.getAttribute(Sesiones.USUARIO).toString()));
    HistorialCambioContrasena hcc = new HistorialCambioContrasena();
    
    JSONObject json = new JSONObject();
    if (usr.getPass().equals(usr.encriptarContrasena(request.getParameter("xContrasenaActual")))) {

        usr.setPass(request.getParameter("xContrasenaNueva"));
        ErrorSistema err = usr.grabar();
        if (err.getNumeroError() != 0) {
            json.put("valido", new Boolean("false"));
            json.put("msg", new String("La contraseña no se pudo modificar"));
        } else {
            json.put("valido", new Boolean("true"));
            json.put("msg", new String("Contraseña actualizada con  exito"));
            hcc.setCveUsuario(usr);
            hcc.setContrasenaNueva(usr.encriptarContrasena(request.getParameter("xContrasenaNueva")));
            hcc.setContrasenaAnterior(usr.encriptarContrasena(request.getParameter("xContrasenaActual")));
            hcc.grabar();
        }

        out.print(json);
        out.flush();

    } else {
        json.put("valido", new Boolean("false"));
        json.put("msg", new String("La contraseña no es correcta"));
        out.print(json);
        out.flush();
    }

%>