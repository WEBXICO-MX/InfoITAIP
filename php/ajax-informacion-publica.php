<%-- 
    Document   : ajax-informacion-publica
    Created on : 28-mar-2017, 11:27:27
    Author     : Roberto Eder Weiss Juárez
--%>

<%@page import="mx.edu.uttab.transparencia.comun.Utilerias"%>
<%@page import="mx.edu.uttab.transparencia.comun.UtilDB"%>
<%@page import="mx.edu.uttab.transparencia.comun.Resultados"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%
    int articulo = 0;
    int fraccion = 0;
    int inciso = 0;
    int apartado = 0;
    int anio = 0;
    int trimestre = 0;
    String page_context = "";
    StringBuilder sql = new StringBuilder();
    StringBuilder html = new StringBuilder();
    Resultados rst = null;

    if (request.getParameter("xAccion") != null) {
        if (request.getParameter("xAccion").equals("getDocumentos")) {
            page_context = request.getParameter("xPageContext");
            articulo = Integer.parseInt(request.getParameter("xArticulo"));
            fraccion = Integer.parseInt(request.getParameter("xFraccion"));
            inciso = Integer.parseInt(request.getParameter("xInciso"));
            apartado = Integer.parseInt(request.getParameter("xApartado"));
            anio = Integer.parseInt(request.getParameter("xAnio"));
            trimestre = Integer.parseInt(request.getParameter("xTrimestre"));

            sql.append("SELECT * FROM documentos WHERE");
            sql.append(" cve_articulo ").append(articulo == 0 ? "IS NULL" : "= " + articulo);
            sql.append(" AND cve_fraccion ").append(fraccion == 0 ? "IS NULL" : "= " + fraccion);
            sql.append(" AND cve_inciso ").append(inciso == 0 ? "IS NULL" : "= " + inciso);
            sql.append(" AND cve_apartado ").append(apartado == 0 ? "IS NULL" : "= " + apartado);
            sql.append(" AND anio =").append(anio);
            sql.append(" AND trimestre =").append(trimestre);
            sql.append(" AND anexo = 1");
            sql.append(" AND activo = 1");

            rst = UtilDB.ejecutaConsulta(sql.toString());

            if (rst.recordCount() != 0) {
                html.append("<ul style=\"list-style-image: url(../img/liston.png); list-style-position: inside;\" >");
                while (rst.next()) {
                    html.append("<li>");
                    html.append("<a href=\"").append(page_context + "/"+rst.getString("ruta_documento")).append("\" target=\"_blank\">").append(rst.getString("nombre")).append("</a> ").append("(fecha de actualización: ").append(Utilerias.getCadenaFechaLarga(rst.getCalendar("fecha_actualizacion_documento"))).append(")");
                    html.append("</li>");
                }
                html.append("</ul>");
            } else {
                html.append("<p class=\"text-center\">No se han cargado documentos por el momento</p>");
            }
            rst.close();

            out.println(html.toString());
            return;

        }
    }
%>