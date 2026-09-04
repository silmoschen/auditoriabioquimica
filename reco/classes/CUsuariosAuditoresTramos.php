<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/CUsuarios.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');

//implementamos la clase empleado
class CUsuariosAuditoresTramos {

    //constructor
    var $ID;
    var $desde;
    var $hasta;
    var $auditor1;
    var $auditor2;
    var $nauditor1;
    var $nauditor2;

    function CUsuariosAuditoresTramos() {
        
    }

    // inserta tupla
    function crear($desde, $hasta, $auditor1, $auditor2) {        
        $result = mysql_query("SELECT desde FROM usuarios_auditores_tramos WHERE desde = '$desde'"); 
        $number_of_rows = mysql_num_rows($result);
        if ($number_of_rows > 0) return;
        
        $query = "INSERT INTO usuarios_auditores_tramos(desde, hasta, fk_auditor1, fk_auditor2) VALUES ('$desde', '$hasta', $auditor1, $auditor2)";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($id) {
        $query = "DELETE FROM usuarios_auditores_tramos WHERE id = $id";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($id) {
        $found = false;
        $query = "SELECT * FROM usuarios_auditores_tramos WHERE id = $id";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->desde = $fila['desde'];
            $this->hasta = $fila['hasta'];
            $this->auditor1 = $fila['fk_auditor1'];
            $this->auditor2 = $fila['fk_auditor2'];
            $found = true;
        }
        if ($found) {
            return true;
        } else {
            return null;
        }
    }

    function getDesde() {
        return $this->desde;
    }

    function getHasta() {
        return $this->hasta;
    }

    function getAuditor1() {
        return $this->auditor1;
    }

    function getAuditor2() {
        return $this->auditor2;
    }
    
    function getNAuditor1() {
        return $this->nauditor1;
    }

    function getNAuditor2() {
        return $this->nauditor2;
    }

    function getUsuariosTramos() {
        $query = "SELECT * FROM usuarios_auditores_tramos ORDER BY desde DESC";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function verificarAuditor1($us, $fecha) {
        $found = false;
        $query = "SELECT * FROM usuarios_auditores_tramos WHERE fk_auditor1 = '$us' AND desde >= '$fecha' AND hasta <= '$fecha'";
        //echo $query;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->desde = $fila['desde'];
            $this->hasta = $fila['hasta'];
            $this->auditor1 = $fila['fk_auditor1'];
            $this->auditor2 = $fila['fk_auditor2'];  
            $found = true;
        }
        return $found;
    }

    function verificarAuditor2($us, $fecha) {
        $found = false;
        $query = "SELECT * FROM usuarios_auditores_tramos WHERE fk_auditor2 = '$us' AND desde >= '$fecha' AND hasta <= '$fecha'";
        //echo $query;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->desde = $fila['desde'];
            $this->hasta = $fila['hasta'];
            $this->auditor1 = $fila['fk_auditor1'];
            $this->auditor2 = $fila['fk_auditor2'];  
            $found = true;
        }
        return $found;
    }
    
    
    function getAuditores($fecha) {
        $utiles = new cUtiles();
        
        $efector = new cEfector();
        $usuario = new CUsuarios();        
        
        $f = $utiles->getFechaAAAAMMDD($fecha);
        $found = false;
        $query = "SELECT * FROM usuarios_auditores_tramos where desde <= '$f' and hasta >= '$f' order by desde desc limit 0, 1";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->desde = $fila['desde'];
            $this->hasta = $fila['hasta'];
           
            $usuario->getObject($fila['fk_auditor1']);
            $efector->getObject($usuario->getEfector());
            $this->nauditor1 = $efector->getNombre();
            $this->auditor1 = $efector->getCodigo();
            
            $usuario->getObject($fila['fk_auditor2']);
            $efector->getObject($usuario->getEfector());
            $this->nauditor2 = $efector->getNombre();
            $this->auditor2 = $efector->getCodigo();
            
            $found = true;
        }
        if ($found) {
            return true;
        } else {
            return null;
        }
    }
    
}

?>
