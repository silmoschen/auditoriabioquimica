<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
//implementamos la clase empleado
class capfijosnbu {
//constructor
    var $ID;
    var $codos;
    var $codanalisis;
    var $periodo;
    var $importe;
    var $perhasta;
    var $perbaja;
    var $sql;
    var $res;
    var $utiles;

    function capfijosnbu() {
        $this->utiles = new cUtiles;
    }

    // inserta tupla
    function crear($codos, $codanalisis, $periodo, $importe, $perhasta, $perbaja) {
    // Si existe, actualizamos
        $id = 0;
        $per = $this->utiles->getPeriodoMMAAAA($periodo);
        $query = "SELECT id, periodo, codos, codanalisis FROM apfijosnbu WHERE codos = '$codos' AND codanalisis = '$codanalisis' AND periodo = '$per'";

        $resultado=mysql_query($query);
        while($fila=mysql_fetch_array($resultado)) {
            $id = $fila['id'];
        }

        if ($id > 0) {
            return $this->actualizar($id, $codos, $codanalisis, $periodo, $importe, $perhasta, $perbaja);
        } else {
        // sino... damos de alta
            $p1 = $this->utiles->getPeriodoMMAAAA($periodo);
            $p2 = $this->utiles->getPeriodoMMAAAA($perhasta);
            $p3 = $this->utiles->getPeriodoMMAAAA($perbaja);
            $query = "INSERT INTO apfijosnbu (codos, codanalisis, periodo, importe, perhasta, perbaja) VALUES ('$codos', '$codanalisis', '$p1', $importe, '$p2', '$p3')";
            $this->sql = $query;
            $result = mysql_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    // borra tupla
    function borrar($id) {
        $query = "DELETE FROM apfijosnbu WHERE id = " . $id;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($id, $codos, $codanalisis, $periodo, $importe, $perhasta, $perbaja) {
        $p1 = $this->utiles->getPeriodoMMAAAA($periodo);
        $p2 = $this->utiles->getPeriodoMMAAAA($perhasta);
        $p3 = $this->utiles->getPeriodoMMAAAA($perbaja);
        $query = "UPDATE apfijosnbu SET periodo = '$p1', importe = $importe, perhasta = '$p2', perbaja = '$p3' WHERE id = $id";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($id) {
        $query = "SELECT * FROM apfijosnbu WHERE id = " . $id;
        $this->sql = $query;
        $resultado=mysql_query($query);
        while($fila=mysql_fetch_array($resultado)) {
            $this->codos       = $fila['codos'];
            $this->codanalisis = $fila['codanalisis'];
            $this->periodo     = $this->utiles->getPeriodoMM_AAAA($fila['periodo']);
            $this->importe     = $fila['importe'];
            $this->perhasta    = $this->utiles->getPeriodoMM_AAAA($fila['perhasta']);
            $this->perbaja     = $this->utiles->getPeriodoMM_AAAA($fila['perbaja']);
        }
        return null;
    }

    function getApFijos($codos, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $this->res = mysql_query("SELECT * FROM apfijosnbu WHERE codos = '$codos' ORDER BY codanalisis, periodo DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
        return $this->res;
    }

    function getCodos() {
        return $this->codos;
    }

    function getCodanalisis() {
        return $this->codanalisis;
    }

    function getPeriodo() {
        return $this->periodo;
    }

    function getImporte() {
        return $this->importe;
    }

    function getPerhasta() {
        return $this->perhasta;
    }

    function getPerbaja() {
        return $this->perbaja;
    }

    function getAranceles() {
        $query = "SELECT * FROM apfijosnbu ORDER BY codos, codanalisis, periodo";
        $this->res=mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

    function getMontoFijo($codos, $codigo, $periodo) {
        $montofijo = 0;
        $p  = $this->utiles->getPeriodoMMAAAA($periodo);
        $p1 = "'" . substr($p, 2, 4) . substr($p, 0, 2) . "'";
        
        $query = "SELECT * FROM apfijosnbu WHERE codos = '$codos' AND codanalisis = '$codigo' ORDER BY periodo";
        
        $resultado=mysql_query($query);
        if ($resultado) {
            while($fila=mysql_fetch_array($resultado)) {
                $this->ID = $fila['id'];
                $p2 = "'" . substr($fila['periodo'], 2, 4) . substr($fila['periodo'], 0, 2) . "'";
                if (strlen($fila['perbaja']) == 6) {
                    $p3 = "'" . substr($fila['perbaja'], 2, 4) . substr($fila['perbaja'], 0, 2) . "'";
                } else {
                    $p3 = "'" . '999999' . "'";
                }

                if ($p1 < $p3) { $montofijo = $fila['importe']; }
                if ($p1 >= $p2) { break; }
            }
        }
        return $montofijo;
    }

}

?>