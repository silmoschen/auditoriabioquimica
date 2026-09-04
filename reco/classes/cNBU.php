<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cNBU {

    //constructor
    var $codigo;
    var $descrip;
    var $unidad;
    var $tramo;
    var $inactivo;
    var $nivel;
    var $sql;
    var $res;

    function cNBU() {
        
    }

    // inserta tupla
    function crear($codigo, $descrip, $unidad, $tramo, $estado, $nivel) {
        $query = "INSERT INTO nbu (codigo, descrip, unidad, tramo, estado, nivel) VALUES ('$codigo', '$descrip', $unidad, $tramo, $estado, '$nivel')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codigo) {
        $query = "DELETE FROM nbu WHERE codigo = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codigo, $descrip, $unidad, $tramo, $estado, $nivel) {
        $query = "UPDATE nbu SET descrip = '$descrip', unidad = $unidad, tramo = $tramo, estado = $estado, nivel = '$nivel' WHERE codigo = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codigo) {
        $found = false;
        $query = "SELECT * FROM nbu WHERE codigo = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codigo = $fila['codigo'];
            $this->descrip = $fila['descrip'];
            $this->unidad = $fila['unidad'];
            $this->tramo = $fila['tramo'];
            $this->inactivo = $fila['estado'];
            $this->nivel = $fila['nivel'];
            $found = true;
        }

        if ($found) {
            return true;
        } else {
            return null;
        }
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getDescrip() {
        return $this->descrip;
    }

    function getUnidad() {
        $unidad = $this->unidad;
        
        // Prorrateamos la mas reciente - Normales        
        $query = "SELECT unidades FROM nbu_unidades WHERE codigo = '" . $this->getCodigo() . "' order by periodo desc limit 0, 1";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $unidad = $fila['unidades'];
        }

        return $unidad;
    }
    
    function getUnidadObsocial($codos) {
        $unidad = $this->unidad;
        
        $found = false;
        
        // Prorrateamos la mas reciente - Adicionales - 20/07/2026       
        $query = "SELECT unidad FROM nbu_os WHERE codigo = '" . $this->getCodigo() . "' and codos = '" . $codos  . "'  limit 0, 1";        
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $unidad = $fila['unidad'];
            $this->unidad = $unidad;        
            $found = true;
        }
        
        if ($found) {
            $this->unidad = $unidad;
            return $unidad;
            exit;
        }
        
        //======================================================================
        
         // Prorrateamos la mas reciente - Normales        
        $query = "SELECT unidades FROM nbu_unidades WHERE codigo = '" . $this->getCodigo() . "' order by periodo desc limit 0, 1";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $unidad = $fila['unidades'];
            $this->unidad = $unidad;
        }
        
        return $unidad;
        
    }

    function getUnidadNbu() {
        // Unidad original
        return $this->unidad;
    }

    function getTramo() {
        return $this->tramo;
    }

    function getInactivo() {
        return $this->inactivo;
    }

    function getNivel() {
        return $this->nivel;
    }

    function getListaNBU() {
        $query = "SELECT * FROM nbu ORDER BY descrip";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

    function getLista($filtro, $RegistrosAEmpezar, $RegistrosAMostrar) {
        if ($filtro == '' or $filtro == 'undefined') {
            $query = "SELECT * FROM nbu ORDER BY descrip LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        } else {
            $query = "SELECT * FROM nbu WHERE descrip LIKE '$filtro%' ORDER BY descrip LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        $this->res = mysql_query($query);
        return $this->res;
    }

    // NBU Complementario

    function getObjectEquivalente($codigo) {
        $found = false;
        $query = "SELECT * FROM practica WHERE vch_codprestacion = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codigo = $fila['vch_codprestacion'];
            $this->descrip = $fila['vch_descripprestacion'];
            $this->unidad = 0;
            $this->tramo = 0;
            $found = true;
        }
        if ($found) {
            return true;
        } else {
            return null;
        }
    }

    function getListaPracticas($filtro, $RegistrosAEmpezar, $RegistrosAMostrar) {
        if ($filtro == '' or $filtro == 'undefined') {
            $query = "SELECT * FROM practica ORDER BY vch_descripprestacion LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        } else {
            $query = "SELECT * FROM practica WHERE vch_descripprestacion LIKE '$filtro%' ORDER BY vch_descripprestacion LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getUnidadAdicional($codos, $codigo) {
        $r = -1;
        $query = "SELECT * FROM nbu_adicional WHERE codos = '$codos' AND codigo = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $r = $fila['unidades'];
        }

        return $r;
    }

    function getPracticaAdicional($codos, $codigo) {
        $found = false;
        $query = "SELECT codigo FROM nbu_adicional WHERE codos = '$codos' AND codigo = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $found = true;
        }

        return $found;
    }

    // actualiza tupla
    function addUnidad($codigo, $periodo, $unidad) {
        $per = substr($periodo, 3, 4) . substr($periodo, 0, 2);
        $query = "DELETE FROM nbu_unidades WHERE codigo = '$codigo' AND periodo = '$per'";
        $this->sql = $query;
        $result = mysql_query($query);
        $query = "INSERT INTO nbu_unidades (codigo, periodo, unidades, unidaddif) VALUES ('$codigo', '$per', $unidad, 0)";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getUnidadesNBU($codigo) {
        $query = "SELECT codigo, periodo, unidades, unidaddif FROM nbu_unidades WHERE codigo = '$codigo' order by periodo desc";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function borrarUnidadesNBU($codigo, $periodo) {
        $query = "DELETE FROM nbu_unidades WHERE codigo = '$codigo' AND periodo = '$periodo'";
        $this->sql = $query;
        $result = mysql_query($query);
    }    

}

?>
