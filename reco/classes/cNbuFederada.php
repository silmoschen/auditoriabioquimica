<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cNbuFederada {

    //constructor
    var $codigo;
    var $descrip;
    var $nivel;
    var $tope_anual;
    var $sql;
    var $res;

    function cNBU() {
        
    }

    // inserta tupla
    function crear($codigo, $descrip, $nivel, $tope_anual) {
        $query = "INSERT INTO nbu_federada (codigo, descrip, nivel, tope_anual) VALUES ('$codigo', '$descrip', $nivel, $tope_anual)";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codigo) {
        $query = "DELETE FROM nbu_federada WHERE codigo = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codigo, $descrip, $nivel, $tope_anual) {
        $query = "UPDATE nbu_federada SET descrip = '$descrip', nivel = $nivel, tope_anual = $tope_anual WHERE codigo = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codigo) {
        $found = false;
        $query = "SELECT * FROM nbu_federada WHERE codigo = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codigo = $fila['codigo'];
            $this->descrip = $fila['descrip'];
            $this->nivel = $fila['nivel'];
            $this->tope_anual = $fila['tope_anual'];           
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

    function getNivel() {
        return $this->nivel;
    }

    function getTopeAnual() {
        return $this->tope_anual;
    }   

   function getListaNBU() {
        $query = "SELECT * FROM nbu_feredada ORDER BY descrip";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

    function getLista($filtro, $RegistrosAEmpezar, $RegistrosAMostrar) {
        if ($filtro == '' or $filtro == 'undefined') {
            $query = "SELECT * FROM nbu_federada ORDER BY codigo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        } else {
            $query = "SELECT * FROM nbu_federada WHERE codigo LIKE '$filtro%' ORDER BY codigo LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        $this->res = mysql_query($query);
        return $this->res;
    }  
        
}

?>
