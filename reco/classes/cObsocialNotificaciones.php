<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cObsocialNotificaciones {

    //constructor
    var $codos;
    var $baja;
    var $m1;
    var $m2;
    var $m3;
    var $m4;
    var $m5;
    var $m6;
    var $m7;

    // inserta tupla
    function crear($codos, $baja, $m1, $m2, $m3, $m4, $m5, $m6, $m7) {
        $this->borrar($codos);
        $query = "INSERT INTO obsocial_notificaciones (codos, baja, m1, m2, m3, m4, m5, m6, m7) VALUES ('$codos', $baja, '$m1', '$m2', '$m3', '$m4', '$m5', '$m6', '$m7')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos) {
        $query = "DELETE FROM obsocial_notificaciones WHERE codos = '$codos'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codos) {
        $find = false;
        $query = "SELECT * FROM obsocial_notificaciones WHERE codos = '$codos' AND baja = 0";
        $result = mysql_query($query);
        
        while ($fila = mysql_fetch_array($result)) {
            $codos = $fila['codos'];
            $baja = $fila['baja'];
            $m1 = $fila['m1'];
            $m2 = $fila['m2'];
            $m3 = $fila['m3'];
            $m4 = $fila['m4'];
            $m5 = $fila['m5'];
            $m6 = $fila['m6'];
            $m7 = $fila['m7'];
            $find = true;            
        }        
        
        if ($find) {
            return true;
        } else {
            return null;
        }        
    }

    function getObjectJSON($codos) {
        $query = "SELECT * FROM obsocial_notificaciones WHERE codos = '$codos'";
        $res = mysql_query($query);

        $rows = null;
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }

        echo json_encode($rows);
    }

    function getObjectJSONOsActiva($codos) {
        $query = "SELECT * FROM obsocial_notificaciones WHERE codos = '$codos' AND baja = 0";
        $res = mysql_query($query);

        $rows = null;
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }

        echo json_encode($rows);
    }   
    
    function getObjectJSActiva($codos) {
        $query = "SELECT * FROM obsocial_notificaciones WHERE codos = '$codos' AND baja = 0";
        $res = mysql_query($query);

        $rows = null;
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }

        return json_encode($rows);
    }   
    
    function getSQL() {
        return $this->sql;
    }

}

?>
