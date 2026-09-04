<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
//implementamos la clase empleado
class cEquivalenciaNBU {
    var $codos;
    var $codigo1;
    var $codigo2;
    var $prefijo;
    var $sql;
    var $res;

    function cEquivalenciaNBU() {
    }

    // inserta tupla
    function crear($codos, $codigo1, $codigo2) {
        $query = "INSERT INTO codigos_eq (codos, codigo1, codigo2) VALUES ('$codos', '$codigo1', '$codigo2')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $codigo1) {
        $query = "DELETE FROM codigos_eq WHERE codos = '$codos' AND codigo1 = '$codigo1'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getCodigos($codos, $codigo) {
        if ($codigo == '') {
            $query = "SELECT * FROM codigos_eq WHERE codos = '$codos'";
        } else {
            $query = "SELECT * FROM codigos_eq WHERE codos = '$codos' AND codigo1 = '$codigo'" ;
        }
        $this->res=mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

    function getCodigoEquivalente($codos, $codigo1) {
        $cod = '';
        $query = "SELECT * FROM codigos_eq WHERE codos = '$codos' AND codigo1 = '$codigo1'";
        $result = mysql_query($query);
        while($fila=mysql_fetch_array($result)) {
            $cod = $fila['codigo2'];
        }
        return $cod;
    }
    
    function getCodigoInverso($codos, $codigo1) {
        $cod = '';
        $query = "SELECT * FROM codigos_eq WHERE codos = '$codos' AND codigo2 = '$codigo1'";
        $result = mysql_query($query);
        while($fila=mysql_fetch_array($result)) {
            $cod = $fila['codigo1'];
        }
        return $cod;
    }    

}

?>
