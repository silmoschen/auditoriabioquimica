<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cNbuReglas {

    //constructor
    var $codos;
    var $regla;

    function getObject($id) {
        $found = false;
        $query = "SELECT * FROM nbu_reglas WHERE codos = '$id'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codos = $fila['codos'];
            $this->regla = $fila['regla'];
            $found = true;
        }
        if ($found) {
            return true;
        } else {
            return null;
        }
    }

    function getDeterminacionR1($codos, $codigo) {
        $query = "SELECT * FROM nbu_iapos_bonos WHERE codos = '$codos' AND codigo = '$codigo'";
        $this->res = mysql_query($query);
        return $this->res;
    }

}

?>
