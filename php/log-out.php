<%-- 
    Document   : log-out
    Created on : 23-mar-2017, 11:14:56
    Author     : Roberto Eder Weiss Juárez
--%>
<%@page import="mx.edu.uttab.transparencia.comun.Sesiones"%>
<%@page contentType="text/html" pageEncoding="UTF-8" %>
<%
    HttpSession httpSession = request.getSession(false);

    if (request.getParameter("logout") != null) {
        response.setContentType("text/html");
        response.setHeader("Cache-Control", "no-cache");
        response.setHeader("Cache-Control", "no-store");
        response.setDateHeader("Expires", 0);
        response.setHeader("Pragma", "no-cache");
        httpSession.removeAttribute(Sesiones.USUARIO);
        httpSession.removeAttribute(Sesiones.NOMBRE);
        httpSession.removeAttribute(Sesiones.ANIO);
        httpSession.removeAttribute(Sesiones.TRIMESTRE);
        httpSession.removeAttribute(Sesiones.URL_PUBLICA);
        httpSession.invalidate();
        httpSession = null;

        response.sendRedirect("../index.jsp");
        return;
    }
%>