<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/conexion.php");

//implementamos la clase empleado
class cModelos {

    //constructor
    var $codos;
    var $idcontrol;
    var $codigo;
    var $fecuencia;
    var $tipofrecuencia;
    var $inhabilitado;
    var $sql;

    function cModelos() {
        
    }

    // inserta tupla
    function crear($codos, $idcontrol, $codigo, $frecuencia, $tipofrecuencia) {
        $query = "INSERT INTO modelos (codos, idcontrol, codigo, frecuencia, tipofrecuencia, inhabilitado, aut_diferida) VALUES ('$codos', '$idcontrol', '$codigo', $frecuencia, $tipofrecuencia, '', '')";
        $this->sql = $query;
        //echo $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codos, $idcontrol, $codigo) {
        $query = "DELETE FROM modelos WHERE codos = '$codos' AND idcontrol = '$idcontrol' AND codigo = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codos, $idcontrol, $codigo, $frecuencia, $tipofrecuencia) {
        $query = "UPDATE modelos SET frecuencia = $frecuencia, tipofrecuencia = $tipofrecuencia WHERE codos = '$codos' AND idcontrol = '$idcontrol' AND codigo = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codos, $idcontrol, $codigo) {
        $this->frecuencia = 0;
        $query = "SELECT * FROM modelos WHERE codos = '$codos' AND idcontrol = '$idcontrol' AND codigo = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codos = $fila['codos'];
            $this->idcontrol = $fila['idcontrol'];
            $this->codigo = $fila['codigo'];
            $this->frecuencia = $fila['frecuencia'];
            $this->tipofrecuencia = $fila['tipofrecuencia'];
        }
        return null;
    }

    function getCodos() {
        return $this->codos;
    }

    function getIdcontrol() {
        return $this->idcontrol;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getFrecuencia() {
        return $this->frecuencia;
    }

    function getTipofrecuencia() {
        return $this->tipofrecuencia;
    }

    function getSQL() {
        return $this->sql;
    }

    function getModelo($codos, $idcontrol) {
        $query = "SELECT modelos.codos, modelos.idcontrol, modelos.codigo, modelos.frecuencia, modelos.tipofrecuencia, nbu.descrip from modelos, nbu where modelos.codigo = nbu.codigo and modelos.codos = '$codos' and modelos.idcontrol = '$idcontrol'";
        $this->sql = $query;
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getModelos($codos) {
        $query = "SELECT DISTINCT diagnosticos_oms.oms_cod, diagnosticos_oms.descrip, modelos.inhabilitado from diagnosticos_oms, modelos where diagnosticos_oms.oms_cod = modelos.idcontrol and modelos.codos = '$codos' ORDER BY descrip";
        $this->sql = $query;
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getModelosDef($codos, $iniciar, $terminar) {
        if (strlen($iniciar) > 0 and strlen($terminar) > 0) {
            $query = "SELECT DISTINCT diagnosticos_oms.oms_cod, diagnosticos_oms.descrip from diagnosticos_oms, modelos where diagnosticos_oms.oms_cod = modelos.idcontrol and modelos.codos = '$codos' ORDER BY descrip LIMIT $iniciar, $terminar";
        } else {
            $query = "SELECT DISTINCT diagnosticos_oms.oms_cod, diagnosticos_oms.descrip from diagnosticos_oms, modelos where diagnosticos_oms.oms_cod = modelos.idcontrol and modelos.codos = '$codos'";
        }
        $this->sql = $query;
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getFrecuenciaEnDias($codos, $idcontrol, $codigo) {
        $dias = 0;
        $query = "SELECT frecuencia, tipofrecuencia FROM modelos WHERE idcontrol = '$idcontrol' AND codigo = '$codigo' AND codos = '$codos'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $dias = $fila['frecuencia'];
            if ($fila['tipofrecuencia'] == 2) {
                $dias = $dias * 30;
            }
            if ($fila['tipofrecuencia'] == 3) {
                $dias = $dias * 365;
            }
            if ($fila['tipofrecuencia'] == 4) {
                $dias = 50000;
            }
        }
        return $dias;
    }

    function copiar($de_codos, $a_codos, $idcontrol) {
        // borramos cualquier definición previa
        $query = "DELETE FROM modelos where codos= '$a_codos' AND idcontrol = '$idcontrol'";
        $result = mysql_query($query);

        // copiamos
        $query = "SELECT * FROM modelos WHERE idcontrol = '$idcontrol' AND codos = '$de_codos'";

        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->crear($a_codos, $idcontrol, $fila['codigo'], $fila['frecuencia'], $fila['tipofrecuencia']);
        }        
    }

    // actualiza tupla
    function Inhabilitar($codos, $idcontrol, $estado) {
        $query = "UPDATE modelos SET inhabilitado = '$estado' WHERE codos = '$codos' AND idcontrol = '$idcontrol'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function VerificarEstado($codos, $idcontrol) {
        $c = '';
        $query = "SELECT DISTINCT inhabilitado from modelos where modelos.codos = '$codos' and modelos.idcontrol = '$idcontrol'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $c = $fila['inhabilitado'];
        }

        if ($c == 'S') {
            return true;
        } else {
            return false;
        }
    }

    function VerificarDefinicion($codos, $idcontrol) {
        $c = 0;
        $query = "SELECT COUNT(codos) AS cant from modelos where modelos.codos = '$codos' and modelos.idcontrol = '$idcontrol'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $c = $fila['cant'];
        }

        if ($c > 0) {
            return true;
        } else {
            return false;
        }
    }
    
     function autorizacionDiferida($codos, $idcontrol, $aut_diferida) {
        $query = "UPDATE modelos SET aut_diferida = '$aut_diferida' WHERE codos = '$codos' AND idcontrol = '$idcontrol'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }
    
     function VerificarAutorizacionDiferida($codos, $idcontrol) {
        $c = '';
        $query = "SELECT DISTINCT aut_diferida from modelos where modelos.codos = '$codos' and modelos.idcontrol = '$idcontrol'";
        //echo $query;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $c = $fila['aut_diferida'];
        }

        if ($c == 'S') {
            return true;
        } else {
            return false;
        }
    }

}

?>
