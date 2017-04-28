<%-- 
    Document   : frm-subir-archivo
    Created on : 27-mar-2017, 10:24:32
    Author     : Roberto Eder Weiss Juárez
--%>
<%@page import="mx.edu.uttab.transparencia.comun.*"%>
<%
    String origen = request.getParameter("xOrigen") != null ? request.getParameter("xOrigen") : "";
    int cveDoc = request.getParameter("xcveDoc") != null ? Integer.parseInt(request.getParameter("xcveDoc")) : 0;
    String nombre = request.getParameter("nombre") != null ? request.getParameter("nombre") : "";

    HttpSession httpSession = request.getSession(false);

    if (httpSession.getAttribute(Sesiones.USUARIO) == null) {
        response.sendRedirect("../index.jsp");
        return;
    }

    int xPagina = 0;

    if (request.getParameter("xPagina") != null) {
        xPagina = Integer.parseInt(request.getParameter("xPagina"));
    }

    int usuario = Integer.parseInt(String.valueOf(httpSession.getAttribute(Sesiones.USUARIO)));
%>
<%@page contentType="text/html" pageEncoding="UTF-8" session="false"%>

<script>

    function checkfile(sender) {
        $('#subirXls').attr("disabled", false);

    <%
        if (xPagina == 1) {
    %>
        var validExts = new Array(".xlsx", ".xls", ".docx", ".pdf", ".pptx", ".zip", ".rar");
    <%
    } else {
    %>
        var validExts = new Array(".xlsx", ".xls");
    <%
        }
    %>


        var fileExt = sender.value;
        fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
        if (validExts.indexOf(fileExt) < 0) {
            alert("Archivo seleccionado no válido, los archivos válidos son: " + validExts.toString() + ".");
            $('#subirXls').attr("disabled", true);
            return false;
        } else {
            return true;
        }
    }

    function subirArchivo()
    {
        var sampleFile = document.getElementById("txtArchivo").files[0];
        var formdata = new FormData();
        formdata.append("txtArchivo", sampleFile);
        var xhr = new XMLHttpRequest();
        var ori = $('#txtOrigen').val();
        var nombre = $('#txtNombre').val();
        xhr.open("POST", "../SubirArchivos?cveDoc=" + <%=cveDoc%> + "&ori=" + ori + "&cveUser2=" + <%=usuario%> + "&nombre=" + nombre, true);
        xhr.send(formdata);
        xhr.onload = function () {
            var respuesta = this.responseText == 'ok' ? '!Imagenes subidas correctamente!' : this.responseText;
            $("#alertaDocumento").html("<span class='custom' style='color:#0C0;font-size:12px;font-weight:bold'>" + respuesta + "</span>");//.fadeIn("fast").fadeOut(6500);		
            $("#archivoXls").submit();
        };
    }

</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Subir archivo</h4>
</div>
<div class="modal-body">
    <div class="te">
        <form id="archivoXls" name="archivoXls" method="post">            
            <input type="file" name="txtArchivo" id="txtArchivo" onchange="checkfile(this);" />
            <input type="hidden" name="txtOrigen" id="txtOrigen" value="<%=origen%>"/>
            <input type="hidden" name="txtDoc" id="txtDoc" value="<%=cveDoc%>"/>
            <input type="hidden" name="txtNombre" id="txtNombre" value="<%=nombre%>"/>
            <br>
            <input class="btn btn-md btn-success btn-block" name="subirXls" id="subirXls" value="Subir archivo" onclick="subirArchivo();" readonly="" /> 
        </form>
    </div>
</div> 
<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
</div>