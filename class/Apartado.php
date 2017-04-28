<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');

class Apartado {

    /**
     * @var Articulo $cveArticulo Tipo Articulo
     */
    private $cveArticulo;

    /**
     * @var Fraccion $cveFraccion Tipo Fraccion
     */
    private $cveFraccion;

    /**
     * @var Inciso $cveInciso Tipo Inciso
     */
    private $cveInciso;
    private $cveApartado;
    private $descripcion;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 4:
                self::__construct1($args[0], $args[1], $args[2], $args[3]);
                break;
        }
    }

    function __construct1(Articulo $xCveArticulo, Fraccion $xCveFraccion, Inciso $xCveInciso, $xCveApartado) {
        $this->limpiar();
        $this->cveArticulo = $xCveArticulo;
        $this->cvefraccion = $xCveFraccion;
        $this->cveInciso = $xCveInciso;
        $this->cveInciso = $xCveApartado;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveArticulo = NULL;
        $this->cveFraccion = NULL;
        $this->cveInciso = NULL;
        $this->cveApartado = 0;
        $this->descripcion = "";
        $this->activo = false;
        $this->_existe = false;
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
     * @return Inciso Devuelve tipo Inciso
     */
    function getCveInciso() {
        return $this->cveInciso;
    }

    function getCveApartado() {
        return $this->cveApartado;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getActivo() {
        return $this->activo;
    }

    function setCveArticulo(Articulo $cveArticulo) {
        $this->cveArticulo = $cveArticulo;
    }

    function setCveFraccion(Fraccion $cveFraccion) {
        $this->cveFraccion = $cveFraccion;
    }

    function setCveInciso(Inciso $cveInciso) {
        $this->cveInciso = $cveInciso;
    }

    function setCveApartado($cveApartado) {
        $this->cveApartado = $cveApartado;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $sql = "INSERT INTO apartados VALUES(";
            $sql .= $this->cveArticulo->getCveArticulo() . ",";
            $sql .= $this->cveFraccion->getCveFraccion() . ",";
            $sql .= $this->cveInciso->getCveInciso() . ",";
            $sql .= "$this->cveApartado,";
            $sql .= "'$this->descripcion',";
            $sql .= "$this->activo";
            $sql .= ")";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE apartados SET ";
            $sql .= "descripcion = '$this->descripcion',";
            $sql .= "activo = $this->activo";
            $sql .= " WHERE cve_articulo = " . $this->cveArticulo->getCveArticulo() . " AND cve_fraccion = " . $this->cveFraccion->getCveFraccion() . " AND cve_inciso = " + $this->cveInciso->getCveInciso() . " AND cve_apartado = $this->cveApartado";
            $count = UtilDB::ejecutaSQL($sql);
        }

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM apartados WHERE cve_articulo = " . $this->cveArticulo->getCveArticulo() . " AND cve_fraccion = " + $this->cveFraccion->getCveFraccion() . " AND cve_inciso = " . $this->cveInciso->getCveInciso() . " AND cve_apartado = $this->cveApartado";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveArticulo = new Articulo($row['cve_articulo']);
            $this->cveFraccion = new Fraccion($this->cveArticulo, $row['cve_fraccion']);
            $this->cveInciso = new Inciso($this->cveArticulo, $this->cveFraccion, $row['cve_inciso']);
            $this->cveApartado = $row['cve_apartado'];
            $this->descripcion = $row['descripcion'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

}
