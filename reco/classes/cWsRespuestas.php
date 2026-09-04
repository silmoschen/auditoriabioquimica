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
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

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
        //echo $query;
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
        
        $i = 0;
        
        $query = "SELECT * FROM ws_respuestas WHERE fecha >= '$desde' AND fecha <= '$hasta'";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result)) {
            
            $p1 = $fila['ID'];
            $p2 = $fila['fecha'];
            $p3 = $fila['codos'];
            $p4 = $fila['idprof'];
            $p5 = $fila['respuesta'];
            $p6 = $fila['fecha_hora'];
            $p7 = $fila['tipo'];
            $p8 = $fila['nrodoc'];
            $p9 = $fila['expediente'];            
            
            $sql = "INSERT IGNORE INTO ws_respuestas_hist (ID, fecha, codos, idprof, respuesta, fecha_hora, tipo, nrodoc, expediente) VALUES (
                   $p1, '$p2', '$p3', '$p4', '$p5', '$p6', $p7, '$p28', '$p9')";
            
            mysql_query($sql);
            
            $sql = "DELETE FROM ws_respuestas WHERE ID = " . $p1;
            mysql_query($sql);
            
            $i++;
            
        }
        
        if ($i > 0) {
            $sql = "INSERT INTO ws_log_dep (desde, hasta) VALUES ('$desde', '$hasta')";            
            mysql_query($sql);
        }
        
        return $i;
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
    
     function getWsRespuestasList($inicio, $registros) {
        $query = "SELECT * FROM ws_respuestas ORDER BY id desc LIMIT $inicio, $registros";
        $this->res = mysql_query($query);
        return $this->res;
    }
    
    function getWsRespuestasCount() {
        $r = 0;
        $query = "SELECT count(*) as cant FROM ws_respuestas";
        $result = mysql_query($query);
        while ($fila = mysql_fetch_array($result)) {
            $r = $fila['cant'];
        }
        return $r;
    }
    
     function getHistorial() {
        $query = "SELECT * from ws_log_dep ORDER BY ID DESC";
        $this->res = mysql_query($query);
        return $this->res;
    }

}

?>
