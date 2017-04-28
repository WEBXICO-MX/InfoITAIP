<?php

/**
 *
 * @author Roberto Eder Weiss Juárez
 * @see {@link http://webxico.blogspot.mx/}
 */
require_once('UtilDB.php');

class Documento {

    private $cveDocumento;

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

    /**
     * @var Apartado $cveInciso Tipo Apartado
     */
    private $cveApartado;
    private $anio;
    private $trimestre;
    private $nombre;
    private $fechaActualizacionDocumento;
    private $rutaDocumento;
    private $anexo;

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
            case 1:
                self::__construct1($args[0]);
                break;
        }
    }

    function __construct1($xCveDocumento) {
        $this->limpiar();
        $this->cveDocumento = $xCveDocumento;
        $this->cargar();
    }

    private function limpiar() {
        $this->cveDocumento = 0;
        $this->cveArticulo = NULL;
        $this->cveFraccion = NULL;
        $this->cveInciso = NULL;
        $this->cveApartado = NULL;
        $this->anio = 0;
        $this->trimestre = 0;
        $this->nombre = "";
        $this->fechaActualizacionDocumento = "";
        $this->rutaDocumento = "";
        $this->anexo = false;
        $this->cveUsuario = NULL;
        $this->fechaRegistro = "";
        $this->cveUsuario2 = NULL;
        $this->fechaModificacion = "";
        $this->activo = false;
        $this->_existe = false;
    }

    function getCveDocumento() {
        return $this->cveDocumento;
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

    /**
     * @return Apartado Devuelve tipo Apartado
     */
    function getCveApartado() {
        return $this->cveApartado;
    }

    function getAnio() {
        return $this->anio;
    }

    function getTrimestre() {
        return $this->trimestre;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getFechaActualizacionDocumento() {
        return $this->fechaActualizacionDocumento;
    }

    function getRutaDocumento() {
        return $this->rutaDocumento;
    }

    function getAnexo() {
        return $this->anexo;
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

    function setCveDocumento($cveDocumento) {
        $this->cveDocumento = $cveDocumento;
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

    function setCveApartado(Apartado $cveApartado) {
        $this->cveApartado = $cveApartado;
    }

    function setAnio($anio) {
        $this->anio = $anio;
    }

    function setTrimestre($trimestre) {
        $this->trimestre = $trimestre;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setFechaActualizacionDocumento($fechaActualizacionDocumento) {
        $this->fechaActualizacionDocumento = $fechaActualizacionDocumento;
    }

    function setRutaDocumento($rutaDocumento) {
        $this->rutaDocumento = $rutaDocumento;
    }

    function setAnexo($anexo) {
        $this->anexo = $anexo;
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
            $this->cveDocumento = UtilDB::getSiguienteNumero("documentos", "cve_documento");
            $sql = "INSERT INTO documentos VALUES(";
            $sql .= "$this->cveDocumento,";
            $sql .= $this->cveArticulo->getCveArticulo() . ",";
            $sql .= $this->cveFraccion->getCveFraccion() . ",";
            $sql .= $this->cveInciso->getCveInciso() . ",";
            $sql .= $this->cveApartado->getCveApartado() . ",";
            $sql .= "$this->anio,";
            $sql .= "$this->trimestre,";
            $sql .= "'$this->nombre',";
            $sql .= "'$this->fechaActualizacionDocumento',";
            $sql .= "NULL,";
            $sql .= "$this->anexo,";
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
            $sql = "UPDATE documentos SET ";
            $sql .= "cve_articulo = ".$this->cveArticulo->getCveArticulo() . ",";
            $sql .= "cve_fraccion = ".$this->cveFraccion->getCveFraccion() . ",";
            $sql .= "cve_inciso = ".$this->cveInciso->getCveInciso() . ",";
            $sql .= "cve_apartad = ".$this->cveApartado->getCveApartado() . ",";
            $sql .= "anio = $this->anio,";
            $sql .= "trimestre = $this->trimestre,";
            $sql .= "nombre = '$this->nombre',";
            $sql .= "fecha_actualizacion_documento = '$this->fechaActualizacionDocumento',";
            $sql .= "anexo = $this->anexo,";
            $sql .= "cve_usuario2 = ".$this->cveUsuario->getCveUsuario() . ",";
            $sql .= "fecha_modificacion = NOW(),";
            $sql .= "activo = $this->activo";            
            $sql .= " WHERE cve_documento = $this->cveDocumento";
            $count = UtilDB::ejecutaSQL($sql);
        }

        return $count;
    }

    function cargar() {
        $sql = "SELECT * FROM documentos WHERE cve_documento = $this->cveDocumento";
        $rst = UtilDB::ejecutaConsulta($sql);

        foreach ($rst as $row) {
            $this->cveArticulo = new Articulo($row['cve_articulo']);
            $this->cveFraccion = new Fraccion($this->cveArticulo, $row['cve_fraccion']);
            $this->cveInciso = new Inciso($this->cveArticulo, $this->cveFraccion, $row['cve_inciso']);
            $this->cveApartado = new Apartado($this->cveArticulo, $this->cveFraccion, $this->cveInciso, $row['cve_apartado']);
            $this->anio = $row['anio'];
            $this->trimestre = $row['trimestre'];
            $this->nombre = $row['nombre'];
            $this->fechaActualizacionDocumento = $row['fecha_actualizacion_documento'];
            $this->rutaDocumento = $row['ruta_documento'];
            $this->anexo = $row['anexo'];
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
