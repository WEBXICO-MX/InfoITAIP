<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');
require_once('ChromePhp.php');

class Usuario {

    private $cveUsuario;

    /**
     * @var Area $cveArea Tipo Area
     */
    private $cveArea;
    private $nombreCompleto;
    private $login;
    private $password;
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

    function __construct1($xCveUsuario) {
        $this->limpiar();
        $this->cveUsuario = $xCveUsuario;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveUsuario = 0;
        $this->cveArea = NULL;
        $this->nombreCompleto = "";
        $this->login = '';
        $this->password = '';
        $this->activo = false;
        $this->_existe = false;
    }

    function getCveUsuario() {
        return $this->cveUsuario;
    }

    /**
     * @return Area Devuelve tipo Area
     */
    function getCveArea() {
        return $this->cveArea;
    }

    function getNombreCompleto() {
        return $this->nombreCompleto;
    }

    function getLogin() {
        return $this->login;
    }

    function getPassword() {
        return $this->password;
    }

    function getActivo() {
        return $this->activo;
    }

    function setCveUsuario($cveUsuario) {
        $this->cveUsuario = $cveUsuario;
    }

    function setCveArea(Area $cveArea) {
        $this->cveArea = $cveArea;
    }

    function setNombreCompleto($nombreCompleto) {
        $this->nombreCompleto = $nombreCompleto;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setActivo($activo) {
        $this->activo = $activo;
    }

    function get_existe() {
        return $this->_existe;
    }

    function grabar() {
        $sql = "";
        $count = 0;

        if (!$this->_existe) {
            $this->cveUsuario = UtilDB::getSiguienteNumero("usuarios", "cve_usuario");
            $sql = "INSERT INTO usuarios VALUES($this->cveUsuario," . $this->cveArea->getCveArea() . ",'$this->nombreCompleto','$this->login','$this->password',$this->activo)";
            $count = UtilDB::ejecutaSQL($sql);
            if ($count > 0) {
                $this->_existe = true;
            }
        } else {
            $sql = "UPDATE usuarios SET ";
            $sql .= "cve_area = " . $this->cveArea->getCveArea() . ",";
            $sql .= "nombre_completo = '$this->nombreCompleto',";
            $sql .= "login = '$this->login',";
            $sql .= "password = '$this->password',";
            $sql .= "activo = $this->activo";
            $sql .= " WHERE cve_usuario = $this->cveUsuario";
            $count = UtilDB::ejecutaSQL($sql);
        }

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM usuarios WHERE cve_usuario = $this->cveUsuario";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveUsuario = $row['cve_usuario'];
            $this->cveArea = new Area($row['cve_area']);
            $this->nombreCompleto = $row['nombre_completo'];
            $this->login = $row['login'];
            $this->password = $row['password'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

    function cargarLoginUsuario() {
        $sql = "SELECT * FROM usuarios WHERE login = '$this->login'";
        $rst = UtilDB::ejecutaConsulta($sql);
        foreach ($rst as $row) {
            $this->cveUsuario = $row['cve_usuario'];
            $this->cveArea = new Area($row['cve_area']);
            $this->nombreCompleto = $row['nombre_completo'];
            $this->login = $row['login'];
            $this->password = $row['password'];
            $this->activo = $row['activo'];
            $this->_existe = true;
        }
        $rst->closeCursor();
    }

}
