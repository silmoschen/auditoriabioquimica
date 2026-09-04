<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

//implementamos la clase empleado
class cArancelNBU {

//constructor
    var $codos;
    var $periodo;
    var $valor;
    var $valordif;
    var $modulo;
    var $nbu_os;
    var $sql;
    var $res;
    var $utiles;

    function cArancelNBU() {
        $this->utiles = new cUtiles;
    }

    // inserta tupla
    function crear($codos, $periodo, $valor, $valordif, $modulo, $nbu_os) {
        $p = $this->utiles->getPeriodoMMAAAA($periodo);
        $s = substr($p, 2, 4) . substr($p, 0, 2);
        $query = "INSERT INTO arancelesnbu (codos, periodo, valor, valordif, modulo, speriodo, nbu_os) VALUES ('$codos', '$p', $valor, $valordif, $modulo, $s, $nbu_os)";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $periodo) {
        $p = $this->utiles->getPeriodoMMAAAA($periodo);
        $query = "DELETE FROM arancelesnbu WHERE codos = " . $codos . " AND periodo = " . $p;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codos, $periodo, $valor, $valordif, $modulo, $nbu_os) {
        $p = $this->utiles->getPeriodoMMAAAA($periodo);
        $s = substr($p, 2, 4) . substr($p, 0, 2);
        $query = "UPDATE arancelesnbu SET valor = " . $valor . ", valordif = " . $valordif . " ,modulo = " . $modulo . " ,speriodo = " . $s . ", nbu_os = " . $nbu_os . " WHERE codos = " . $codos . " AND periodo = " . $p;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codos, $periodo) {
        $p = $this->utiles->getPeriodoMMAAAA($periodo);
        $query = "SELECT * FROM arancelesnbu WHERE codos = '$codos' AND periodo = '$p'";
        $this->sql = $query;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codos = $fila['codos'];
            $this->periodo = $this->utiles->getPeriodoMM_AAAA($fila['periodo']);
            $this->valor = $fila['valor'];
            $this->valordif = $fila['valordif'];
            $this->modulo = $fila['modulo'];
            $this->nbu_os = $fila['nbu_os'];
        }
        return null;
    }

    function getCodos() {
        return $this->codos;
    }

    function getPeriodo() {
        return $this->periodo;
    }

    function getArancel() {
        return $this->valor;
    }

    function getArancelDif() {
        return $this->valordif;
    }

    function getModulo() {
        return $this->modulo;
    }
    
    function getNbu_os() {
        return $this->nbu_os;
    }

    function getAranceles() {
        $query = "SELECT * FROM arancelesnbu ORDER BY codos, speriodo";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getarancelesnbu($codos, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $query = "SELECT * FROM arancelesnbu WHERE codos = '$codos' ORDER BY speriodo DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

    function getArancelNBU($codos, $periodo) {
        $arancel = 0;
        $p = $this->utiles->getPeriodoMMAAAA($periodo);
        $p1 = "'" . substr($p, 2, 4) . substr($p, 0, 2) . "'";

        $query = "SELECT * FROM arancelesnbu WHERE codos = '$codos' AND speriodo <= $p1 ORDER BY speriodo";

        $resultado = mysql_query($query);
        if ($resultado) {
            while ($fila = mysql_fetch_array($resultado)) {
                $arancel = $fila['valor'];
                $this->valor = $fila['valor'];
                $this->modulo = $fila['modulo'];
                $this->nbu_os = $fila['nbu_os'];
            }
        }

        return $arancel;
    }

    function getArancelNBUDiferencial($codos, $periodo) {
        $arancel = 0;
        $p = $this->utiles->getPeriodoMMAAAA($periodo);
        $p1 = "'" . substr($p, 2, 4) . substr($p, 0, 2) . "'";

        $query = "SELECT * FROM arancelesnbu WHERE codos = '$codos' AND speriodo <= $p1 ORDER BY speriodo";

        $resultado = mysql_query($query);
        if ($resultado) {
            while ($fila = mysql_fetch_array($resultado)) {
                $arancel = $fila['valordif'];
                $this->modulo = $fila['modulo'];
                $this->nbu_os = $fila['nbu_os'];
            }
        }

        return $arancel;
    }

}

?>