<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

//implementamos la clase empleado
class cAfiliados {

    //constructor
    var $codos;
    var $nrodoc;
    var $nombre;
    var $direccion;
    var $observacion;
    var $fechanac;
    var $depto;
    var $diferido;
    var $id_beneficio;
    var $id_parentesco;
    var $sexo;
    var $tipo_doc;
    var $inactivo;
    var $retiva;
    var $retiene_iva;
    var $idos;
    var $sql;
    var $res;
    var $utiles;
    var $coseguro1;
    var $coseguro2;
    var $coseguro3;
    var $numerodoc;

    function cAfiliados() {
        $this->utiles = new cUtiles();
        $coseguro1 = 0;
        $coseguro2 = 0;
        $coseguro3 = 0;
    }

    // inserta tupla
    function crear($codos, $nrodoc, $nombre, $observac, $fechanac, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc) {
        $query = "INSERT INTO bioqafil (codos, nrodoc, nombre, observacion, fechanac, direccion, id_beneficio, id_perentesco, sexo, tipo_doc) VALUES ('$codos', '$nrodoc', '$nombre', '$observac', '$fechanac', '$direccion', '$id_beneficio', '$id_parentesco', '$sexo', '$tipo_doc')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function crear1($codos, $nrodoc, $nombre, $observac, $fechanac, $depto, $diferido, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc) {
        $query = "INSERT INTO bioqafil (codos, nrodoc, nombre, observacion, fechanac, depto, diferido, direccion, id_beneficio, id_parentesco, sexo, tipo_doc) VALUES ('$codos', '$nrodoc', '$nombre', '$observac', '$fechanac', '$depto', '$diferido', '$direccion', '$id_beneficio', '$id_parentesco', '$sexo', '$tipo_doc')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function crear2($codos, $nrodoc, $nombre, $observac, $fechanac, $depto, $diferido, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc, $retiva, $idos) {
        $n = str_replace("'", " ", $nombre);
        $query = "INSERT INTO bioqafil (codos, nrodoc, nombre, observacion, fechanac, depto, diferido, direccion, id_beneficio, id_parentesco, sexo, tipo_doc, retiva, idos, inactivo) VALUES ('$codos', '$nrodoc', '$n', '$observac', '$fechanac', '$depto', '$diferido', '$direccion', '$id_beneficio', '$id_parentesco', '$sexo', '$tipo_doc', '$retiva', '$idos', 'N')";
        //echo $query;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function crear3($codos, $nrodoc, $nombre, $observac, $fechanac, $depto, $diferido, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc, $retiva, $idos, $numerodoc) {
        $n = str_replace("'", " ", $nombre);
        $query = "INSERT INTO bioqafil (codos, nrodoc, nombre, observacion, fechanac, depto, diferido, direccion, id_beneficio, id_parentesco, sexo, tipo_doc, retiva, idos, inactivo, numerodoc) VALUES ('$codos', '$nrodoc', '$n', '$observac', '$fechanac', '$depto', '$diferido', '$direccion', '$id_beneficio', '$id_parentesco', '$sexo', '$tipo_doc', '$retiva', '$idos', 'N', '$numerodoc')";
        //echo $query;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $nrodoc) {
        $query = "DELETE FROM bioqafil WHERE codos = '$codos' AND nrodoc = '$nrodoc'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codos, $nrodoc, $nombre, $observac, $fechanac, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc, $inactivo) {
        $query = "UPDATE bioqafil SET nombre = '$nombre', observacion = '$observac' , fechanac = '$fechanac', direccion = '$direccion', id_beneficio = '$id_beneficio', id_parentesco = '$id_parentesco', sexo = '$sexo', tipo_doc = '$tipo_doc', inactivo = '$inactivo' WHERE codos = '$codos' AND nrodoc = '$nrodoc'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar2($codos, $nrodoc, $nombre, $observac, $fechanac, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc, $inactivo, $retiva, $idos) {
        $n = str_replace("'", " ", $nombre);
        $query = "UPDATE bioqafil SET nombre = '$n', observacion = '$observac' , fechanac = '$fechanac', direccion = '$direccion', id_beneficio = '$id_beneficio', id_parentesco = '$id_parentesco', sexo = '$sexo', tipo_doc = '$tipo_doc', inactivo = '$inactivo', retiva = '$retiva', idos = '$idos' WHERE codos = '$codos' AND nrodoc = '$nrodoc'";
        $this->sql = $query;
        //echo $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar3($codos, $nrodoc, $nombre, $observac, $fechanac, $direccion, $id_beneficio, $id_parentesco, $sexo, $tipo_doc, $inactivo, $retiva, $idos, $numerodoc) {
        $n = str_replace("'", " ", $nombre);
        $query = "UPDATE bioqafil SET nombre = '$n', observacion = '$observac' , fechanac = '$fechanac', direccion = '$direccion', id_beneficio = '$id_beneficio', id_parentesco = '$id_parentesco', sexo = '$sexo', tipo_doc = '$tipo_doc', inactivo = '$inactivo', " .
                "retiva = '$retiva', idos = '$idos', numerodoc = '$numerodoc' WHERE codos = '$codos' AND nrodoc = '$nrodoc'";
        $this->sql = $query;
        //echo $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codos, $nrodoc) {
        $find = false;
        $this->retiene_iva = null;
        $this->diferido = 'N';
        $this->nombre = '*** Inexistente ***';
        $query = "SELECT codos, nrodoc, nombre, observacion, fechanac, diferido, depto, direccion, id_parentesco, id_beneficio, sexo, tipo_doc, inactivo, retiva, idos, numerodoc FROM bioqafil WHERE codos = '$codos' AND nrodoc = '$nrodoc'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codos = $fila['codos'];
            $this->nrodoc = $fila['nrodoc'];
            $this->nombre = substr($fila['nombre'], 0, 30);
            $this->observacion = $fila['observacion'];
            $this->fechanac = $fila['fechanac'];
            $this->diferido = $fila['diferido'];
            $this->depto = $fila['depto'];
            $this->direccion = $fila['direccion'];
            $this->id_parentesco = $fila['id_parentesco'];
            $this->id_beneficio = $fila['id_beneficio'];
            $this->sexo = $fila['sexo'];
            $this->tipo_doc = $fila['tipo_doc'];
            $this->inactivo = $fila['inactivo'];
            $this->retiva = $fila['retiva'];
            $this->retiene_iva = $fila['retiva'];
            $this->idos = $fila['idos'];
            $this->numerodoc = $fila['numerodoc'];
            $find = true;
        }
        if ($this->diferido == 'N') {
            $this->diferido = 'N';
        }
        if ($this->retiva != 'N')
            $this->retiva = 'S';
        return $find;
    }

    function getCodos() {
        return $this->codos;
    }

    function getNrodoc() {
        return $this->nrodoc;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getObservacion() {
        return $this->observacion;
    }

    function getFechanac() {
        return $this->fechanac;
    }

    function getDepto() {
        return $this->depto;
    }

    function getDiferido() {
        return $this->diferido;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getId_beneficio() {
        return $this->id_beneficio;
    }

    function getId_parentesco() {
        return $this->id_parentesco;
    }

    function getSexo() {
        return $this->sexo;
    }

    function getTipo_doc() {
        return $this->tipo_doc;
    }

    function getRetiva() {
        return $this->retiva;
    }

    function getIdOS() {
        return $this->idos;
    }

    function getNumerodoc() {
        return $this->numerodoc;
    }

    function getAfiliados($codos) {
        $query = "SELECT * FROM afiliados WHERE codos = '$codos'";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

    function Exportar($archivo, $codos, $nrodoc) {
        $query = "SELECT * FROM bioqafil
              WHERE codos = '$codos' AND nrodoc = '$nrodoc'";
        $result = mysql_query($query);
        while ($filadet = mysql_fetch_array($result)) {
            $n = $filadet['nrodoc'];
            $ndoc = $n;
            //if (substr($n, 0, 7) == '8000060' || substr($n, 0, 7) == '8000061') $ndoc = substr ($n, 7, 11);

            if (substr($n, 0, 7) == '8000060' || substr($n, 0, 7) == '8000061' || substr($n, 0, 7) == '8000062' || substr($n, 0, 7) == '8000063' || substr($n, 0, 7) == '8000064' || substr($n, 0, 7) == '8000065' || substr($n, 0, 7) == '8000066')
                $ndoc = substr($n, 7, 11);

            $lineadet = $this->utiles->StringLongitudFija($filadet['codos'], 7) .
                    //$this->utiles->StringLongitudFija($filadet['nrodoc'], 15) .
                    $this->utiles->StringLongitudFija($ndoc, 15) .
                    $this->utiles->StringLongitudFija($filadet['nombre'], 50) .
                    $this->utiles->StringLongitudFija($filadet['observacion'], 55) .
                    $this->utiles->StringLongitudFija($filadet['fechanc'], 10) .
                    $this->utiles->StringLongitudFija($filadet['depto'], 2) .
                    $this->utiles->StringLongitudFija($filadet['diferido'], 1) .
                    $this->utiles->StringLongitudFija($filadet['retiva'], 1) .
                    " \r\n";

            fwrite($archivo, $lineadet);
        }
    }

    function getConsultaAfiliados($codos, $filtro, $tipof, $RegistrosAEmpezar, $RegistrosAMostrar) {
        if ($filtro == '' or $filtro == 'undefined') {
            $this->res = mysql_query("SELECT codos, nrodoc, nombre FROM bioqafil WHERE codos = '$codos' ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
        } else {
            if ($tipof == '1') {
                $this->res = mysql_query("SELECT codos, nrodoc, nombre, id_beneficio, retiva FROM bioqafil WHERE codos = '$codos' AND nombre LIKE '$filtro%' ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
            }
            if ($tipof == '2') {
                $this->res = mysql_query("SELECT codos, nrodoc, nombre FROM bioqafil WHERE codos = '$codos' AND nrodoc = '$filtro' ORDER BY nrodoc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
            }
            if ($tipof == '3') {
                $this->res = mysql_query("SELECT codos, nrodoc, nombre FROM bioqafil WHERE codos = '$codos' AND id_beneficio = '$filtro' ORDER BY nrodoc LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
            }
            if ($tipof == '4') {
                $this->res = mysql_query("SELECT codos, nrodoc, nombre, id_beneficio, retiva FROM bioqafil WHERE codos = '$codos' AND nrodoc LIKE '$filtro' ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
            }
            if ($tipof == '5') {
                $this->res = mysql_query("SELECT * FROM bioqafil WHERE codos = '$codos' AND nrodoc = '$filtro'");
            }
        }

        return $this->res;
    }

    function verificarTipoDoc($tipodoc) {
        $find = false;
        $query = "SELECT tipo_doc FROM bioqafil WHERE tipo_doc = '$tipodoc'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $find = true;
            break;
        }
        return $find;
    }

    function updateCoseguro($codos, $nrodoc, $coseguro1, $coseguro2, $coseguro3) {
        $find = false;
        $query = "SELECT codos FROM bioqafil_plan WHERE codos = '$codos' and nrodoc = '$nrodoc'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $find = true;
            break;
        }

        if ($find == true) {
            $query = "UPDATE bioqafil_plan SET coseguro1 = $coseguro1, coseguro2 = $coseguro2, coseguro3 = $coseguro3  WHERE codos = '$codos' and nrodoc = '$nrodoc'";
        } else {
            $query = "INSERT INTO bioqafil_plan (codos, nrodoc, coseguro1, coseguro2, coseguro3) VALUES ('$codos', '$nrodoc', $coseguro1, $coseguro2, $coseguro3)";
        }

        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function registrarOrden($nrodoc, $fecha, $codos) {
        $fp = $this->utiles->getFechaAAAAMMDD($fecha);
        $query = "INSERT INTO auditoria_pac_diarios (nrodoc, fecha, codos) VALUES ('$nrodoc', '$fp', '$codos')";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getOrden($nrodoc, $fecha, $codos) {
        $find = false;
        $query = "SELECT nrodoc FROM auditoria_pac_diarios WHERE nrodoc = '$nrodoc' and codos = '$codos' and fecha = '$fecha'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $find = true;
            break;
        }

        return $find;
    }

    function getCoseguro($codos, $nrodoc) {
        $find = false;
        $query = "SELECT coseguro1, coseguro2, coseguro3 FROM bioqafil_plan WHERE codos = '$codos' and nrodoc = '$nrodoc'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->coseguro1 = $fila['coseguro1'];
            $this->coseguro2 = $fila['coseguro2'];
            $this->coseguro3 = $fila['coseguro3'];
            break;
        }
    }

}

?>
