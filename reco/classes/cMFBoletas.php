<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

//implementamos la clase empleado
class cMFBoletas {

//constructor
    var $ID;
    var $codos;
    var $periodo;
    var $concepto;
    var $monto;
    var $fecha;
    var $tipo;
    var $sql;
    var $res;
    var $utiles;

    function cMFBoletas() {
        $this->utiles = new cUtiles;
    }

    // inserta tupla
    function crear($codos, $periodo, $monto, $tipo, $concepto, $fecha) {
        // Si existe, actualizamos
        $id = 0;
        $per = $this->utiles->getPeriodoMMAAAA($periodo);
        $query = "SELECT id FROM mf_boletas WHERE codos = '$codos' AND periodo = '$per'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $id = $fila['id'];
        }

        if ($id > 0) {
            return $this->actualizar($id, $codos, $periodo, $monto, $tipo);
        } else {
            // sino... damos de alta
            $p1 = $this->utiles->getPeriodoMMAAAA($periodo);
            $f1 = $this->utiles->getFechaAAAAMMDD($fecha);
            $query = "INSERT INTO mf_boletas (codos, periodo, monto, tipo, concepto, fecha) VALUES ('$codos', '$p1', $monto, $tipo, '$concepto', '$f1')";
            $this->sql = $query;
            //echo $query;
            $result = mysql_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    // borra tupla
    function borrar($id) {
        $query = "DELETE FROM mf_boletas WHERE id = " . $id;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($id, $codos, $periodo, $monto, $tipo) {
        $p1 = $this->utiles->getPeriodoMMAAAA($periodo);
        $query = "UPDATE mf_boletas SET periodo = '$p1', monto = $monto, tipo = $tipo WHERE id = $id";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($id) {
        $query = "SELECT * FROM mf_boletas WHERE id = " . $id;
        $this->sql = $query;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codos = $fila['codos'];
            $this->periodo = $this->utiles->getPeriodoMM_AAAA($fila['periodo']);
            $this->monto = $fila['monto'];
            $this->tipo = $fila['tipo'];
            $this->concepto = $fila['concepto'];
            $this->fecha = $this->utiles->getFechaDDMMAAAA($fila['fecha']);
        }
        return null;
    }

    function getApFijos($codos, $RegistrosAEmpezar, $RegistrosAMostrar) {
        $this->res = mysql_query("SELECT * FROM mf_boletas WHERE codos = '$codos' ORDER BY periodo DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar");
        return $this->res;
    }

    function getCodos() {
        return $this->codos;
    }

    function getPeriodo() {
        return $this->periodo;
    }

    function getMonto() {
        return $this->monto;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getConcepto() {
        return $this->concepto;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getAranceles() {
        $query = "SELECT * FROM apfijosnbu ORDER BY codos, codanalisis, periodo";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

    function getMontoFijo($codos, $periodo, $fechaorden) {
        $montofijo = 0;
        $p = $this->utiles->getPeriodoMMAAAA($periodo);
        $f1 = $this->utiles->getFechaAAAAMMDD($this->utiles->getFechaActual());
        $p1 = "'" . substr($p, 2, 4) . substr($p, 0, 2) . "'";

        $query = "SELECT * FROM mf_boletas WHERE codos = '$codos' ORDER BY periodo";
        //echo $query;

        $resultado = mysql_query($query);
        if ($resultado) {
            while ($fila = mysql_fetch_array($resultado)) {

                $this->ID = $fila['id'];
                $p2 = "'" . substr($fila['periodo'], 2, 4) . substr($fila['periodo'], 0, 2) . "'";

                if ($p2 <= $p1) {
                    
                    if ($fechaorden >= $fila['fecha']) {
                        $montofijo = $fila['monto'];
                        $this->tipo = $fila['tipo'];
                        $this->concepto = $fila['concepto'];
                        $this->fecha = $fila['fecha'];
                    }
                    
                }
                if ($p2 >= $p1) {
                    break;
                }
            }
        }
        return $montofijo;
    }

}

?>