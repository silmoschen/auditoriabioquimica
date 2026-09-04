<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cWsRespuestas
 *
 * @author server
 */
include_once($_SERVER['DOCUMENT_ROOT'] . '/reco/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/reco/classes/cUtiles.php');

class cWsRespuestas {

    var $id;
    var $fecha;
    var $codos;
    var $idprof;
    var $respuesta;
    var $fecha_hora;
    var $tipo;
    var $nrodoc;
    var $expediente;
    var $utiles;

    function cWsRespuestas() {
        $this->utiles = new cUtiles;
    }

    function crear($codos, $idprof, $nrodoc, $respuesta, $tipo) {
        $fh = $this->utiles->getFechaHoraActual();
        $fe = $this->utiles->getFechaAAAAMMDD($this->utiles->getFechaActual());
        $query = "INSERT INTO ws_respuestas(codos, idprof, nrodoc, fecha, respuesta, fecha_hora, tipo) VALUES ('$codos', '$idprof', '$nrodoc', '$fe', '$respuesta', '$fh', $tipo)";
        $result = mysql_query($query);
        //echo $query;
        if (!$result)
            return false;
        else
            return true;
    }

    function crear1($codos, $idprof, $nrodoc, $respuesta, $tipo, $expediente) {
        $fh = $this->utiles->getFechaHoraActual();
        $fe = $this->utiles->getFechaAAAAMMDD($this->utiles->getFechaActual());
        $query = "INSERT INTO ws_respuestas(codos, idprof, nrodoc, fecha, respuesta, fecha_hora, tipo, expediente) VALUES ('$codos', '$idprof', '$nrodoc', '$fe', '$respuesta', '$fh', $tipo, '$expediente')";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getWsRespuestas($codos, $xdesde, $xhasta, $inicio, $registros) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        if ($codos == '0') {
            $query = "SELECT * FROM ws_respuestas WHERE fecha >= '$desde' AND fecha <= '$hasta' ORDER BY fecha  LIMIT $inicio, $registros";
        } else {
            $query = "SELECT * FROM ws_respuestas WHERE fecha >= '$desde' AND fecha <= '$hasta' AND codos = '$codos' ORDER BY fecha  LIMIT $inicio, $registros";
        }
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getObrasSociales() {
        $query = "SELECT DISTINCT(codos) FROM ws_respuestas ORDER BY codos";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function depurar($xdesde, $xhasta) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        $query = "DELETE FROM ws_respuestas WHERE fecha >= '$desde' AND fecha <= '$hasta'";
        $this->res = mysql_query($query);
    }

    function getWsErrorRegla1($nroauditoria) {
        $r = '';
        $query = "SELECT respuesta FROM ws_respuestas WHERE tipo = 4 AND expediente = '$nroauditoria'";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result)) {
            $r = $fila['respuesta'];
        }
        return $r;
    }
    
    function getSQL1($xtipo, $xcodos, $xnrodoc, $xexpediente) {
        $query = "SELECT respuesta FROM ws_respuestas WHERE codos = '$xcodos' and tipo = $xtipo and nrodoc = '$xnrodoc' and expediente = '$xexpediente'";
        $this->res = mysql_query($query);
        return $this->res;
    }

}

?>
