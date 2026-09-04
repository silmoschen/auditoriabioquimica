<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cEntidad {

    //constructor
    var $nombre;
    var $direccion;
    var $telefono;
    var $email;
    var $parametro1;
    var $parametro2;
    var $parametro3;
    var $parametro4;
    var $cuit;
    var $tipo_entidad;
    var $unifica_hist;
    var $departamento;
    var $obs1;
    var $obs2;
    var $obs3;
    var $sql;

    function cEntidad() {
        
    }

    // inserta tupla
    function crear($nombre, $direccion, $telefono, $email, $parametro1, $parametro2, $parametro3, $parametro4, $cuit, $tipo_entidad, $unifica_hist, $departamento, $obs1) {
        $query = "DELETE FROM entidad";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result) {

        }
        $query = "INSERT INTO entidad (id, nombre, direccion, telefono, email, parametro1, parametro2, parametro3, parametro4, cuit, tipo_entidad, unifica_hist, departamento, obs1) VALUES
                                   (1, '$nombre', '$direccion', '$telefono', '$email', $parametro1, '$parametro2', '$parametro3', '$parametro4', '$cuit', $tipo_entidad, '$unifica_hist', "
                . "                 '$departamento', '$obs1')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject() {
        $query = "SELECT * FROM entidad WHERE id = 1";
        $resultado = mysql_query($query);
        $this->parametro1 = '4';
        while ($fila = mysql_fetch_array($resultado)) {
            $this->nombre = $fila['nombre'];
            $this->direccion = $fila['direccion'];
            $this->telefono = $fila['telefono'];
            $this->email = $fila['email'];
            $this->parametro1 = $fila['parametro1'];
            $this->parametro2 = $fila['parametro2'];
            $this->parametro3 = $fila['parametro3'];
            $this->parametro4 = $fila['parametro4'];
            $this->cuit = $fila['cuit'];
            $this->tipo_entidad = $fila['tipo_entidad'];
            $this->unifica_hist = $fila['unifica_hist'];
            $this->departamento = $fila['departamento'];
            $this->obs1 = $fila['obs1'];
        }
        return null;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getEmail() {
        return $this->email;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getParametro1() {
        return $this->parametro1;
    }

    function getParametro2() {
        return $this->parametro2;
    }

    function getParametro3() {
        return $this->parametro3;
    }

    function getParametro4() {
        return $this->parametro4;
    }

    function getCuit() {
        return $this->cuit;
    }

    function getTipo_entidad() {
        return $this->tipo_entidad;
    }

    function getUnifica_hist() {
        return $this->unifica_hist;
    }

    function getDepartamento() {
        return $this->departamento;
    }
    
    function getObs1() {
        return $this->obs1;
    }

    function getSQL() {
        return $this->sql;
    }

    function verificarTipoPrestador($id) {
        $found = false;
        $query = "SELECT tipo_entidad FROM entidad WHERE tipo_entidad = '$id'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $found = true;
            break;
        }
        return $found;
    }

}

?>