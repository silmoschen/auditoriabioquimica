<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

class cDiagnosticosOMS {
//constructor
    var $oms_cod;
    var $clave;
    var $orden;
    var $indice;
    var $codrap;
    var $descrip;
    var $oms;
    var $sql;
    var $res;
    var $utiles;

    function cDiagnosticosOMS() {
        $this->utiles = new cUtiles();
    }

    // inserta tupla
    function crear($oms_cod, $clave, $orden, $indice, $codrap, $descrip, $oms) {
        $query = "INSERT INTO diagnosticos_oms (oms_cod, clave, orden, indice, codrap, descrip, oms) VALUES ('$oms_cod', '$clave', '$orden', '$indice', '$codrap', '$descrip', '$oms')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($oms_cod) {
        $query = "DELETE FROM diagnosticos_oms WHERE oms_cod = '$oms_cod'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($oms_cod, $clave, $orden, $indice, $codrap, $descrip, $oms) {
        $query = "UPDATE diagnosticos_oms SET clave = '$clave', orden = '$orden', indice = '$indice', codrap = '$codrap', descrip = '$descrip', oms = '$oms' WHERE oms_cod = '$oms_cod'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($oms_cod) {        
        $nf = false;
        $this->descrip = 'Sin Definir';
        $query = "SELECT * FROM diagnosticos_oms WHERE oms_cod = '$oms_cod'";
        $this->sql = $query;
        $resultado=mysql_query($query);
        while($fila=mysql_fetch_array($resultado)) {
            $this->oms_cod    = $fila['oms_cod'];
            $this->clave      = $fila['clave'];
            $this->orden      = $fila['orden'];
            $this->indice     = $fila['indice'];
            $this->codrap     = $fila['codrap'];
            $this->descrip    = $fila['descrip'];
            if ($fila['oms'] == 'T') {
                $this->oms = 'S';
            } else {
                $this->oms = 'N';
            }
            $nf = true;
        }
        return $nf;
    }

    function Exportar($dx, $oms_cod) {
        $this->getObject($oms_cod);

        if ($this->descrip != 'Sin Definir') {
            $linea = $this->utiles->StringLongitudFija($this->oms_cod,  8) .
                $this->utiles->StringLongitudFija($this->clave,  5) .
                $this->utiles->StringLongitudFija($this->orden,  5) .
                $this->utiles->StringLongitudFija($this->indice,  6) .
                $this->utiles->StringLongitudFija($this->descrip,  100) .
                " \r\n";
            fwrite($dx, $linea);
        }
    }

    function getoms_cod() {
        return $this->oms_cod;
    }

    function getClave() {
        return $this->clave;
    }

    function getOrden() {
        return $this->orden;
    }

    function getIndice() {
        return $this->indice;
    }

    function getCodrap() {
        return $this->codrap;
    }

    function getDescrip() {
        return $this->descrip;
    }

    function getOms() {
        return $this->oms;
    }

    function getDiagnosticos() {
        $query = "SELECT * FROM diagnosticos_oms ORDER BY descrip";
        $this->res=mysql_query($query);
        return $this->res;
    }

    function getDiagnosticosOMS($RegistrosAEmpezar, $RegistrosAMostrar) {
        $query = "SELECT * FROM diagnosticos_oms ORDER BY descrip LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        $this->res=mysql_query($query);
        return $this->res;
    }

    function getSQL() {
        return $this->sql;
    }

}

?>
