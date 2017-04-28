<%-- 
    Document   : subir-archivo
    Created on : 27-mar-2017, 10:29:58
    Author     : Roberto Eder Weiss Juárez
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%
    
    if (request.getParameter("txtOrigen") != null) {
        String origen = request.getParameter("txtOrigen");
        int cveDoc = Integer.parseInt(request.getParameter("txtDoc"));
        
        if (origen.equals("transparencia")) {
            response.sendRedirect("transparencia.jsp");
            return;
        }
        if (origen.equals("anexos")) {
            response.sendRedirect("anexos.jsp");
            return;
        }

    }
%>