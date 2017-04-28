<%-- 
    Document   : informacion-publica
    Created on : 23-mar-2017, 14:53:57
    Author     : Roberto Eder Weiss Juárez
--%>

<%@page import="java.util.Properties"%>
<%@page import="java.util.Calendar"%>
<%@page import="mx.edu.uttab.transparencia.comun.Resultados"%>
<%@page import="mx.edu.uttab.transparencia.comun.UtilDB"%>
<%@page contentType="text/html" pageEncoding="UTF-8" session="false"%>
<%
    String sql = "";
    Resultados rst = null;
    Resultados rst2 = null;
    Resultados rst3 = null;
    Resultados rst4 = null;
    StringBuilder html = new StringBuilder();
    StringBuilder html2 = new StringBuilder();
    int count = 0;
    int count2 = 0;
    int anio_actual = Calendar.getInstance().get(Calendar.YEAR);
    int mes_actual = Calendar.getInstance().get(Calendar.MONTH);
    int trimestre_actual = getTrimestreActual(mes_actual);
    int[] anios = {2015, 2016, 2017};
    String[] trimestres = {"1er. Trimestre (enero-marzo)", "2do. Trimestre (abril-junio)", "3er. Trimestre (julio-septiembre)", "4to. Trimestre (octubre-diciembre)"};
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
        <link href="${pageContext.request.contextPath}/css/infoITAIP.min.css" rel="stylesheet"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            body { 
                background: url(${pageContext.request.contextPath}/img/fondo-pantalla.jpg) no-repeat  center 130px;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size:cover;
                font-family: roboto;    /* Margin bottom by footer height */
                margin-bottom: 301.5px;
            }
        </style>
    <body>
        <div class="container-fluid">
            <jsp:include page="include-header.jsp">
                <jsp:param name="o" value="informacion-publica" />
            </jsp:include>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="text-center text-uppercase">Información pública</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <%  sql = "SELECT * FROM articulos WHERE activo = 1 ORDER BY cve_articulo ASC";
                                    rst = UtilDB.ejecutaConsulta(sql);

                                    if (rst.recordCount() != 0) {
                                        html.append("<ul class=\"nav nav-tabs\">");
                                        html2.append("<div class=\"tab-content\">");
                                        while (rst.next()) {
                                            html.append("<li ").append(count == 0 ? "class=\"active\"" : "").append("><a data-toggle=\"tab\" href=\"#articulo").append(rst.getInt("cve_articulo")).append("\"><span class=\"glyphicon glyphicon-tasks\"></span> ").append(rst.getString("nombre")).append("</a></li>");
                                            
                                            html2.append("<div id=\"articulo").append(rst.getInt("cve_articulo")).append("\" class=\"tab-pane fade ").append(count == 0 ? "in active" : "").append("\">");
                                            html2.append("<p>").append(rst.getString("descripcion")).append("</p>");
                                            sql = "SELECT * FROM fracciones WHERE activo = 1 AND cve_articulo = " + rst.getInt("cve_articulo") + " ORDER BY cve_fraccion ASC";
                                            rst2 = UtilDB.ejecutaConsulta(sql);

                                            if (rst2.recordCount() != 0) {
                                                html2.append("<div class=\"panel-group\" id=\"accordion-articulo-").append(rst.getInt("cve_articulo")).append("\">");
                                                while (rst2.next()) {
                                                    html2.append("<div class=\"panel panel-default\">");
                                                    html2.append("<div class=\"panel-heading\">");
                                                    html2.append("<h4 class=\"panel-title\">");
                                                    html2.append("<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion-articulo-").append(rst.getInt("cve_articulo")).append("\" href=\"#collapse-fraccion-").append(rst2.getInt("cve_fraccion")).append("\">");
                                                    html2.append("<span class=\"glyphicon glyphicon-bookmark\"></span> ").append(rst2.getString("nombre"));
                                                    html2.append("</a>");
                                                    html2.append("</h4>");
                                                    html2.append("</div>");
                                                    html2.append("<div id=\"collapse-fraccion-").append(rst2.getInt("cve_fraccion")).append("\" class=\"panel-collapse collapse ").append(count2 == 0 ? "in" : "").append("\">");
                                                    //html2.append("<div id=\"collapse-fraccion-").append(rst2.getInt("cve_fraccion")).append("\" class=\"panel-collapse collapse \">");
                                                    html2.append("<div class=\"panel-body\">");
                                                    html2.append("<p>").append(rst2.getString("descripcion")).append("</p><br/><br/>");

                                                    sql = "SELECT * FROM incisos WHERE cve_articulo = " + rst.getInt("cve_articulo") + " AND cve_fraccion = " + rst2.getInt("cve_fraccion") + " AND activo = 1 ORDER BY cve_inciso ASC";
                                                    rst3 = UtilDB.ejecutaConsulta(sql);

                                                    if (rst3.recordCount() != 0) {
                                                        html2.append("<div class=\"panel-group\" id=\"accordion-incisos-").append(rst2.getInt("cve_fraccion")).append("\">");
                                                        while (rst3.next()) {
                                                            html2.append("<div class=\"panel panel-success\">");
                                                            html2.append("<div class=\"panel-heading\">");
                                                            html2.append("<h4 class=\"panel-title\">");
                                                            html2.append("<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion-incisos-").append(rst2.getInt("cve_fraccion")).append("\" href=\"#collapse-inciso-").append(rst3.getInt("cve_inciso")).append("\">");
                                                            html2.append("<span class=\"glyphicon glyphicon-asterisk\"></span> ").append(rst3.getString("descripcion"));
                                                            html2.append("</a>");
                                                            html2.append("</h4>");
                                                            html2.append("</div>");
                                                            html2.append("<div id=\"collapse-inciso-").append(rst3.getInt("cve_inciso")).append("\" class=\"panel-collapse collapse \">");
                                                            html2.append("<div class=\"panel-body\">");

                                                            sql = "SELECT * FROM apartados WHERE cve_articulo = " + rst.getInt("cve_articulo") + " AND cve_fraccion = " + rst2.getInt("cve_fraccion") + " AND cve_inciso = " + rst3.getInt("cve_inciso") + " AND activo = 1 ORDER BY cve_apartado ASC";
                                                            rst4 = UtilDB.ejecutaConsulta(sql);

                                                            if (rst4.recordCount() != 0) {
                                                                html2.append(" <div class=\"panel-group\" id=\"accordion-apartados-").append(rst3.getInt("cve_inciso")).append("\">");
                                                                while (rst4.next()) {
                                                                    html2.append("<div class=\"panel panel-info\">");
                                                                    html2.append("<div class=\"panel-heading\">");
                                                                    html2.append("<h4 class=\"panel-title\">");
                                                                    html2.append("<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#accordion-apartados-").append(rst3.getInt("cve_inciso")).append("\" href=\"#collapse-apartado-").append(rst4.getInt("cve_apartado")).append("\">");
                                                                    html2.append("<span class=\"glyphicon glyphicon-th-list\"></span> ").append(rst4.getString("descripcion"));
                                                                    html2.append("</a>");
                                                                    html2.append("</h4>");
                                                                    html2.append("</div>");
                                                                    html2.append("<div id=\"collapse-apartado-").append(rst4.getInt("cve_apartado")).append("\" class=\"panel-collapse collapse\">");
                                                                    html2.append("<div class=\"panel-body\">");
                                                                    html2.append(getAniosTrimestres(rst.getInt("cve_articulo"), rst2.getInt("cve_fraccion"), rst3.getInt("cve_inciso"), rst4.getInt("cve_apartado"), "panel-default", anio_actual, mes_actual, trimestre_actual, anios, trimestres));
                                                                    html2.append("</div>");
                                                                    html2.append("</div>");
                                                                    html2.append("</div>");
                                                                }
                                                                html2.append("</div>");
                                                            } else {
                                                                html2.append(getAniosTrimestres(rst.getInt("cve_articulo"), rst2.getInt("cve_fraccion"), rst3.getInt("cve_inciso"), 0, "panel-info", anio_actual, mes_actual, trimestre_actual, anios, trimestres));
                                                            }
                                                            rst4.close();

                                                            html2.append("</div>");
                                                            html2.append("</div>");
                                                            html2.append("</div>");

                                                        }
                                                        html2.append("</div>");

                                                    } else {
                                                        html2.append(getAniosTrimestres(rst.getInt("cve_articulo"), rst2.getInt("cve_fraccion"), 0, 0, "panel-success", anio_actual, mes_actual, trimestre_actual, anios, trimestres));
                                                    }

                                                    rst3.close();

                                                    html2.append("</div>");
                                                    html2.append("</div>");
                                                    html2.append("</div>");
                                                    count2++;
                                                }
                                                html2.append("</div>");
                                                count2 = 0;

                                            }

                                            rst2.close();

                                            html2.append("</div>");
                                            count++;
                                        }
                                        html.append("</div>");
                                        html.append("</ul>");
                                        html.append(html2);
                                        count = 0;

                                        out.println(html.toString());

                                        rst.close();
                                    } else {
                                        html.append("<h3 class=\"text-center text-uppercase\">No hay datos para mostrar por el momento</h3>");
                                    }

                                %>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <jsp:include page="include-footer.jsp" />
        <script src="${pageContext.request.contextPath}/js/jquery-2.2.4.min.js"></script>
        <script src="${pageContext.request.contextPath}/js/bootstrap.min.js"></script>        
        <script src="${pageContext.request.contextPath}/js/infoITAIP.min.js"></script>
        <script>
            var accordion = "";
            var articulo = 0;
            var fraccion = 0;
            var incisio = 0;
            var apartado = 0;
            var anio = 0;
            var trimestre = 0;

            $(document).ready(function () {
                $("[id*='art-'] .panel-group").each(function () {
                    $('#' + this.id).on('shown.bs.collapse', function (e) {
                        accordion = $(e.target).attr("id");
                        $('#' + accordion + " div.panel-body").html("<p><img src=\"../img/ajax-loading.gif\" alt=\"cargando\"/> cargando ...</p>");
                        articulo = parseInt(accordion.substring(accordion.indexOf("art") + 4, accordion.indexOf("art") + 6));
                        fraccion = parseInt(accordion.substring(accordion.indexOf("frac") + 5, accordion.indexOf("frac") + 7));
                        inciso = parseInt(accordion.substring(accordion.indexOf("inc") + 4, accordion.indexOf("inc") + 6));
                        apartado = parseInt(accordion.substring(accordion.indexOf("apt") + 4, accordion.indexOf("apt") + 6));
                        anio = parseInt(accordion.substring(accordion.indexOf("anio") + 5, accordion.indexOf("anio") + 9));
                        trimestre = parseInt(accordion.substring(accordion.indexOf("trim") + 5, accordion.indexOf("trim") + 7));
                        //console.log(accordion);
                        //console.log("articulo:"+articulo+",fraccion:"+fraccion+",inciso:"+inciso+",apartado:"+apartado+",año:"+anio+",trimestre:"+trimestre);
                        $('#' + accordion + " div.panel-body").load("ajax-informacion-publica.jsp", {"xAccion": "getDocumentos", "xPageContext":"${pageContext.request.contextPath}","xArticulo": articulo, "xFraccion": fraccion, "xInciso": inciso, "xApartado": apartado, "xAnio": anio, "xTrimestre": trimestre});

                    });
                });
            });
        </script>
    </body>
</html>
<%!
    public int getTrimestreActual(int mes) {
        int trimestre = 0;
        if (mes >= 0 && mes < 3) {
            trimestre = 1;
        } else if (mes >= 3 && mes < 6) {
            trimestre = 2;
        } else if (mes >= 6 && mes < 9) {
            trimestre = 3;
        } else {
            trimestre = 4;
        }
        return trimestre;
    }

    public StringBuilder getAniosTrimestres(int art, int frac, int inc, int apt, String panel_style, int anio_actual, int mes_actual, int trimestre_actual, int[] anios, String[] trimestres) {
        StringBuilder html = new StringBuilder();
        StringBuilder html2 = new StringBuilder();
        int count_trimestre = 0;
        String identificador = "art-" + (art < 10 ? "0" + art : art) + "-frac-" + (frac < 10 ? "0" + frac : frac) + "-inc-" + (inc < 10 ? "0" + inc : inc) + "-apt-" + (apt < 10 ? "0" + apt : apt);
        html.append("<ul class=\"nav nav-tabs\">");
        html2.append("<div class=\"tab-content\">");
        for (int anio : anios) {
            html.append("<li ").append(anio == anio_actual ? "class=\"active\"" : "").append("><a data-toggle=\"tab\" href=\"#").append(identificador + "-tabs-anio-" + anio).append("\"><span class=\"glyphicon glyphicon-calendar\"></span> ").append(anio).append("</a></li>");

            html2.append("<div id=\"").append(identificador + "-tabs-anio-" + anio).append("\" class=\"tab-pane fade ").append(anio == anio_actual ? "in active" : "").append("\">");
            html2.append("<br/><br/>");
            html2.append("<div class=\"panel-group\" id=\"").append(identificador + "-accordion-anio-" + anio).append("\">");

            for (String trimestre : trimestres) {
                html2.append("<div class=\"panel ").append(panel_style).append("\">");
                html2.append("<div class=\"panel-heading\">");
                html2.append("<h4 class=\"panel-title\">");
                html2.append("<a class=\"accordion-toggle\" data-toggle=\"collapse\" data-parent=\"#").append(identificador + "-accordion-anio-" + anio).append("\" href=\"#").append(identificador + "-collapse-anio-" + anio + "-trim-0" + (count_trimestre + 1)).append("\">");
                html2.append("<p><span class=\"glyphicon glyphicon-eye-open\"></span> ").append(trimestre).append("</p>");
                html2.append("</a>");
                html2.append("</h4>");
                html2.append("</div>");
                // HABILITAR LA LINEA QUE ESTA COMENTADA DEBAJO SI SE REQUIERE QUE ESTE ABIERTO POR DEFAULT EL TRIMESTRE ACTUAL EN EL AÑO ACTUAL
                //html2.append("<div id=\"").append(identificador + "-collapse-anio-" + anio + "-trim-0" + (count_trimestre + 1)).append("\" class=\"panel-collapse collapse ").append(anio_actual == anio ? (trimestre_actual == count_trimestre + 1 ? "in" : "") : "").append("\">");
                html2.append("<div id=\"").append(identificador + "-collapse-anio-" + anio + "-trim-0" + (count_trimestre + 1)).append("\" class=\"panel-collapse collapse \">");
                html2.append("<div class=\"panel-body\">");

                html2.append("</div>");
                html2.append("</div>");
                html2.append("</div>");
                count_trimestre++;
            }
            count_trimestre = 0;
            html2.append("</div>");
            html2.append("</div>");
        }
        html2.append("</div>");
        html.append("</ul>");
        html.append(html2);

        return html;

    }
%>