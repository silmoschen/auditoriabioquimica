<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cEfector {

    //constructor
    var $codigo;
    var $nombre;
    var $usuario;
    var $pass;
    var $direccion;
    var $nrocuit;
    var $email;
    var $id_especialidad;
    var $id_tipoprestador;
    var $fechamat;
    var $matricula_nac;
    var $baja;
    var $res;
    var $sql;
    var $parametro1;
    var $parametro2;
    var $parametro3;
    var $nivel2;

    function cEfector() {
        
    }

    // inserta tupla
    function crear($codigo, $nombre, $usuario, $pass, $direccion, $nrocuit, $email, $id_especialidad, $tipo_prestador, $fechamat, $matricula_nac, $matricula1, $parametro2, $parametro3, $nivel2) {
        $query = "INSERT INTO profesio (idprof, nombre, usuario, pass, direccion, nrocuit, email, id_especialidad, tipo_prestador, fechamat, matricula_nac, parametro1, parametro2, parametro3, nivel2) VALUES ('$codigo', '$nombre', '$usuario', '$pass', '$direccion', '$nrocuit', '$email', '$id_especialidad', $tipo_prestador, '$fechamat', '$matricula_nac', '$matricula1', '$parametro2', '$parametro3', $nivel2)";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codigo) {
        $query = "DELETE FROM profesio WHERE idprof = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codigo, $nombre, $usuario, $pass, $direccion, $nrocuit, $email, $id_especialidad, $id_tipoprestador, $fechamat, $matricula_nac, $matricula1, $parametro2, $parametro3, $nivel2) {
        $query = "UPDATE profesio SET nombre = '$nombre', usuario = '$usuario', pass = '$pass', direccion = '$direccion', nrocuit = '$nrocuit', email = '$email', id_especialidad = '$id_especialidad', tipo_prestador = $id_tipoprestador, fechamat = '$fechamat', matricula_nac = '$matricula_nac', parametro1 = '$matricula1', parametro2 = '$parametro2', parametro3 = '$parametro3', nivel2 = $nivel2 WHERE idprof = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codigo) {
        $find = false;
        $query = "SELECT * FROM profesio WHERE idprof = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codigo = $fila['idprof'];
            $this->nombre = $fila['nombre'];
            $this->usuario = $fila['usuario'];
            $this->pass = $fila['pass'];
            $this->direccion = $fila['direccion'];
            $this->nrocuit = $fila['nrocuit'];
            $this->email = $fila['email'];
            $this->id_especialidad = $fila['id_especialidad'];
            $this->id_tipoprestador = $fila['tipo_prestador'];
            $this->fechamat = $fila['fechamat'];
            $this->matricula_nac = $fila['matricula_nac'];
            $this->baja = $fila['baja'];
            $this->parametro1 = $fila['parametro1'];
            $this->parametro2 = $fila['parametro2'];
            $this->parametro3 = $fila['parametro3'];
            $this->nivel2 = $fila['nivel2'];
            $find = true;
        }
        if ($find) {
            return true;
        } else {
            return null;
        }
    }

    function verificarUsuario($usuario, $pass) {
        $r = false;
        $query = "SELECT * FROM profesio WHERE usuario = '$usuario' AND pass = '$pass'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $r = true;
            $this->codigo = $fila['idprof'];
            $this->nombre = $fila['nombre'];
            $this->usuario = $fila['usuario'];
            $this->pass = $fila['pass'];
            $this->direccion = $fila['direccion'];
            $this->nrocuit = $fila['nrocuit'];
            $this->email = $fila['email'];
            $this->id_especialidad = $fila['id_especialidad'];
            $this->id_tipoprestador = $fila['tipo_prestador'];
            $this->fechamat = $fila['fechamat'];
            $this->matricula_nac = $fila['matricula_nac'];

            if ($fila['baja'] == 'S')
                $r = false;
        }
        return $r;
    }

    function cambiarpass($codigo, $pass) {
        $query = "UPDATE profesio SET pass = '$pass' WHERE idprof = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function bajaEfector($codigo, $estado) {
        if ($estado == 'S')
            $query = "UPDATE profesio SET baja = 'S' WHERE idprof = '$codigo'"; else
            $query = "UPDATE profesio SET baja = NULL WHERE idprof = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function findUsuario($usuario) {
        $r = false;
        $query = "SELECT * FROM profesio WHERE usuario = '$usuario'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $r = true;
            $this->codigo = $fila['idprof'];
            $this->nombre = $fila['nombre'];
            $this->usuario = $fila['usuario'];
            $this->pass = $fila['pass'];
            $this->direccion = $fila['direccion'];
            $this->nrocuit = $fila['nrocuit'];
            $this->email = $fila['email'];
            $this->id_especialidad = $fila['id_especialidad'];
            $this->id_tipoprestador = $fila['tipo_prestador'];
            $this->fechamat = $fila['fechamat'];
            $this->matricula_nac = $fila['matricula_nac'];
        }
        return $r;
    }

    function getEfectores($RegistrosAEmpezar, $RegistrosAMostrar, $filtro) {
        if ($filtro == '' or $filtro == 'undefined') {
            $query = "SELECT * FROM profesio ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        } else {
            $query = "SELECT * FROM profesio WHERE nombre LIKE '$filtro%' ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getPassword() {
        return $this->pass;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getNrocuit() {
        return $this->nrocuit;
    }

    function getEmail() {
        return $this->email;
    }

    function getId_especialidad() {
        return $this->id_especialidad;
    }

    function getId_tipoprestador() {
        return $this->id_tipoprestador;
    }

    function getSQL() {
        return $this->sql;
    }

    function getFechamat() {
        return $this->fechamat;
    }

    function getMatricula_nac() {
        return $this->matricula_nac;
    }
    
    function getNivel2() {
        return $this->nivel2;
    }

    function getProfesionales() {
        $query = "SELECT * FROM profesio ORDER BY nombre";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function verificarEspecialidad($id) {
        $r = false;
        $query = "SELECT id_especialidad FROM profesio WHERE id_especialidad = '$id'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $r = true;
            break;
        }
        return $r;
    }

}

?>
