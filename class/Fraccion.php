<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');
require_once('ChromePhp.php');

class Fraccion {

    /**
     * @var Articulo $cveArticulo Tipo Articulo
     */
    private $cveArticulo;
    private $cveFraccion;
    private $nombre;
    private $descripcion;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 2:
                self::__construct1($args[0], $args[1]);
                break;
        }
    }

    function __construct1(Articulo $xCveArticulo, $xCveFraccion) {
        $this->limpiar();
        $this->cveArticulo = $xCveArticulo;
        $this->cveFraccion = $xCveFraccion;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveArticulo = NULL;
        $this->cveFraccion = 0;
        $this->nombre = "";
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

    function getCveFraccion() {
        return $this->cveFraccion;
    }

    function getNombre() {
        return $this->nombre;
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

    function setCveFraccion($cveFraccion) {
        $this->cveFraccion = $cveFraccion;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
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
            $sql = "INSERT INTO fracciones VALUES(";
            $sql .= $this->cveArticulo->getCveArticulo().",";
            $sql .= "$this->cveFraccion,";
            $sql .= "'$this->nombre',";
            $sql .= "'$this->descripcion',";
            $sql .= "$this->activo";
            $sql .= ")";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE fracciones SET ";
            $sql .= "nombre = '$this->nombre',";
            $sql .= "descripcion = '$this->descripcion',";
            $sql .= "activo = $this->activo";
            $sql .= " WHERE cve_articulo = " . $this->cveArticulo->getCveArticulo() . " AND cve_fraccion = $this->cveFraccion";
            $count = UtilDB::ejecutaSQL($sql);
        }

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM fracciones WHERE cve_articulo = " . $this->cveArticulo->getCveArticulo() . " AND cve_fraccion = $this->cveFraccion";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveArticulo = new Articulo($row['cve_articulo']);
            $this->cveFraccion = $row['cve_fraccion'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

}
