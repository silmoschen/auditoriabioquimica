<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
//implementamos la clase empleado
class cMedicos {
//constructor
    var $codos;
    var $idprof;
    var $nombre;
    var $matricula;
    var $libro;
    var $folio;
    var $estado;
    var $utiles;
    var $sql;
    var $res;

    function cMedicos() {
        $this->utiles = new cUtiles;
    }

    // inserta tupla
    function crear($codos, $idprof, $nombre, $matricula, $libro, $folio, $estado) {
        $query = "INSERT INTO medicos (codos, idprof, nombre, matricula, libro, folio, estado) VALUES
              ('$codos', '$idprof', '$nombre', '$matricula', '$libro', '$folio', '$estado')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $idprof) {
        $query = "DELETE FROM medicos WHERE codos = '$codos' AND idprof = '$idprof'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codos, $idprof, $nombre, $matricula, $libro, $folio, $estado) {
        $query = "UPDATE medicos SET nombre = " . '"' . $nombre . '"' . ', matricula = ' . '"' . $matricula . '"' .
            ', libro = ' . '"' . $libro . '"' . ', folio = ' . '"' . $folio . '"' . ', estado = ' . '"' . $estado . '"' .
            " WHERE codos = '$codos' and idprof = '$idprof'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codos, $idprof) {
        $nf = false;
        $query = "SELECT * FROM medicos WHERE codos = '$codos' and idprof = '$idprof'";
        $resultado=mysql_query($query);
        while($fila=mysql_fetch_array($resultado)) {
            $this->codos     = $fila['codos'];
            $this->idprof    = $fila['idprof'];
            $this->nombre    = $fila['nombre'];
            $this->matricula = $fila['matricula'];
            $this->libro     = $fila['libro'];
            $this->folio     = $fila['folio'];
            $this->estado    = $fila['estado'];
            $nf              = true;
        }
        return $nf;
    }

    function CopiarMedicos($codos1, $codos2) {
        $cant = 0;
        $query = "SELECT * FROM medicos WHERE codos = '$codos1'";
        $resultado=mysql_query($query);
        while($f=mysql_fetch_array($resultado)) {
            if ($this->getObject($codos2, $f['idprof'])) {
                $this->actualizar($codos2, $f['idprof'], $f['nombre'], $f['matricula'], $f['libro'], $f['folio'], $f['estado']);
            } else {
                $this->crear($codos2, $f['idprof'], $f['nombre'], $f['matricula'], $f['libro'], $f['folio'], $f['estado']);
            }
            $cant = $cant + 1;
        }

        echo 'Trabajo Realizado :: ' . $cant . ' Registros Procesados';
    }

    function Exportar($med, $codos, $idprof) {
        $this->getObject($codos, $idprof);
        $linea = $this->utiles->StringLongitudFija($this->codos,  6) .
            $this->utiles->StringLongitudFija($this->idprof,  20) .
            $this->utiles->StringLongitudFija($this->libro,  10) .
            $this->utiles->StringLongitudFija($this->folio,  10) .
            $this->utiles->StringLongitudFija($this->matricula,  15) .
            $this->utiles->StringLongitudFija($this->nombre,  100) .
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

    function getEstado() {
        return $this->estado;
    }

    function getMedicos($codos, $nombre) {
        $query = "SELECT * FROM medicos WHERE codos = '$codos' AND estado <> '" . "S" . "'  AND nombre LIKE '" . $nombre . "%'" . " ORDER BY nombre";        
        //echo $query;
        $this->res=mysql_query($query);
        return $this->res;
    }

    function getListaMedicos($codos, $filtro, $RegistrosAEmpezar, $RegistrosAMostrar) {
        if ($filtro == '' or $filtro == 'undefined') {
            $query = "SELECT * FROM medicos WHERE codos = '$codos' ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        } else {
            $query = "SELECT * FROM medicos WHERE codos = '$codos' AND nombre LIKE '$filtro%' ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }

        $this->res=mysql_query($query);
        return $this->res;
    }

    function Nuevo($codos) {
        $n = 0;
        $query = "SELECT MAX(idprof) AS newID FROM medicos WHERE codos = '$codos' AND idprof < '99990'";
        $resultado=mysql_query($query);
        while($fila=mysql_fetch_array($resultado)) {
            $n = $fila['newID'];
        }
        $n = $n + 1;

        return $this->utiles->LlenarIzquierda($n, 5, '0');
    }

    function getMedico($codos, $libro, $folio, $matricula) {
        $nf = false;
        $query = "SELECT idprof FROM medicos WHERE codos = '$codos' and libro = '$libro' and folio = '$folio' and matricula = '$matricula'";
        $resultado=mysql_query($query);
        if (mysql_num_rows($resultado) == 0) {
            $nf = false;
        } else {
            $nf = true;
        }
        return $nf;
    }

    function getSQL() {
        return $this->sql;
    }

    function repara() {
        $query = "SELECT * FROM medicos";
        $resultado=mysql_query($query);
        while($fila=mysql_fetch_array($resultado)) {
            $this->codos     = $fila['codos'];
            $this->idprof    = $fila['idprof'];
            $this->nombre    = $fila['nombre'];
            $this->libro     = $this->utiles->LlenarIzquierda($fila['libro'], 2, '0');
            $this->folio     = $this->utiles->LlenarIzquierda($fila['folio'], 3, '0');
            $this->matricula = $this->utiles->LlenarIzquierda($fila['matricula'], 5, '0');
            $this->actualizar($this->codos, $this->idprof, $this->nombre, $this->matricula, $this->libro, $this->folio);
        }

    }

}

?>
