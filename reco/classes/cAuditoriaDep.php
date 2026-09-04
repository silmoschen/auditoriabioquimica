<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cAuditoriaDep
 *
 * @author silmo
 */
set_include_path('../');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

class cAuditoriaDep {

    var $utiles;

    function cAuditoriaDep() {
        $this->utiles = new cUtiles;
    }

    function crear($desde, $hasta, $codos) {
        $d = $this->utiles->getFechaAAAAMMDD($desde);
        $h = $this->utiles->getFechaAAAAMMDD($desde);

        $query = "INSERT INTO dep_auditoria (desde, hasta, codos) VALUES ('$d', '$h', '$codos')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }
    
    function getList($codos) {
        $query = "select desde, hasta, codos, fecha_hora from dep_auditoria where codos = '$codos' order by fecha_hora desc";
        //$echo $query;
        $this->res = mysql_query($query);
        return $this->res;
    }

}
