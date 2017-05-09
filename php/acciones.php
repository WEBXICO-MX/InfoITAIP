<?php
session_start();
require_once '../class/Articulo.php';
require_once '../class/Fraccion.php';
require_once '../class/Inciso.php';
require_once '../class/Apartado.php';
require_once '../class/Documento.php';
require_once '../class/Area.php';
require_once '../class/Usuario.php';
require_once '../class/ChromePhp.php';


if (!isset($_SESSION['usr']) or $_SESSION['area'] != 1) {
    header('Location: ../index.php');
    die();
    return;
}

$ql = "";
$rst = NULL;

$area = isset($_SESSION['area']) ? (int) $_SESSION['area'] : 0;
$anio = isset($_SESSION['anio']) ? (int) $_SESSION['anio'] : 0;
$trimestre = isset($_SESSION['trimestre']) ? (int) $_SESSION['trimestre'] : 0;
$trimestres = ["1er trimestre (enero-marzo)", "2do trimestre (abril-junio)", "3er trimestre (julio-septimebre)", "4to trimestre (octubre-diciembre)"];

if (isset($_POST["xAccion"])) {
    if ($_POST["xAccion"] === "grabaArticulo") {

        $art = new Articulo();
        $cveArt = 0;

        try {
            $cveArt = (int) $_POST["xCveArticulo"];
        } catch (Exception $e) {
            $cveArt = 0;
        }
        $art = new Articulo($cveArt);
        $art->setCveArticulo((int) $_POST["xCveArticulo"]);
        $art->setNombre($_POST["xNombre"]);
        $art->setDescripcion($_POST["xDescripcion"]);
        $art->setActivo(isset($_POST["xActivo"]) ? true : false);

        $art->grabar();
    } elseif ($_POST["xAccion"] === "muestraArticulos") {

        $sql = "";
        $rst = NULL;
        $x = 0;

        $sql .= "SELECT CVE_ARTICULO IDART,NOMBRE,DESCRIPCION,ACTIVO FROM ARTICULOS ORDER BY DESCRIPCION";
        $rst = UtilDB::ejecutaConsulta($sql);
        ?>

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
                                    <?php
                                    foreach ($rst as $row) {
                                        $x++;
                                        ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo($x); ?></td>
                                            <td>
                                                <a href="javascript:recargaArticulo('acciones.php?xAccion=recargaArticulo','<?php echo($row["IDART"]); ?>');" class="liga"><?php echo($row["NOMBRE"]); ?></a>
                                            </td>
                                            <td style="text-align: justify">
                                                <?php echo($row["DESCRIPCION"]); ?>
                                            </td>
                                            <td style="text-align: center">
                                                <img src="<?php echo($row["ACTIVO"] ? "../img/checkmark.png" : "../img/cerrar.png"); ?>" width="16" height="16" title="<?php echo($row["ACTIVO"] ? "Activo" : "Inactivo"); ?>" border="0">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $rst->closeCursor();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                
        </div>

        <?php
    } elseif ($_POST["xAccion"] === "recargaArticulo") {

        $cve = 0;

        if (isset($_POST["cve"]) != null) {
            $cve = (int) $_POST["cve"];
        }

        $artR = new Articulo($cve);

        $json = '{"xCveArticulo":' . $artR->getCveArticulo() . ',"xDescripcion":"' . $artR->getDescripcion() . '","xNombre":"' . $artR->getNombre() . '","xActivo":' . $artR->getActivo() . '}';
        echo($json);
        return;
    } elseif ($_POST["xAccion"] === "grabaFracciones") {

        $frac = new Fraccion();
        $cveFrac = 0;

        try {
            $cveFrac = (int) $_POST["xCveFraccion"];
        } catch (Exception $e) {
            $cveFrac = 0;
        }
        $frac = NULL;
        $frac = new Fraccion($cveFrac);

        $frac->setCveArticulo(new Articulo((int) $_POST["xCveArticuloV"]));
        $frac->setCveFraccion((int) "xCveFraccion");
        $frac->setNombre($_POST["xNombre"]);
        $frac->setDescripcion($_POST["xDescripcion"]);
        $frac->setActivo(isset($_POST["xActivo"]) ? true : false);
        $frac->grabar();
    } elseif ($_POST["xAccion"] === "muestraFracciones") {

        $sql = "";
        $x = 0;

        $sql .= "SELECT F.CVE_ARTICULO IDART,F.CVE_FRACCION IDFRAC,A.NOMBRE ARTICULO,F.NOMBRE,F.DESCRIPCION FRACCION,F.ACTIVO ";
        $sql .= "FROM FRACCIONES F ";
        $sql .= "INNER JOIN ARTICULOS A ON A.CVE_ARTICULO = F.CVE_ARTICULO ";
        $sql .= "ORDER BY IDFRAC";
        $rst = UtilDB::ejecutaConsulta($sql);
        ?>

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
                                    <?php
                                    foreach ($rst as $row) {
                                        $x++;
                                        ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo($x); ?></td>
                                            <td style="text-align: center">
                                                <?php echo($row["ARTICULO"]); ?>
                                            </td>
                                            <td style="text-align: center">
                                                <a href="javascript:recargaFraccion('acciones.php?xAccion=recargaFraccion','<?php echo($row["IDFRAC"]); ?>');" class="liga"><?php echo($row["NOMBRE"]); ?> </a>
                                            </td>
                                            <td style="text-align: justify">
                                                <?php echo($row["FRACCION"]); ?>
                                            </td>
                                            <td style="text-align: center">
                                                <img src="<?php echo($row["ACTIVO"] ? "../img/checkmark.png" : "../img/cerrar.png"); ?>" width="16" height="16" title="<?php echo($row["ACTIVO"] ? "Activo" : "Inactivo"); ?>" border="0">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $rst->closeCursor();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                
        </div>

        <?php
    } elseif ($_POST["xAccion"] === "recargaFraccion") {

        $cve = 0;

        if (isset($_POST["cve"])) {
            $cve = (int) $_POST["cve"];
        }

        $fracR = new Fraccion($cve);

        $json = '{"xCveFraccion":' . $fracR->getCveFraccion() . ',"xCveArticulo":' . $fracR->getCveArticulo()->getCveArticulo() . ',"xNombre":"' . $fracR->getNombre() . '","xDescripcion":"' . $fracR->getDescripcion() . '","xActivo":' . $fracR->getActivo() . '}';
        echo($json);
        return;
    } elseif ($_POST["xAccion"] === "cargaComboFracciones") {

        $sql = "";
        $rst = NULL;

        $cveArt = 0;

        if (isset($_POST["cveArt"])) {
            $cveArt = (int) $_POST["cveArt"];
        }

        $cveFrac = 0;

        if (isset($_POST["cveFrac"])) {
            $cveFrac = (int) $_POST["cveFrac"];
        }

        $sql .= "SELECT CVE_ARTICULO AS IDART,cve_fraccion,nombre,DESCRIPCION,ACTIVO ";
        $sql .= "FROM FRACCIONES ";
        $sql .= "WHERE CVE_ARTICULO = " . $cveArt . " ";
        if ($area != 1) {
            $sql .= "AND ACTIVO = 1 AND cve_fraccion IN (SELECT cve_fraccion FROM permisos WHERE activo = 1 AND cve_area = " . $area . " GROUP BY cve_fraccion)";
        } else {
            $sql .= "AND ACTIVO = 1 ";
        }
        $sql .= "ORDER BY CVE_FRACCION";
        $rst = UtilDB::ejecutaConsulta($sql);

        $text = "";
        $text .= "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
        if ($rst->rowCount() != 0) {
            foreach ($rst as $row) {
                $text .= "<option value=\"" . $row["cve_fraccion"] . "\"" . ($row["cve_fraccion"] == $cveFrac ? " selected" : "") . ">" . $row["nombre"] . "</option>";
            }
        }

        echo($text);
        $rst->closeCursor();
        return;
    } elseif ($_POST["xAccion"] === "grabaIncisos") {

        $inc = new Inciso();
        $cveInc = 0;

        try {
            $cveInc = (int) $_POST["xCveInciso"];
        } catch (Exception $e) {
            $cveInc = 0;
        }
        $inc = new Inciso($cveInc);

        $inc->setCveArticulo(new Articulo((int) $_POST["xCveArticuloV"]));
        $inc->setCveFraccion(new Articulo((int) $_POST["xCveArticuloV"]), (int) $_POST["xCveFraccionV"]);
        $inc->setCveInciso((int) $_POST["xCveInciso"]);
        $inc->setDescripcion($_POST["xDescripcion"]);
        $inc->setActivo(isset($_POST["xActivo"]) ? true : false);
        $inc->grabar();
    } elseif ($_POST["xAccion"] === "muestraIncisos") {

        $sql = "";
        $rst = NULL;
        $x = 0;

        $sql .= "SELECT I.CVE_ARTICULO IDART,I.CVE_FRACCION IDFRA,A.NOMBRE ARTICULO,I.CVE_INCISO IDINC,F.NOMBRE FRACCION,I.DESCRIPCION INCISO,I.ACTIVO ";
        $sql .= "FROM INCISOS I ";
        $sql .= "INNER JOIN ARTICULOS A ON A.CVE_ARTICULO = I.CVE_ARTICULO ";
        $sql .= "INNER JOIN FRACCIONES F ON F.CVE_FRACCION = I.CVE_FRACCION ";
        $sql .= "ORDER BY ARTICULO,IDFRA,INCISO";
        $rst = UtilDB::ejecutaConsulta($sql);
        ?>

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
                                    <?php
                                    foreach ($rst as $row) {
                                        $x++;
                                        ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo($x); ?></td>
                                            <td style="text-align: center">
                                                <?php echo($row["ARTICULO"]); ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?Php echo($row["FRACCION"]); ?>
                                            </td>
                                            <td style="text-align: justify">
                                                <a href="javascript:recargaInciso('acciones.php?xAccion=recargaInciso','<?php echo($row["IDINC"]); ?>');" class="liga"><?php echo($row["INCISO"]); ?></a>
                                            </td>
                                            <td style="text-align: center">
                                                <img src="<?php echo($row["ACTIVO"] ? "../img/checkmark.png" : "../img/cerrar.png"); ?>" width="16" height="16" title="<?php echo($row["ACTIVO"] ? "Activo" : "Inactivo"); ?>" border="0">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $rst->closeCursor();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                
        </div>

        <?php
    } elseif ($_POST["xAccion"] === "recargaInciso") {

        $cve = 0;

        if (isset($_POST["cve"])) {
            $cve = (int) $_POST["cve"];
        }

        $incR = new Inciso($cve);

        $json = '{"xCveInciso":' . $incR->getCveInciso() . ',"xCveFraccion":' . $incR->getCveFraccion()->getCveFraccion() . ',"xCveArticulo":' . $incR->getCveArticulo()->getCveArticulo() . ',"xDescripcion":"' . $incR->getDescripcion() . '","xActivo":' . $incR->getActivo() . '}';
        echo($json);
        return;
    } elseif ($_POST["xAccion"] === "cargaComboIncisos") {

        $sql = "";
        $rst = NULL;

        $cveArt = 0;

        if (isset($_POST["cveArt"])) {
            $cveArt = (int) $_POST["cveArt"];
        }

        $cveFrac = 0;

        if (isset($_POST["cveFrac"])) {
            $cveFrac = (int) $_POST["cveFrac"];
        }

        $cveInc = 0;

        if (isset($_POST["cveInc"])) {
            $cveInc = (int) $_POST["cveInc"];
        }

        $sql .= "SELECT CVE_ARTICULO IDART,CVE_FRACCION IDFRAC,cve_inciso,descripcion,ACTIVO ";
        $sql .= "FROM INCISOS ";
        $sql .= "WHERE CVE_ARTICULO = " . $cveArt . " ";
        $sql .= "AND CVE_FRACCION = " . $cveFrac . " ";
        $sql .= "ORDER BY cve_inciso";
        $rst = UtilDB::ejecutaConsulta($sql);

        $text = "";
        $text .= "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
        if ($rst->rowCount() != 0) {
            foreach ($rst as $row) {
                $text .= "<option value=\"" . $row["cve_inciso"] . "\"" . ($row["cve_inciso"] == $cveInc ? " selected" : "") . ">" . $row["descripcion"] . "</option>";
            }
        }

        echo($text);
        $rst->closeCursor();
        return;
    } elseif ($_POST["xAccion"] === "cargaComboApartados") {

        $sql = "";
        $rst = NULL;

        $cveArt = 0;

        if (isset($_POST["cveArt"])) {
            $cveArt = (int) $_POST["cveArt"];
        }

        $cveFrac = 0;

        if (isset($_POST["cveFrac"])) {
            $cveFrac = (int) $_POST["cveFrac"];
        }

        $cveInc = 0;

        if (isset($_POST["cveInc"])) {
            $cveInc = (int) $_POST["cveInc"];
        }

        $sql .= "SELECT * FROM APARTADOS ";
        $sql .= "WHERE CVE_ARTICULO = " . $cveArt . " ";
        $sql .= "AND CVE_FRACCION = " . $cveFrac . " ";
        $sql .= "AND CVE_INCISO = " . $cveInc;
        $sql .= " ORDER BY CVE_APARTADO";
        echo($sql);
        $rst = UtilDB::ejecutaConsulta($sql);

        $text = "";
        $text .= "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
        if ($rst->rowCount() != 0) {
            foreach ($rst as $row) {
                $text .= "<option value=\"" . $row["cve_apartado"] . "\"  >" . $row["descripcion"] . "</option>";
            }
        }

        echo($text);
        $rst->closeCursor();
        return;
    } elseif ($_POST["xAccion"] === "grabaApartados") {

        $apa = new Apartado();
        $cveApa = 0;

        try {
            $cveApa = (int) $_POST["xCveApartado"];
        } catch (Exception $e) {
            $cveApa = 0;
        }
        $apa = new Apartado($cveApa);

        $apa->setCveArticulo(new Articulo((int) $_POST["xCveArticuloV"]));
        $apa->setCveFraccion(new Fraccion(new Articulo((int) $_POST["xCveArticuloV"]), (int) $_POST["xCveArticuloV"]));
        $apa->setCveInciso(new Inciso(new Articulo((int) $_POST["xCveArticuloV"]), new Fraccion(new Articulo((int) $_POST["xCveArticuloV"]), (int) $_POST["xCveArticuloV"]), (int) $_POST["xCveIncisoV"]));
        $apa->setCveApartado(new Articulo((int) $_POST["xCveArticuloV"]), new Fraccion(new Articulo((int) $_POST["xCveArticuloV"]), (int) $_POST["xCveArticuloV"]), new Inciso(new Articulo((int) $_POST["xCveArticuloV"]), new Fraccion(new Articulo((int) $_POST["xCveArticuloV"]), (int) $_POST["xCveArticuloV"]), (int) $_POST["xCveIncisoV"]), (int) $_POST["xCveApartado"]);
        $apa->setDescripcion(request . getParameter("xDescripcion"));
        $apa->setActivo(isset($_POST["xActivoVal"]) ? true : false);
        $apa->grabar();
    } elseif ($_POST["xAccion"] === "muestraApartados") {

        $sql = "";
        $rst = NULL;
        $x = 0;

        $sql .= "SELECT A.CVE_ARTICULO IDART,A.CVE_FRACCION IDFRAC,A.CVE_INCISO IDINC,A.CVE_APARTADO IDAPA, ";
        $sql .= "AR.NOMBRE ARTICULO,F.NOMBRE FRACCION,I.DESCRIPCION INCISO,A.DESCRIPCION APARTADO,A.ACTIVO ";
        $sql .= "FROM APARTADOS A ";
        $sql .= "INNER JOIN ARTICULOS AR ON AR.CVE_ARTICULO = A.CVE_ARTICULO ";
        $sql .= "INNER JOIN FRACCIONES F ON F.CVE_FRACCION = A.CVE_FRACCION ";
        $sql .= "INNER JOIN INCISOS I ON I.CVE_INCISO = A.CVE_INCISO ";
        $sql .= "ORDER BY ARTICULO,IDFRAC,INCISO,APARTADO";
        $rst = UtilDB::ejecutaConsulta($sql);
        ?>

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
                                    <?php
                                    foreach ($rst as $row) {
                                        $x++;
                                        ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo($x) ?></td>
                                            <td style="text-align: center">
                                                <?php echo($row["ARTICULO"]); ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php echo($row["FRACCION"]); ?>
                                            </td>
                                            <td style="text-align: justify">
                                                <?php echo($row["INCISO"]); ?>
                                            </td>
                                            <td style="text-align: justify">
                                                <a href="javascript:recargaApartado('acciones.php?xAccion=recargaApartado','<?php echo($row["IDAPA"]); ?>');" class="liga"><?php echo($row["APARTADO"]); ?></a>
                                            </td>
                                            <td style="text-align: center">
                                                <img src="<?php echo($row["ACTIVO"] ? "../img/checkmark.png" : "../img/cerrar.png"); ?>" width="16" height="16" title="<?php echo($row["ACTIVO"] ? "Activo" : "Inactivo"); ?>" border="0">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $rst->closeCursor();
                                    $rst = NULL;
                                    $sql = "";
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                
        </div>

        <?php
    } elseif ($_POST["xAccion"] === "recargaApartado") {

        $cve = 0;

        if (isset($_POST["cve"])) {
            $cve = (int) $_POST["cve"];
        }

        $apaR = new Apartado($cve);

        $json = '{"xCveApartado":' . $apaR->getCveApartado() . ',"xCveArticulo":' . $apaR->getCveApartado()->getCveArticulo() . ',"xCveFraccion":' . $apaR->getCveFraccion()->getCveFraccion() . ',"xCveInciso":' . $apaR->getCveInciso()->getCveInciso() . ',"xDescripcion":"' . $apaR->getDescripcion() . '","xActivo":' . $apaR->getActivo() . '}';
        echo($json);
        return;
    } elseif ($_POST["xAccion"] === "cargaComboApartado") {

        $sql = "";
        $rst = NULL;

        $cveArt = 0;

        if (isset($_POST["cveArt"])) {
            $cveArt = (int) $_POST["cveArt"];
        }

        $cveFrac = 0;

        if (isset($_POST["cveFrac"])) {
            $cveFrac = (int) $_POST["cveFrac"];
        }

        $cveInc = 0;

        if (isset($_POST["cveInc"])) {
            $cveInc = (int) $_POST["cveInc"];
        }

        $cveApa = 0;

        if (isset($_POST["cveApa"])) {
            $cveApa = (int) $_POST["cveApa"];
        }

        $sql .= "SELECT A.CVE_ARTICULO IDART,A.CVE_FRACCION IDFRA,A.CVE_INCISO IDINC,A.cve_apartado,A.descripcion,A.ACTIVO ";
        $sql .= "FROM APARTADOS A ";
        $sql .= "WHERE A.CVE_ARTICULO = " . $cveArt . " ";
        $sql .= "AND A.CVE_FRACCION = " . $cveFrac . " ";
        $sql .= "AND CVE_INCISO = " . $cveInc . " ";
        $sql .= "ORDER BY A.cve_apartado";
        $rst = UtilDB::ejecutaConsulta($sql);

        $text = "";
        $text .= "<option value='0'>---------- SELECCIONE UNA OPCIÓN -----------</option>";
        if ($rst->rowCount() != 0) {
            foreach ($rst as $row) {
                $text .= "<option value=\"" . $row["cve_apartado"] . "\"" . ($row["cve_apartado"] == $cveApa ? " selected" : "") . ">" . $row["descripcion"] . "</option>";
            }
        } else {
            $text .= "<option value='0' selected>No existen apartados para este artículo.</option>";
        }

        echo($text);
        $rst->closeCursor();
        return;
    } elseif ($_POST["xAccion"] === "grabaTransparencia") {
        $cveDoc = 0;
        $xPagina = 0;

        if (isset($_POST["xPagina"])) {
            $xPagina = (int) $_POST["xPagina"];
        }

        try {
            $cveDoc = (int) $_POST["xCveDocumento"];
        } catch (Exception $e) {
            $cveDoc = 0;
        }
        $doc = new Documento($cveDoc);
        $doc->setCveDocumento((int) $_POST["xCveDocumento"]);
        $doc->setCveArticulo(new Articulo((int) $_POST["xCveArticuloV"]));
        $doc->setCveFraccion(new Fraccion($doc->getCveArticulo(),(int) $_POST["xCveFraccion"]));
        $doc->setCveInciso(new Inciso($doc->getCveArticulo(),$doc->getCveFraccion(),(int) $_POST["xCveInciso"]));
        $doc->setCveApartado(new Apartado($doc->getCveArticulo(),$doc->getCveFraccion(),$doc->getCveInciso(),(int) $_POST["xCveApartado"]));
        $doc->setAnio((int) $_POST["xCveAnio"]);
        $doc->setTrimestre((int) $_POST["xCveTrimestre"]);
        $doc->setNombre($_POST["xNombre"]);
        $fecha_act_doc = date_create($_POST["xFechaActualizacion"]);
        $doc->setFechaActualizacionDocumento(date_format($fecha_act_doc, 'Y-m-d H:i:s'));
        $doc->setRutaDocumento($_POST["xRuta"] === "---" ? "---" : substr($_POST["xRuta"], strlen($_SESSION["url_publica"]), strlen($_POST["xRuta"])));
        
        if ($xPagina == 1) {
            $doc->setAnexo(true);
        } else {
            $doc->setAnexo(false);
        }

        $doc->setCveUsuario(new Usuario((int) $_POST["xCveUsuario"]));
        $doc->setCveUsuario2(new Usuario((int) $_POST["xCveUsuario2"]));
        $fecha_fecha_modificacion = date_create($_POST["xFechaActualizacionR"]);
        $doc->setFechaModificacion(date_format($fecha_fecha_modificacion, 'Y-m-d H:i:s'));
        $doc->setActivo(isset($_POST["xActivo"]) ? true : false);
        $doc->grabar();

        
    } elseif ($_POST["xAccion"] === "muestraDoctos") { 

        $sql = "";
        $rst = NULL;
        $x = 0;
        $titulo = "";

        $xPagina = 0;

        if (isset($_POST["xPagina"])) {
            $xPagina = (int) $_POST["xPagina"];
        }

        if ($xPagina == 1) {
            $titulo = "Anexos";
        } else {
            $titulo = "Transparencia";
        }

        $sql .= "SELECT D.CVE_DOCUMENTO IDDOC,D.CVE_ARTICULO IDART,D.CVE_FRACCION IDFRAC,D.CVE_INCISO IDINC,D.CVE_APARTADO IDAPA, ";
        $sql .= "A.NOMBRE ARTICULO,F.NOMBRE FRACCION,D.ANIO,D.TRIMESTRE TRI, ";
        $sql .= "D.NOMBRE,DATE_FORMAT(D.FECHA_ACTUALIZACION_DOCUMENTO, '%d %m %Y') FECHA_ACT,D.RUTA_DOCUMENTO RUTA, ";
        $sql .= "D.ANEXO,D.CVE_USUARIO IDUSER,DATE_FORMAT(D.FECHA_REGISTRO,'%d %m %Y') FECHA_REG,D.CVE_USUARIO2 IDUSER2, ";
        $sql .= "DATE_FORMAT(D.FECHA_MODIFICACION,'%d %m %Y') FECHA_MOD,D.ACTIVO ";
        $sql .= "FROM DOCUMENTOS D ";
        $sql .= "INNER JOIN ARTICULOS A ON A.CVE_ARTICULO = D.CVE_ARTICULO ";
        $sql .= "INNER JOIN FRACCIONES F ON F.CVE_FRACCION = D.CVE_FRACCION ";
        if ($xPagina == 1) {
            $sql .= "WHERE D.ANEXO = 1 ";
        } else {
            $sql .= "WHERE D.ANEXO = 0 ";
        }
        if ($area != 1) {
            $sql .= "AND D.CVE_ARTICULO IN (SELECT cve_articulo FROM permisos WHERE activo = 1 AND cve_area = " . $area . " GROUP BY cve_articulo) ";
            $sql .= "AND D.CVE_FRACCION IN (SELECT cve_articulo FROM permisos WHERE activo = 1 AND cve_area = " . $area . " GROUP BY cve_articulo) ";
        }
        $sql .= " AND D.ANIO = " . $anio . " AND D.TRIMESTRE = " . $trimestre;
        $sql .= " ORDER BY ARTICULO,FRACCION,NOMBRE";
        //ChromePhp::log($sql);
        $rst = UtilDB::ejecutaConsulta($sql);
        ?>

        <script>

            $(document).ready(function () {
                $('#tablaDoctos').DataTable();
            });

        </script>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h5 class="text-center text-uppercase"><?php echo($titulo); ?></h5>
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
                                    <?php
                                    foreach ($rst as $row) {
                                        $x++;
                                        ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo($x); ?></td>
                                            <td style="text-align: center"><?php echo($row["ANIO"]); ?></td>
                                            <td style="text-align: center"><?php echo($trimestres[$row["TRI"] - 1]); ?></td>
                                            <td style="text-align: center">
                                                <a href="javascript:recargaDocto('acciones.php?xAccion=recargaDocto','<?php echo($row["IDDOC"]); ?>');" class="liga"><?php echo($row["ARTICULO"]); ?></a>
                                            </td>
                                            <td style="text-align: center">
                                                <?php echo($row["FRACCION"]); ?>
                                            </td>
                                            <td style="text-align: justify">
                                                <?php echo($row["NOMBRE"]); ?>
                                            </td>
                                            <td style="text-align: justify">
                                                <?php
                                                if ($row["RUTA"] === "") {
                                                    ?>
                                                    <a href="javascript:modalArchivos('<?php echo($row["IDDOC"]); ?>','<?php echo($row["NOMBRE"]); ?>');" class="liga">
                                                        <?php
                                                        if ($xPagina == 1) {
                                                            ?>
                                                            Subir documento
                                                            <?php
                                                        } else {
                                                            ?>
                                                            Subir archivo xls
                                                            <?php
                                                        }
                                                        ?>
                                                    </a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a href="<?php echo($_SESSION["url_publica"] . $row["RUTA"]); ?>" target="_blank"><?php echo($_SESSION["url_publica"] . $row["RUTA"]); ?></a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td style="text-align: center">

                                                <?php
                                                $extension = "";

                                                $i = strpos($row["RUTA"], ".");
                                                if ($i > 0) {
                                                    $extension = substr($row["RUTA"], $i + 1);
                                                }
                                                ?>

                                                <?php
                                                if ($extension === "xlsx" or $extension === "xls") {
                                                    ?>
                                                    <img src="../img/extensiones/File-Excel-icon.png" title="Excel">
                                                    <?php
                                                } elseif ($extension === "docx") {
                                                    ?>
                                                    <img src="../img/extensiones/Word-icon.png" title="Word">
                                                    <?php
                                                } elseif ($extension === "pdf") {
                                                    ?>
                                                    <img src="../img/extensiones/acrobat.png" title="Pdf">
                                                    <?php
                                                } elseif ($extension === "pptx") {
                                                    ?>
                                                    <img src="../img/extensiones/File-PowerPoint-icon.png" title="Power Point">
                                                    <?php
                                                } elseif ($extension === "zip") {
                                                    ?>
                                                    <img src="../img/extensiones/zipito.png" title="Zip">
                                                    <?php
                                                } elseif ($extension === "rar") {
                                                    ?>
                                                    <img src="../img/extensiones/Winrar-SZ-icon.png" title="Winrar">
                                                    <?php
                                                }
                                                ?>

                                            </td>
                                            <td style="text-align: center">
                                                <img src="<?php echo($row["ACTIVO"] ? "../img/checkmark.png" : "../img/cerrar.png"); ?>" width="16" height="16" title="<?php echo($row["ACTIVO"] ? "Activo" : "Inactivo"); ?>" border="0">
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $rst->closeCursor();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
        <?php
    } elseif ($_POST["xAccion"] === "recargaDocto") {

        $cve = 0;

        if (isset($_POST["cve"])) {
            $cve = (int) $_POST["cve"];
        }

        $docto = new Documento($cve);
        $ruta = "";

        if ($docto->getRutaDocumento() === "---") {
            $ruta = "";
        } else {
            $ruta = $_SESSION["url_publica"];
        }

        $json = '{"xCveDocumento":' . $docto->getCveDocumento() . ',"xCveApartado":' . $docto->getCveApartado()->getCveApartado() . ',"xCveArticulo":' . $docto->getCveArticulo()->getCveArticulo() . ',"xCveFraccion":' . $docto->getCveFraccion()->getCveFraccion() . ',"xCveInciso":' . $docto->getCveInciso()->getCveInciso() . ',"xCveUsuario":' . $docto->getCveUsuario()->getCveUsuario() . ',"xFechaActualizacion":"' . $docto->getFechaActualizacionDocumento() . '","xNombre":"' . $docto->getNombre() . '","xRuta":"' . $docto->getRutaDocumento() . '","xActivo":' . $docto->getActivo() . '}';
        echo($json);
        return;
    }
}
else
{
    ChromePhp::log("No existe xAccion");
}
?>