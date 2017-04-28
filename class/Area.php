<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');

class Area {

    private $cveArea;
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

    function __construct1($xCveArea) {
        $this->limpiar();
        $this->cveArea = $xCveArea;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveArea = 0;
        $this->descripcion = "";
        $this->activo = false;
        $this->_existe = false;
    }

    function getCveArea() {
        return $this->cveArea;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getActivo() {
        return $this->activo;
    }

    function setCveArea($cveArea) {
        $this->cveArea = $cveArea;
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
            $this->cveArea = UtilDB::getSiguienteNumero("areas", "cve_area");
            $sql = "INSERT INTO areas VALUES($this->cveArea,'$this->descripcion',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE areas SET ";
            $sql .= "descripcion = '$this->descripcion',";
            $sql .= "activo = $this->activo";
            $sql .= " WHERE cve_area = $this->cveArea";
            $count = UtilDB::ejecutaSQL($sql);
        }

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM areas WHERE cve_area = $this->cveArea";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveArea = $row['cve_area'];
            $this->descripcion = $row['descripcion'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

}
