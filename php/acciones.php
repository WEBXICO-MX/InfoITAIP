<%-- 
    Document   : acciones
    Created on : 23/03/2017, 01:07:03 PM
    Author     : Viruliento
--%>
<%@page import="java.util.*"%>
<%@page import="mx.edu.uttab.transparencia.base.*"%>
<%@page import="org.json.simple.*"%>
<%@page import="mx.edu.uttab.transparencia.comun.*"%>
<%@page contentType="text/html" pageEncoding="UTF-8" %>

<%
    HttpSession httpSession = request.getSession(false);

    if (httpSession.getAttribute(Sesiones.USUARIO) == null) {
        response.sendRedirect("../index.jsp");
        return;
    }

    JSONObject json = new JSONObject();
    StringBuilder sql = new StringBuilder();
    Resultados rs = new Resultados();
    
    int area = httpSession.getAttribute(Sesiones.AREA) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.AREA).toString()) : 0;
    int anio = httpSession.getAttribute(Sesiones.ANIO) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.ANIO).toString()) : 0;
    int trimestre = httpSession.getAttribute(Sesiones.TRIMESTRE) != null ? Integer.parseInt(httpSession.getAttribute(Sesiones.TRIMESTRE).toString()) : 0;
    String[] trimestres = {"1er trimestre (enero-marzo)", "2do trimestre (abril-junio)", "3er trimestre (julio-septimebre)", "4to trimestre (octubre-diciembre)"};

    if (request.getParameter("xAccion") != null) {
        if (request.getParameter("xAccion").equals("grabaArticulo")) {

            Articulos art = new Articulos();
            int cveArt = 0;
            ErrorSistema err = new ErrorSistema();

            try {
                cveArt = Integer.parseInt(request.getParameter("xCveArticulo"));
            } catch (Exception e) {
                cveArt = 0;
            }
            art = null;
            art = new Articulos(cveArt);

            art.setCveArticulo(Integer.parseInt(request.getParameter("xCveArticulo")));
            art.setNombre(request.getParameter("xNombre"));
            art.setDescripcion(request.getParameter("xDescripcion"));
            art.setActivo(request.getParameter("xActivoVal").equals("1") ? true : false);
            err = art.grabar();

            if (err.getNumeroError() != 0) {
                System.out.println("GrabÃ³, error no. " + err.getCadenaError() + "\n" + err.getCadenaSQL());
            } else {
                out.println(art.getMsg());
            }

        } else if (request.getParameter("xAccion").equals("muestraArticulos")) {

            sql = new StringBuilder();
            rs = new Resultados();
            int x = 0;

            sql.append("SELECT CVE_ARTICULO IDART,NOMBRE,DESCRIPCION,ACTIVO FROM ARTICULOS ORDER BY DESCRIPCION");
            rs = UtilDB.ejecutaConsulta(sql.toString());
%>

<script>

    $(document).ready(function () {
        $('#tablaArticulos').DataTable();
    });

</script>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5 class="text-center text-uppercase">Artículos</h5>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <table id="tablaArticulos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Activo</th>                                            
                            </tr>
                        </thead>
                        <!--<tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Nombre</th>
                                <th>Activo</th>      
                            </tr>
                        </tfoot>-->
                        <tbody>
                            <%
                                while (rs.next()) {
                                    x++;
                            %>
                            <tr>
                                <td style="text-align: center"><%=x%></td>
                                <td>
                                    <a href="javascript:recargaArticulo('acciones.jsp?xAccion=recargaArticulo','<%=rs.getInt("IDART")%>');" class="liga"><%=rs.getString("NOMBRE")%></a>
                                </td>
                                <td style="text-align: justify">
                                    <%=rs.getString("DESCRIPCION")%>
                                </td>
                                <td style="text-align: center">
                                    <img src="<%=rs.getBoolean("ACTIVO") ? "../img/checkmark.png" : "../img/cerrar.png"%>" width="16" height="16" title="<%=rs.getBoolean("ACTIVO") ? "Activo" : "Inactivo"%>" border="0">
                                </td>
                            </tr>
                            <%
                                }
                                rs.close();
                                rs = null;
                                sql = new StringBuilder();
                            %>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>                
</div>

<%
} else if (request.getParameter("xAccion").equals("recargaArticulo")) {

    int cve = 0;

    if (request.getParameter("cve") != null) {
        cve = Integer.parseInt(request.getParameter("cve"));
    }

    Articulos artR = new Articulos();
    artR = new Articulos(cve);

    json = new JSONObject();
    json.put("xCveArticulo", new Integer(artR.getCveArticulo()));
    json.put("xDescripcion", new String(artR.getDescripcion()));
    json.put("xNombre", new String(artR.getNombre()));
    json.put("xActivo", new Boolean(artR.isActivo()));

    out.print(json);
    out.flush();
    return;

} else if (request.getParameter("xAccion").equals("grabaFracciones")) {

    Fracciones frac = new Fracciones();
    int cveFrac = 0;
    ErrorSistema err = new ErrorSistema();

    try {
        cveFrac = Integer.parseInt(request.getParameter("xCveFraccion"));
    } catch (Exception e) {
        cveFrac = 0;
    }
    frac = null;
    frac = new Fracciones(cveFrac);

    frac.setCveArticulo(Integer.parseInt(request.getParameter("xCveArticuloV")));
    frac.setCveFraccion(Integer.parseInt(request.getParameter("xCveFraccion")));
    frac.setNombre(request.getParameter("xNombre"));
    frac.setDescripcion(request.getParameter("xDescripcion"));
    frac.setActivo(request.getParameter("xActivoVal").equals("1") ? true : false);
    err = frac.grabar();

    if (err.getNumeroError() != 0) {
        System.out.println("GrabÃ³, error no. " + err.getCadenaError() + "\n" + err.getCadenaSQL());
    } else {
        out.println(frac.getMsg());
    }

} else if (request.getParameter("xAccion").equals("muestraFracciones")) {

    sql = new StringBuilder();
    rs = new Resultados();
    int x = 0;

    sql.append("SELECT F.CVE_ARTICULO IDART,F.CVE_FRACCION IDFRAC,A.NOMBRE ARTICULO,F.NOMBRE,F.DESCRIPCION FRACCION,F.ACTIVO ");
    sql.append("FROM FRACCIONES F ");
    sql.append("INNER JOIN ARTICULOS A ON A.CVE_ARTICULO = F.CVE_ARTICULO ");
    sql.append("ORDER BY IDFRAC");
    rs = UtilDB.ejecutaConsulta(sql.toString());
%>

<script>

    $(document).ready(function () {
        $('#tablaFracciones').DataTable();
    });

</script>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5 class="text-center text-uppercase">Fracciones</h5>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <table id="tablaFracciones" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Artículo</th>
                                <th>Nombre</th>                                            
                                <th>Fracciones</th>                                            
                                <th>Activo</th>                                            
                            </tr>
                        </thead>
                        <tbody>
                            <%
                                while (rs.next()) {
                                    x++;
                            %>
                            <tr>
                                <td style="text-align: center"><%=x%></td>
                                <td style="text-align: center">
                                    <%=rs.getString("ARTICULO")%>
                                </td>
                                <td style="text-align: center">
                                    <a href="javascript:recargaFraccion('acciones.jsp?xAccion=recargaFraccion','<%=rs.getInt("IDFRAC")%>');" class="liga"><%=rs.getString("NOMBRE")%> </a>
                                </td>
                                <td style="text-align: justify">
                                    <%=rs.getString("FRACCION")%>
                                </td>
                                <td style="text-align: center">
                                    <img src="<%=rs.getBoolean("ACTIVO") ? "../img/checkmark.png" : "../img/cerrar.png"%>" width="16" height="16" title="<%=rs.getBoolean("ACTIVO") ? "Activo" : "Inactivo"%>" border="0">
                                </td>
                            </tr>
                            <%
                                }
                                rs.close();
                                rs = null;
                                sql = new StringBuilder();
                            %>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>                
</div>

<%
} else if (request.getParameter("xAccion").equals("recargaFraccion")) {

    int cve = 0;

    if (request.getParameter("cve") != null) {
        cve = Integer.parseInt(request.getParameter("cve"));
    }

    Fracciones fracR = new Fracciones();
    fracR = new Fracciones(cve);

    json = new JSONObject();
    json.put("xCveFraccion", new Integer(fracR.getCveFraccion()));
    json.put("xCveArticulo", new Integer(fracR.getCveArticulo()));
    json.put("xNombre", new String(fracR.getNombre()));
    json.put("xDescripcion", new String(fracR.getDescripcion()));
    json.put("xActivo", new Boolean(fracR.isActivo()));

    out.print(json);
    out.flush();
    return;

} else if (request.getParameter("xAccion").equals("cargaComboFracciones")) {

    sql = new StringBuilder();
    rs = new Resultados();

    int cveArt = 0;

    if (request.getParameter("cveArt") != null) {
        cveArt = Integer.parseInt(request.getParameter("cveArt"));
    }

    int cveFrac = 0;

    if (request.getParameter("cveFrac") != null) {
        cveFrac = Integer.parseInt(request.getParameter("cveFrac"));
    }

    sql.append("SELECT CVE_ARTICULO IDART,CVE_FRACCION IDFRAC,NOMBRE FRACCION,DESCRIPCION,ACTIVO ");
    sql.append("FROM FRACCIONES ");
    sql.append("WHERE CVE_ARTICULO = ").append(cveArt).append(" ");
    if (area != 1) {
        sql.append("AND ACTIVO = 1 AND cve_fraccion IN (SELECT cve_fraccion FROM permisos WHERE activo = 1 AND cve_area = ").append(area).append(" GROUP BY cve_fraccion)");
    } else {
        sql.append("AND ACTIVO = 1 ");
    }
    sql.append("ORDER BY IDFRAC");
    rs = UtilDB.ejecutaConsulta(sql.toString());

    String text = "";
    text += "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
    if (rs.recordCount() != 0) {
        while (rs.next()) {
            text += "<option value=\"" + rs.getInt("IDFRAC") + "\"" + (rs.getInt("IDFRAC") == cveFrac ? " selected" : "") + ">" + rs.getString("FRACCION") + "</option>";
        }
    }

    out.println(text);
    rs.close();
    rs = null;
    sql = new StringBuilder();
    return;

} else if (request.getParameter("xAccion").equals("grabaIncisos")) {

    Incisos inc = new Incisos();
    int cveInc = 0;
    ErrorSistema err = new ErrorSistema();

    try {
        cveInc = Integer.parseInt(request.getParameter("xCveInciso"));
    } catch (Exception e) {
        cveInc = 0;
    }
    inc = null;
    inc = new Incisos(cveInc);

    inc.setCveArticulo(Integer.parseInt(request.getParameter("xCveArticuloV")));
    inc.setCveFraccion(Integer.parseInt(request.getParameter("xCveFraccionV")));
    inc.setCveInciso(Integer.parseInt(request.getParameter("xCveInciso")));
    inc.setDescripcion(request.getParameter("xDescripcion"));
    inc.setActivo(request.getParameter("xActivoVal").equals("1") ? true : false);
    err = inc.grabar();

    if (err.getNumeroError() != 0) {
        System.out.println("GrabÃ³, error no. " + err.getCadenaError() + "\n" + err.getCadenaSQL());
    } else {
        out.println(inc.getMsg());
    }

} else if (request.getParameter("xAccion").equals("muestraIncisos")) {

    sql = new StringBuilder();
    rs = new Resultados();
    int x = 0;

    sql.append("SELECT I.CVE_ARTICULO IDART,I.CVE_FRACCION IDFRA,A.NOMBRE ARTICULO,I.CVE_INCISO IDINC,F.NOMBRE FRACCION,I.DESCRIPCION INCISO,I.ACTIVO ");
    sql.append("FROM INCISOS I ");
    sql.append("INNER JOIN ARTICULOS A ON A.CVE_ARTICULO = I.CVE_ARTICULO ");
    sql.append("INNER JOIN FRACCIONES F ON F.CVE_FRACCION = I.CVE_FRACCION ");
    sql.append("ORDER BY ARTICULO,IDFRA,INCISO");
    rs = UtilDB.ejecutaConsulta(sql.toString());
%>

<script>

    $(document).ready(function () {
        $('#tablaIncisos').DataTable();
    });

</script>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5 class="text-center text-uppercase">Fracciones</h5>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <table id="tablaIncisos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Artículo</th>
                                <th>Fraccion</th>                                            
                                <th>Inciso</th>                                            
                                <th>Activo</th>                                            
                            </tr>
                        </thead>
                        <tbody>
                            <%
                                while (rs.next()) {
                                    x++;
                            %>
                            <tr>
                                <td style="text-align: center"><%=x%></td>
                                <td style="text-align: center">
                                    <%=rs.getString("ARTICULO")%>
                                </td>
                                <td style="text-align: center">
                                    <%=rs.getString("FRACCION")%>
                                </td>
                                <td style="text-align: justify">
                                    <a href="javascript:recargaInciso('acciones.jsp?xAccion=recargaInciso','<%=rs.getInt("IDINC")%>');" class="liga"><%=rs.getString("INCISO")%></a>
                                </td>
                                <td style="text-align: center">
                                    <img src="<%=rs.getBoolean("ACTIVO") ? "../img/checkmark.png" : "../img/cerrar.png"%>" width="16" height="16" title="<%=rs.getBoolean("ACTIVO") ? "Activo" : "Inactivo"%>" border="0">
                                </td>
                            </tr>
                            <%
                                }
                                rs.close();
                                rs = null;
                                sql = new StringBuilder();
                            %>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>                
</div>

<%
} else if (request.getParameter("xAccion").equals("recargaInciso")) {

    int cve = 0;

    if (request.getParameter("cve") != null) {
        cve = Integer.parseInt(request.getParameter("cve"));
    }

    Incisos incR = new Incisos();
    incR = new Incisos(cve);

    json = new JSONObject();
    json.put("xCveInciso", new Integer(incR.getCveInciso()));
    json.put("xCveFraccion", new Integer(incR.getCveFraccion()));
    json.put("xCveArticulo", new Integer(incR.getCveArticulo()));
    json.put("xDescripcion", new String(incR.getDescripcion()));
    json.put("xActivo", new Boolean(incR.isActivo()));

    out.print(json);
    out.flush();
    return;

} else if (request.getParameter("xAccion").equals("cargaComboIncisos")) {

    sql = new StringBuilder();
    rs = new Resultados();

    int cveArt = 0;

    if (request.getParameter("cveArt") != null) {
        cveArt = Integer.parseInt(request.getParameter("cveArt"));
    }

    int cveFrac = 0;

    if (request.getParameter("cveFrac") != null) {
        cveFrac = Integer.parseInt(request.getParameter("cveFrac"));
    }

    int cveInc = 0;

    if (request.getParameter("cveInc") != null) {
        cveInc = Integer.parseInt(request.getParameter("cveInc"));
    }

    sql.append("SELECT CVE_ARTICULO IDART,CVE_FRACCION IDFRAC,CVE_INCISO IDINC,DESCRIPCION INCISO,ACTIVO ");
    sql.append("FROM INCISOS ");
    sql.append("WHERE CVE_ARTICULO = ").append(cveArt).append(" ");
    sql.append("AND CVE_FRACCION = ").append(cveFrac).append(" ");
    sql.append("ORDER BY IDINC");
    rs = UtilDB.ejecutaConsulta(sql.toString());

    String text = "";
    text += "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
    if (rs.recordCount() != 0) {
        while (rs.next()) {
            text += "<option value=\"" + rs.getInt("IDINC") + "\"" + (rs.getInt("IDINC") == cveInc ? " selected" : "") + ">" + rs.getString("INCISO") + "</option>";
        }
    }

    out.println(text);
    rs.close();
    rs = null;
    sql = new StringBuilder();
    return;

} else if (request.getParameter("xAccion").equals("cargaComboApartados")) {

    sql = new StringBuilder();
    rs = new Resultados();

    int cveArt = 0;

    if (request.getParameter("cveArt") != null) {
        cveArt = Integer.parseInt(request.getParameter("cveArt"));
    }

    int cveFrac = 0;

    if (request.getParameter("cveFrac") != null) {
        cveFrac = Integer.parseInt(request.getParameter("cveFrac"));
    }

    int cveInc = 0;

    if (request.getParameter("cveInc") != null) {
        cveInc = Integer.parseInt(request.getParameter("cveInc"));
    }

    sql.append("SELECT * FROM APARTADOS ");
    sql.append("WHERE CVE_ARTICULO = ").append(cveArt).append(" ");
    sql.append("AND CVE_FRACCION = ").append(cveFrac).append(" ");
    sql.append("AND CVE_INCISO = ").append(cveInc);
    sql.append(" ORDER BY CVE_APARTADO");
    System.out.println(sql.toString());
    rs = UtilDB.ejecutaConsulta(sql.toString());

    String text = "";
    text += "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
    if (rs.recordCount() != 0) {
        while (rs.next()) {
            text += "<option value=\"" + rs.getInt("cve_apartado") + "\"  >" + rs.getString("descripcion") + "</option>";
        }
    }

    out.println(text);
    rs.close();
    rs = null;
    sql = new StringBuilder();
    return;

} else if (request.getParameter("xAccion").equals("grabaApartados")) {

    Apartados apa = new Apartados();
    int cveApa = 0;
    ErrorSistema err = new ErrorSistema();

    try {
        cveApa = Integer.parseInt(request.getParameter("xCveApartado"));
    } catch (Exception e) {
        cveApa = 0;
    }
    apa = null;
    apa = new Apartados(cveApa);

    apa.setCveArticulo(Integer.parseInt(request.getParameter("xCveArticuloV")));
    apa.setCveFraccion(Integer.parseInt(request.getParameter("xCveFraccionV")));
    apa.setCveInciso(Integer.parseInt(request.getParameter("xCveIncisoV")));
    apa.setCveApartado(Integer.parseInt(request.getParameter("xCveApartado")));
    apa.setDescripcion(request.getParameter("xDescripcion"));
    apa.setActivo(request.getParameter("xActivoVal").equals("1") ? true : false);
    err = apa.grabar();

    if (err.getNumeroError() != 0) {
        System.out.println("GrabÃ³, error no. " + err.getCadenaError() + "\n" + err.getCadenaSQL());
    } else {
        out.println(apa.getMsg());
    }

} else if (request.getParameter("xAccion").equals("muestraApartados")) {

    sql = new StringBuilder();
    rs = new Resultados();
    int x = 0;

    sql.append("SELECT A.CVE_ARTICULO IDART,A.CVE_FRACCION IDFRAC,A.CVE_INCISO IDINC,A.CVE_APARTADO IDAPA, ");
    sql.append("AR.NOMBRE ARTICULO,F.NOMBRE FRACCION,I.DESCRIPCION INCISO,A.DESCRIPCION APARTADO,A.ACTIVO ");
    sql.append("FROM APARTADOS A ");
    sql.append("INNER JOIN ARTICULOS AR ON AR.CVE_ARTICULO = A.CVE_ARTICULO ");
    sql.append("INNER JOIN FRACCIONES F ON F.CVE_FRACCION = A.CVE_FRACCION ");
    sql.append("INNER JOIN INCISOS I ON I.CVE_INCISO = A.CVE_INCISO ");
    sql.append("ORDER BY ARTICULO,IDFRAC,INCISO,APARTADO");
    rs = UtilDB.ejecutaConsulta(sql.toString());
%>

<script>

    $(document).ready(function () {
        $('#tablaApartados').DataTable();
    });

</script>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5 class="text-center text-uppercase">Fracciones</h5>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <table id="tablaApartados" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Artículo</th>
                                <th>Fraccion</th>                                            
                                <th>Inciso</th>
                                <th>Apartado</th>
                                <th>Activo</th>                                            
                            </tr>
                        </thead>
                        <tbody>
                            <%
                                while (rs.next()) {
                                    x++;
                            %>
                            <tr>
                                <td style="text-align: center"><%=x%></td>
                                <td style="text-align: center">
                                    <%=rs.getString("ARTICULO")%>
                                </td>
                                <td style="text-align: center">
                                    <%=rs.getString("FRACCION")%>
                                </td>
                                <td style="text-align: justify">
                                    <%=rs.getString("INCISO")%>
                                </td>
                                <td style="text-align: justify">
                                    <a href="javascript:recargaApartado('acciones.jsp?xAccion=recargaApartado','<%=rs.getInt("IDAPA")%>');" class="liga"><%=rs.getString("APARTADO")%></a>
                                </td>
                                <td style="text-align: center">
                                    <img src="<%=rs.getBoolean("ACTIVO") ? "../img/checkmark.png" : "../img/cerrar.png"%>" width="16" height="16" title="<%=rs.getBoolean("ACTIVO") ? "Activo" : "Inactivo"%>" border="0">
                                </td>
                            </tr>
                            <%
                                }
                                rs.close();
                                rs = null;
                                sql = new StringBuilder();
                            %>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>                
</div>

<%
} else if (request.getParameter("xAccion").equals("recargaApartado")) {

    int cve = 0;

    if (request.getParameter("cve") != null) {
        cve = Integer.parseInt(request.getParameter("cve"));
    }

    Apartados apaR = new Apartados();
    apaR = new Apartados(cve);

    json = new JSONObject();
    json.put("xCveApartado", new Integer(apaR.getCveApartado()));
    json.put("xCveArticulo", new Integer(apaR.getCveArticulo()));
    json.put("xCveFraccion", new Integer(apaR.getCveFraccion()));
    json.put("xCveInciso", new Integer(apaR.getCveInciso()));
    json.put("xDescripcion", new String(apaR.getDescripcion()));
    json.put("xActivo", new Boolean(apaR.isActivo()));

    out.print(json);
    out.flush();
    return;

} else if (request.getParameter("xAccion").equals("cargaComboApartado")) {

    sql = new StringBuilder();
    rs = new Resultados();

    int cveArt = 0;

    if (request.getParameter("cveArt") != null) {
        cveArt = Integer.parseInt(request.getParameter("cveArt"));
    }

    int cveFrac = 0;

    if (request.getParameter("cveFrac") != null) {
        cveFrac = Integer.parseInt(request.getParameter("cveFrac"));
    }

    int cveInc = 0;

    if (request.getParameter("cveInc") != null) {
        cveInc = Integer.parseInt(request.getParameter("cveInc"));
    }

    int cveApa = 0;

    if (request.getParameter("cveApa") != null) {
        cveApa = Integer.parseInt(request.getParameter("cveApa"));
    }

    sql.append("SELECT A.CVE_ARTICULO IDART,A.CVE_FRACCION IDFRA,A.CVE_INCISO IDINC,A.CVE_APARTADO IDAPA,A.DESCRIPCION APARTADO,A.ACTIVO ");
    sql.append("FROM APARTADOS A ");
    sql.append("WHERE A.CVE_ARTICULO = ").append(cveArt).append(" ");
    sql.append("AND A.CVE_FRACCION = ").append(cveFrac).append(" ");
    sql.append("AND CVE_INCISO = ").append(cveInc).append(" ");
    sql.append("ORDER BY IDAPA");
    rs = UtilDB.ejecutaConsulta(sql.toString());

    String text = "";
    text += "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
    if (rs.recordCount() != 0) {
        while (rs.next()) {
            text += "<option value=\"" + rs.getInt("IDAPA") + "\"" + (rs.getInt("IDAPA") == cveApa ? " selected" : "") + ">" + rs.getString("APARTADO") + "</option>";
        }
    } else {
        text += "<option value='0' selected>No existen apartados para este artículo.</option>";
    }

    out.println(text);
    rs.close();
    rs = null;
    sql = new StringBuilder();
    return;

} else if (request.getParameter("xAccion").equals("grabaTransparencia")) {

    Documentos doc = new Documentos();
    int cveDoc = 0;
    ErrorSistema err = new ErrorSistema();

    int xPagina = 0;

    if (request.getParameter("xPagina") != null) {
        xPagina = Integer.parseInt(request.getParameter("xPagina"));
    }

    try {
        cveDoc = Integer.parseInt(request.getParameter("xCveDocumento"));
    } catch (Exception e) {
        cveDoc = 0;
    }
    doc = null;
    doc = new Documentos(cveDoc);

    doc.setCveDocumento(Integer.parseInt(request.getParameter("xCveDocumento")));
    doc.setCveArticulo(Integer.parseInt(request.getParameter("xCveArticuloV")));
    doc.setCveFraccion(Integer.parseInt(request.getParameter("xCveFraccionV")));
    doc.setCveInciso(Integer.parseInt(request.getParameter("xCveIncisoV")));
    doc.setCveApartado(Integer.parseInt(request.getParameter("xCveApartadoV")));
    doc.setAnio(Integer.parseInt(request.getParameter("xCveAnio")));
    doc.setTrimestre(Integer.parseInt(request.getParameter("xCveTrimestre")));
    doc.setNombre(request.getParameter("xNombre"));

    Vector fi;
    Calendar fecha = Calendar.getInstance();
    if (String.valueOf(request.getParameter("xFechaActualizacion")) != null) {
        if (!String.valueOf(request.getParameter("xFechaActualizacion")).equals("")) {
            fi = Utilerias.split("/", String.valueOf(request.getParameter("xFechaActualizacion")));
            fecha.set(Integer.parseInt((String) fi.elementAt(2)), Integer.parseInt((String) fi.elementAt(1)) - 1, Integer.parseInt((String) fi.elementAt(0)));
            doc.setFechaActDoc(fecha);
        }
    }

    doc.setRutaDocto(request.getParameter("xRuta").equals("---") ? "---" : request.getParameter("xRuta").substring(httpSession.getAttribute(Sesiones.URL_PUBLICA).toString().length(), request.getParameter("xRuta").length()));

    if (xPagina == 1) {
        doc.setAnexo(true);
    } else {
        doc.setAnexo(false);
    }

    doc.setCveUser(Integer.parseInt(request.getParameter("xCveUsuario")));

    doc.setCveUser2(Integer.parseInt(request.getParameter("xCveUsuario2")));

    Calendar fechaMR = Calendar.getInstance();
    if (String.valueOf(request.getParameter("xFechaActualizacionR")) != null) {
        if (!String.valueOf(request.getParameter("xFechaActualizacionR")).equals("")) {
            fi = Utilerias.split("/", String.valueOf(request.getParameter("xFechaActualizacionR")));
            fechaMR.set(Integer.parseInt((String) fi.elementAt(2)), Integer.parseInt((String) fi.elementAt(1)) - 1, Integer.parseInt((String) fi.elementAt(0)));
            doc.setFechaMod(fechaMR);
        }
    }

    doc.setActivo(request.getParameter("xActivoVal").equals("1") ? true : false);
    err = doc.grabar();

    if (err.getNumeroError() != 0) {
        System.out.println("GrabÃ³, error no. " + err.getCadenaError() + "\n" + err.getCadenaSQL());
    } else {
        out.println(doc.getMsg());
    }

} else if (request.getParameter("xAccion").equals("muestraDoctos")) {

    sql = new StringBuilder();
    rs = new Resultados();
    int x = 0;
    String titulo = "";

    int xPagina = 0;

    if (request.getParameter("xPagina") != null) {
        xPagina = Integer.parseInt(request.getParameter("xPagina"));
    }

    if (xPagina == 1) {
        titulo = "Anexos";
    } else {
        titulo = "Transparencia";
    }

    sql.append("SELECT D.CVE_DOCUMENTO IDDOC,D.CVE_ARTICULO IDART,D.CVE_FRACCION IDFRAC,D.CVE_INCISO IDINC,D.CVE_APARTADO IDAPA, ");
    sql.append("A.NOMBRE ARTICULO,F.NOMBRE FRACCION,D.ANIO,D.TRIMESTRE TRI, ");
    sql.append("D.NOMBRE,CONVERT(NVARCHAR,D.FECHA_ACTUALIZACION_DOCUMENTO,106) FECHA_ACT,D.RUTA_DOCUMENTO RUTA, ");
    sql.append("D.ANEXO,D.CVE_USUARIO IDUSER,CONVERT(NVARCHAR,D.FECHA_REGISTRO,106) FECHA_REG,D.CVE_USUARIO2 IDUSER2, ");
    sql.append("CONVERT(NVARCHAR,D.FECHA_MODIFICACION,106) FECHA_MOD,D.ACTIVO ");
    sql.append("FROM DOCUMENTOS D ");
    sql.append("INNER JOIN ARTICULOS A ON A.CVE_ARTICULO = D.CVE_ARTICULO ");
    sql.append("INNER JOIN FRACCIONES F ON F.CVE_FRACCION = D.CVE_FRACCION ");
    if (xPagina == 1) {
        sql.append("WHERE D.ANEXO = 1 ");
    } else {
        sql.append("WHERE D.ANEXO = 0 ");
    }
    if (area != 1) {
            sql.append("AND D.CVE_ARTICULO IN (SELECT cve_articulo FROM permisos WHERE activo = 1 AND cve_area = ").append(area).append(" GROUP BY cve_articulo) ");
            sql.append("AND D.CVE_FRACCION IN (SELECT cve_articulo FROM permisos WHERE activo = 1 AND cve_area = ").append(area).append(" GROUP BY cve_articulo) ");
        }
    sql.append(" AND D.ANIO = ").append(anio).append(" AND D.TRIMESTRE = ").append(trimestre);
    sql.append(" ORDER BY ARTICULO,FRACCION,NOMBRE");
    rs = UtilDB.ejecutaConsulta(sql.toString());
%>

<script>

    $(document).ready(function () {
        $('#tablaDoctos').DataTable();
    });

</script>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5 class="text-center text-uppercase"><%=titulo%></h5>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <table id="tablaDoctos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Año</th>
                                <th>Trimestre</th>
                                <th>Artículo</th>
                                <th>Fraccion</th>                                            
                                <th>Nombre</th>
                                <th>Liga</th>
                                <th>Tipo archivo</th>
                                <th>Activo</th>                                            
                            </tr>
                        </thead>
                        <tbody>
                            <%
                                while (rs.next()) {
                                    x++;
                            %>
                            <tr>
                                <td style="text-align: center"><%=x%></td>
                                <td style="text-align: center"><%=rs.getInt("ANIO")%></td>
                                <td style="text-align: center"><%= trimestres[rs.getInt("TRI") -1] %></td>
                                <td style="text-align: center">
                                    <a href="javascript:recargaDocto('acciones.jsp?xAccion=recargaDocto','<%=rs.getInt("IDDOC")%>');" class="liga"><%=rs.getString("ARTICULO")%></a>
                                </td>
                                <td style="text-align: center">
                                    <%=rs.getString("FRACCION")%>
                                </td>
                                <td style="text-align: justify">
                                    <%=rs.getString("NOMBRE")%>
                                </td>
                                <td style="text-align: justify">
                                    <%
                                        if (rs.getString("RUTA").equals("")) {
                                    %>
                                    <a href="javascript:modalArchivos('<%=rs.getInt("IDDOC")%>','<%=rs.getString("NOMBRE")%>');" class="liga">
                                        <%
                                            if (xPagina == 1) {
                                        %>
                                        Subir documento
                                        <%
                                        } else {
                                        %>
                                        Subir archivo xls
                                        <%
                                            }
                                        %>
                                    </a>
                                    <%
                                    } else {
                                    %>
                                    <a href="<%=httpSession.getAttribute(Sesiones.URL_PUBLICA).toString() + rs.getString("RUTA")%>" target="_blank"><%=httpSession.getAttribute(Sesiones.URL_PUBLICA).toString() + rs.getString("RUTA")%></a>
                                    <%
                                        }
                                    %>
                                </td>
                                <td style="text-align: center">

                                    <%
                                        String extension = "";

                                        int i = rs.getString("RUTA").lastIndexOf('.');
                                        if (i > 0) {
                                            extension = rs.getString("RUTA").substring(i + 1);
                                        }
                                    %>

                                    <%
                                        if (extension.equals("xlsx") || extension.equals("xls")) {
                                    %>
                                    <img src="../img/extensiones/File-Excel-icon.png" title="Excel">
                                    <%
                                    } else if (extension.equals("docx")) {
                                    %>
                                    <img src="../img/extensiones/Word-icon.png" title="Word">
                                    <%
                                    } else if (extension.equals("pdf")) {
                                    %>
                                    <img src="../img/extensiones/acrobat.png" title="Pdf">
                                    <%
                                    } else if (extension.equals("pptx")) {
                                    %>
                                    <img src="../img/extensiones/File-PowerPoint-icon.png" title="Power Point">
                                    <%
                                    } else if (extension.equals("zip")) {
                                    %>
                                    <img src="../img/extensiones/zipito.png" title="Zip">
                                    <%
                                    } else if (extension.equals("rar")) {
                                    %>
                                    <img src="../img/extensiones/Winrar-SZ-icon.png" title="Winrar">
                                    <%
                                        }
                                    %>

                                </td>
                                <td style="text-align: center">
                                    <img src="<%=rs.getBoolean("ACTIVO") ? "../img/checkmark.png" : "../img/cerrar.png"%>" width="16" height="16" title="<%=rs.getBoolean("ACTIVO") ? "Activo" : "Inactivo"%>" border="0">
                                </td>
                            </tr>
                            <%
                                }
                                rs.close();
                                rs = null;
                                sql = new StringBuilder();
                            %>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>                
</div>

<%
        } else if (request.getParameter("xAccion").equals("recargaDocto")) {

            int cve = 0;

            if (request.getParameter("cve") != null) {
                cve = Integer.parseInt(request.getParameter("cve"));
            }

            Documentos docto = new Documentos();
            docto = new Documentos(cve);
            String ruta = "";

            if (docto.getRutaDocto().equals("---")) {
                ruta = "";
            } else {
                ruta = httpSession.getAttribute(Sesiones.URL_PUBLICA).toString();
            }

            json = new JSONObject();
            json.put("xCveDocumento", new Integer(docto.getCveDocumento()));
            json.put("xCveApartado", new Integer(docto.getCveApartado()));
            json.put("xCveArticulo", new Integer(docto.getCveArticulo()));
            json.put("xCveFraccion", new Integer(docto.getCveFraccion()));
            json.put("xCveInciso", new Integer(docto.getCveInciso()));
            json.put("xCveUsuario", new Integer(docto.getCveUser()));
            json.put("xFechaActualizacion", new String(docto.getFechaAct()));
            json.put("xNombre", new String(docto.getNombre()));
            json.put("xRuta", new String(ruta + docto.getRutaDocto()));
            json.put("xActivo", new Boolean(docto.isActivo()));

            out.print(json);
            out.flush();
            return;

        }
    }
%>