<?php
require_once '../class/UtilDB.php';
$articulo = 0;
$fraccion = 0;
$inciso = 0;
$apartado = 0;
$anio = 0;
$trimestre = 0;
$page_context = "";
$sql = "";
$html = "";
$rst = NULL;

if (isset($_POST["xAccion"])) {
    if ($_POST["xAccion"] === "getDocumentos") {
        $page_context = $_POST["xPageContext"];
        $articulo = (int) $_POST["xArticulo"];
        $fraccion = (int) $_POST["xFraccion"];
        $inciso = (int) $_POST["xInciso"];
        $apartado = (int) $_POST["xApartado"];
        $anio = (int) $_POST["xAnio"];
        $trimestre = (int) $_POST["xTrimestre"];

        $sql .= "SELECT * FROM documentos WHERE";
        $sql .= " cve_articulo " . ($articulo == 0 ? "IS NULL" : "= " . $articulo);
        $sql .= " AND cve_fraccion " . ($fraccion == 0 ? "IS NULL" : "= " . $fraccion);
        $sql .= " AND cve_inciso " . ($inciso == 0 ? "IS NULL" : "= " . $inciso);
        $sql .= " AND cve_apartado " . ($apartado == 0 ? "IS NULL" : "= " . $apartado);
        $sql .= " AND anio =" . $anio;
        $sql .= " AND trimestre =" . $trimestre;
        $sql .= " AND anexo = 1";
        $sql .= " AND activo = 1";

        $rst = UtilDB::ejecutaConsulta($sql);

        if ($rst->rowCount() != 0) {
            $html .= "<ul style=\"list-style-image: url(../img/liston.png); list-style-position: inside;\" >";
            foreach ($rst as $row) {
                $html .= "<li>";
                $html .= "<a href=\"" . ($page_context . "/" . $row["ruta_documento"]) . "\" target=\"_blank\">" . $row["nombre"] . "</a> " . "(fecha de actualización: " . $row["fecha_actualizacion_documento"] . ")";
                $html .= "</li>";
            }
            $html .= "</ul>";
        } else {
            $html .= "<p class=\"text-center\">No se han cargado documentos por el momento</p>";
        }
        $rst->closeCursor();

        echo($html);
        return;
    }
}
?>