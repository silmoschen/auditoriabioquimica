<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

class cLogs {
    var $usuario;
    var $fechahora;
    var $fecha;
    var $utiles;

    function CLogs() {
        $this->utiles = new cUtiles;
    }

    function crear($usuario) {
        $fh = $this->utiles->getFechaHoraActual();
        $fe = $this->utiles->getFechaAAAAMMDD($this->utiles->getFechaActual());
        $query = "INSERT INTO logs(usuario, fechahora, fecha) VALUES ('$usuario', '$fh', '$fe')";        
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($fecha) {
        $fe = $this->utiles->getFechaAAAAMMDD($fecha);
        $query = "DELETE FROM logs WHERE fecha <= '$fe'";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getFechahora() {
        return $this->fechahora;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getLogs($usuario, $xdesde, $xhasta, $inicio, $registros) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        if ($usuario == '0') {
          $query = "SELECT * FROM logs WHERE fecha >= '$desde' AND fecha <= '$hasta' ORDER BY fecha  LIMIT $inicio, $registros";
        } else {
          $query = "SELECT * FROM logs WHERE fecha >= '$desde' AND fecha <= '$hasta' AND usuario = '$usuario' ORDER BY fecha  LIMIT $inicio, $registros";
        }
        $this->res=mysql_query($query);
        return $this->res;
    }

    function getUsuarios() {
        $query = "SELECT DISTINCT(usuario) FROM logs ORDER BY usuario";
        $this->res=mysql_query($query);
        return $this->res;
    }

    function depurar($xdesde, $xhasta) {
        $desde = $this->utiles->getFechaAAAAMMDD($xdesde);
        $hasta = $this->utiles->getFechaAAAAMMDD($xhasta);
        $query = "DELETE FROM logs WHERE fecha >= '$desde' AND fecha <= '$hasta'";
        $this->res=mysql_query($query);
    }

}

?>

