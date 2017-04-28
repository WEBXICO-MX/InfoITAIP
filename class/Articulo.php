<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');

class Articulo {

    private $cveArticulo;
    private $nombre;
    private $descripcion;
    private $activo;
    private $_existe;

    function __construct() {
        $this->limpiar();

        $args = func_get_args();
        $nargs = func_num_args();

        switch ($nargs) {
            case 1:
                self::__construct1($args[0]);
                break;
        }
    }

    function __construct1($xCveArticulo) {
        $this->limpiar();
        $this->cveArticulo = $xCveArticulo;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveArticulo = 0;
        $this->nombre = "";
        $this->descripcion = "";
        $this->activo = false;
        $this->_existe = false;
    }

    function getCveArticulo() {
        return $this->cveArticulo;
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

    function setCveArticulo($cveArticulo) {
        $this->cveArticulo = $cveArticulo;
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
            $this->cveArticulo = UtilDB::getSiguienteNumero("articulos", "cve_articulo");
            $sql = "INSERT INTO articulos VALUES($this->cveArticulo,'$this->nombre','$this->descripcion',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE articulos SET ";
            $sql .= "nombre = '$this->nombre',";
            $sql .= "descripcion = '$this->descripcion',";
            $sql .= "activo = $this->activo";
            $sql .= " WHERE cve_articulo = $this->cveArticulo";
            $count = UtilDB::ejecutaSQL($sql);
        }

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM articulos WHERE cve_articulo = $this->cveArticulo";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveArticulo = $row['cve_articulo'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

}
