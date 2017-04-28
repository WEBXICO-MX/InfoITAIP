<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');

class HistorialCambioContrasena {

    private $cveHistorial;

    /**
     * @var Usuario $cveUsuario Tipo Usuario
     */
    private $cveUsuario;
    private $contrasenaNueva;
    private $contrasenaAnterior;
    private $fechaCambio;
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

    function __construct1($xHistorial) {
        $this->limpiar();
        $this->cveHistorial = $xHistorial;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveHistorial = NULL;
        $this->cveUsuario = NULL;
        $this->contrasenaNueva = "";
        $this->contrasenaAnterior = "";
        $this->fechaCambio = "";
        $this->_existe = false;
    }

    function getCveHistorial() {
        return $this->cveHistorial;
    }

    function getCveUsuario() {
        return $this->cveUsuario;
    }

    function getContrasenaNueva() {
        return $this->contrasenaNueva;
    }

    function getContrasenaAnterior() {
        return $this->contrasenaAnterior;
    }

    function getFechaCambio() {
        return $this->fechaCambio;
    }

    function setCveHistorial($cveHistorial) {
        $this->cveHistorial = $cveHistorial;
    }

    function setCveUsuario(Usuario $cveUsuario) {
        $this->cveUsuario = $cveUsuario;
    }

    function setContrasenaNueva($contrasenaNueva) {
        $this->contrasenaNueva = $contrasenaNueva;
    }

    function setContrasenaAnterior($contrasenaAnterior) {
        $this->contrasenaAnterior = $contrasenaAnterior;
    }

    function setFechaCambio($fechaCambio) {
        $this->fechaCambio = $fechaCambio;
    }

    function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cveHistorial = UtilDB::getSiguienteNumero("historial_cambio_contrasena", "cve_historial");
            $sql = "INSERT historial_cambio_contrasena VALUES(";
            $sql .= "$this->cveHistorial,";
            $sql .= "'$this->contrasenaNueva',";
            $sql .= "'$this->contrasenaAnterior',";
            $sql .= "NOW()";
            $sql .= ")";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } 

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM historial_cambio_contrasena WHERE cve_historial = " . $this->cveHistorial;
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveHistorial = $row['cve_historial'];
            $this->cveUsuario = new Usuario($row['cve_usuario']);
            $this->contrasenaNueva = $row['contrasena_nueva'];
            $this->contrasenaAnterior = $row['contrasena_anterior'];
            $this->fechaCambio = $row['fecha_cambio'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

}
