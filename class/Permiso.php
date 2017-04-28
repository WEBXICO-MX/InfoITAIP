<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');

class Permiso {

    /**
     * @var Area $cveArea Tipo Area
     */
    private $cveArea;

    /**
     * @var Articulo $cveArticulo Tipo Articulo
     */
    private $cveArticulo;

    /**
     * @var Fraccion $cveFraccion Tipo Fraccion
     */
    private $cveFraccion;

    /**
     * @var Usuario $cveUsuario Tipo Usuario
     */
    private $cveUsuario;
    private $fechaRegistro;

    /**
     * @var Usuario $cveUsuario2 Tipo Usuario
     */
    private $cveUsuario2;
    private $fechaModificacion;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 3:
                self::__construct1($args[0], $args[1], $args[2]);
                break;
        }
    }

    function __construct1(Area $xCveArea, Articulo $xCveArticulo, Fraccion $xCveFraccion) {
        $this->limpiar();
        $this->cveArea = $xCveArea;
        $this->cveArticulo = $xCveArticulo;
        $this->cvefraccion = $xCveFraccion;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveArea = NULL;
        $this->cveArticulo = NULL;
        $this->cveFraccion = NULL;
        $this->cveUsuario = NULL;
        $this->fechaRegistro = "";
        $this->cveUsuario2 = NULL;
        $this->fechaModificacion = "";
        $this->activo = false;
        $this->_existe = false;
    }

    /**
     * @return Area Devuelve tipo Area
     */
    function getCveArea() {
        return $this->cveArea;
    }

    /**
     * @return Articulo Devuelve tipo Articulo
     */
    function getCveArticulo() {
        return $this->cveArticulo;
    }

    /**
     * @return Fraccion Devuelve tipo Fraccion
     */
    function getCveFraccion() {
        return $this->cveFraccion;
    }

    /**
     * @return Usuario Devuelve tipo Usuario
     */
    function getCveUsuario() {
        return $this->cveUsuario;
    }

    function getFechaRegistro() {
        return $this->fechaRegistro;
    }

    /**
     * @return Usuario Devuelve tipo Usuario
     */
    function getCveUsuario2() {
        return $this->cveUsuario2;
    }

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function getActivo() {
        return $this->activo;
    }

    function setCveArea(Area $cveArea) {
        $this->cveArea = $cveArea;
    }

    function setCveArticulo(Articulo $cveArticulo) {
        $this->cveArticulo = $cveArticulo;
    }

    function setCveFraccion(Fraccion $cveFraccion) {
        $this->cveFraccion = $cveFraccion;
    }

    function setCveUsuario(Usuario $cveUsuario) {
        $this->cveUsuario = $cveUsuario;
    }

    function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = $fechaRegistro;
    }

    function setCveUsuario2(Usuario $cveUsuario2) {
        $this->cveUsuario2 = $cveUsuario2;
    }

    function setFechaModificacion($fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $sql = "INSERT INTO permisos VALUES(";
            $sql .= $this->cveArea->getCveArea() . ",";
            $sql .= $this->cveArticulo->getCveArticulo() . ",";
            $sql .= $this->cveFraccion->getCveFraccion() . ",";
            $sql .= $this->cveUsuario->getCveUsuario() . ",";
            $sql .= "NOW(),";
            $sql .= "NULL,";
            $sql .= "NULL,";
            $sql .= "$this->activo";
            $sql .= ")";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE permisos SET ";
            $sql .= "activo = $this->activo";
            $sql .= " WHERE cve_area = " . $this->cveArea->getCveArea() . " AND cve_articulo = " . $this->cveArticulo->getCveArticulo() . " AND cve_fraccion = " . $this->cveFraccion->getCveFraccion();
            $count = UtilDB::ejecutaSQL($sql);
        }

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM permisos WHERE cve_area = " . $this->cveArea->getCveArea() . " AND cve_articulo = " + $this->cveArticulo->getCveArticulo() . " AND cve_fraccion = " . $this->cveFraccion->getCveFraccion();
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveArea = new Area($row['cve_area']);
            $this->cveArticulo = new Articulo($row['cve_articulo']);
            $this->cveFraccion = new Fraccion($this->cveArticulo, $row['cve_fraccion']);
            $this->cveUsuario = new Usuario($row['cve_usuario']);
            $this->fechaRegistro = $row['fecha_registro'];
            $this->cveUsuario2 = new Usuario($row['cve_usuario2']);
            $this->fechaModificacion = $row['fecha_modificacion'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

}
