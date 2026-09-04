<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

class cEfectoresExcluidos {

    var $codos;
    var $idprof;
    var $utiles;

    function cEfectoresExcluidos() {
        $this->utiles = new cUtiles;
    }

    function crear($codos, $idprof) {
        $id = $this->utiles->LlenarIzquierda($idprof, 6, '0');
        $query = "INSERT INTO efectores_excluidos (codos, idprof) VALUES ('$codos', $id )";
        //echo $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $idprof) {
        $id = $this->utiles->LlenarIzquierda($idprof, 6, '0');
        //echo $query;
        $query = "DELETE FROM efectores_excluidos WHERE codos = '$codos' and idprof = $id";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getCodos() {
        return $this->codos;
    }

    function getIdprof() {
        return $this->idprof;
    }

    function getObject($codos, $idprof) {
        $query = "SELECT count(*) AS c FROM efectores_excluidos WHERE codos = '$codos' AND idprof = '$idprof'";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result))
            $res = $fila['c'];
        return $res;
    }

    function procesar($codos, $idprof) {
        $id = "'" . $this->utiles->LlenarIzquierda($idprof, 6, '0') . "'";
        if ($this->getObject($codos, $idprof) == 0) {
            $this->crear($codos, $id);
        } else {
            $this->borrar($codos, $id);
        }
    }

}
?>

