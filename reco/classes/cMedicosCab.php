<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');

//implementamos la clase empleado
class cMedicosCab {

//constructor
    var $codos;
    var $idprof;
    var $nomnre;
    var $matricula;
    var $libro;
    var $folio;
    var $capitas;
    var $activo;
    var $utiles;
    var $sql;
    var $res;

    function cMedicosCab() {
        $this->utiles = new cUtiles;
    }

    // inserta tupla
    function crear($codos, $idprof, $capitas, $activo) {
        $f = false;
        $query = "SELECT * FROM medicos_cab WHERE codos = '$codos' and idprof = '$idprof'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $f = true;
        }

        if ($f) {
            $this->actualizar($codos, $idprof, $capitas, $activo);
            return true;
        }

        $query = "INSERT INTO medicos_cab (codos, idprof, capitas, activo) VALUES
              ('$codos', '$idprof', $capitas, '$activo')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $idprof) {
        $query = "DELETE FROM medicos_cab WHERE codos = '$codos' AND idprof = '$idprof'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codos, $idprof, $capitas, $activo) {
        $query = "UPDATE medicos_cab SET capitas = $capitas, activo = '$activo' " .
                "WHERE codos = '$codos' and idprof = '$idprof'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizarEstado($codos, $idprof, $activo) {
        $query = "UPDATE medicos_cab SET activo = '$activo' " .
                "WHERE codos = '$codos' and idprof = '$idprof'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codos, $idprof) {
        $nf = false;
        $query = "SELECT * FROM medicos_cab WHERE codos = '$codos' and idprof = '$idprof'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $medico = new cMedicos();
            $medico->getObject($codos, $idprof);
            $this->codos = $fila['codos'];
            $this->idprof = $fila['idprof'];
            $this->nombre = $medico->getNombre();
            $this->matricula = $medico->getMatricula();
            $this->libro = $medico->getLibro();
            $this->folio = $medico->getFolio();
            $this->capitas = $fila['capitas'];
            $this->activo = $fila['activo'];
            $nf = true;
        }
        return $nf;
    }

    function Exportar($med, $codos, $idprof) {
        $this->getObject($codos, $idprof);
        $linea = $this->utiles->StringLongitudFija($this->codos, 6) .
                $this->utiles->StringLongitudFija($this->idprof, 20) .
                $this->utiles->StringLongitudFija($this->libro, 10) .
                $this->utiles->StringLongitudFija($this->folio, 10) .
                $this->utiles->StringLongitudFija($this->matricula, 15) .
                $this->utiles->StringLongitudFija($this->nombre, 100) .
                $this->utiles->StringLongitudFija($this->capitas, 15) .
                $this->utiles->StringLongitudFija($this->activo, 2) .                
                " \r\n";
        fwrite($med, $linea);
    }

    function getCodos() {
        return $this->codos;
    }

    function getIdprof() {
        return $this->idprof;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getMatricula() {
        return $this->matricula;
    }

    function getLibro() {
        return $this->libro;
    }

    function getFolio() {
        return $this->folio;
    }

    function getCapitas() {
        return $this->capitas;
    }

    function getActivo() {
        return $this->activo;
    }

    function getMedicos($codos, $nombre) {
        $query = "SELECT medicos.idprof, medicos.codos, medicos.nombre, medicos.libro, medicos.folio, medicos.matricula FROM " .
                "medicos_cab, medicos WHERE medicos_cab.codos = '$codos' AND medicos_cab.idprof = medicos.idprof AND medicos.codos = medicos_cab.codos AND medicos_cab.activo <> 'S' " .
                "AND medicos.nombre LIKE '" . $nombre . "%'" . " ORDER BY nombre";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getListaMedicos($codos, $filtro, $RegistrosAEmpezar, $RegistrosAMostrar, $t_filtro) {
        if ($filtro == '' or $filtro == 'undefined') {
            $query = "SELECT medicos_cab.*, medicos.nombre FROM medicos_cab, medicos WHERE medicos_cab.codos = '$codos' AND medicos_cab.codos = medicos.codos AND medicos_cab.idprof = medicos.idprof ORDER BY medicos.nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        } else {
            $query = "SELECT * FROM medicos_cab WHERE codos = '$codos' AND idprof = '$filtro' ORDER BY codos LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        if ($t_filtro == '5') {
            $query = "SELECT medicos_cab.*, medicos.nombre FROM medicos_cab, medicos WHERE medicos_cab.codos = medicos.codos AND medicos_cab.idprof = medicos.idprof AND medicos_cab.codos = '$codos' AND medicos.nombre LIKE '$filtro%' LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        $this->res = mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

}

?>