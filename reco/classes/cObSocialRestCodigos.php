<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cObsocialRestCodigos {

    //constructor
    var $codos;
    var $codigo1;
    var $codigo2;
    var $observacion;

    // inserta tupla
    function crear($codos, $codigo1, $codigo2, $observacion) {        
        $query = "INSERT INTO obsocial_rest_codigos (codos, codigo1, codigo2, observacion) VALUES ('$codos', '$codigo1', '$codigo2', '$observacion')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $codigo1, $codigo2) {
        $query = "DELETE FROM obsocial_rest_codigos WHERE codos = '$codos' and codigo1 = '$codigo1' and codigo2 = '$codigo2'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codos) {
        $find = false;
        $query = "SELECT * FROM obsocial_rest_codigos WHERE codos = '$codos'";
        $result = mysql_query($query);
        
        while ($fila = mysql_fetch_array($result)) {
            $codos = $fila['codos'];
            $codigo1 = $fila['codigo1'];
            $codigo2 = $fila['codigo2'];
            $observacion = $fila['observacion'];
            $find = true;            
        }        
        
        if ($find) {
            return true;
        } else {
            return null;
        }        
    }

    function getObjectJSON($codos) {
        $query = "SELECT * FROM obsocial_rest_codigos WHERE codos = '$codos'";
        $res = mysql_query($query);

        $rows = null;
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }

        echo json_encode($rows);
    }   
    
    function getSQL() {
        return $this->sql;
    }
    
    function getObjects($codos) {
        $query = "SELECT * FROM obsocial_rest_codigos WHERE codos = '$codos'";
        $res = mysql_query($query);
        return $res;        
    }   

}

?>

