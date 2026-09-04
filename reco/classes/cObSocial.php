<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

//implementamos la clase empleado
class cObsocial {

    //constructor
    var $codigo;
    var $nombre;
    var $factnbu;
    var $bonos;
    var $medicos_cab;
    var $soportemag;
    var $usuario;
    var $pass;
    var $derivacion;
    var $inc_leyenda;
    var $leyenda;
    var $ing_continuo;
    var $incluye_ab;
    var $aut_directa;
    var $coseguro;
    var $coseguro_mf;
    var $alta_paciente;
    var $nivel3;
    var $tope_anual;
    var $practicas_rechazadas;
    var $sql;
    var $_rpc;
    var $_user;
    var $_pass;
    var $_url;
    var $_parametro1;
    var $_parametro2;
    var $_parametro3;
    var $_parametro4;
    var $_parametro5;
    var $_parametro6;
    var $_parametro7;
    var $_parametro8;
    var $_parametro9;
    var $_parametro10;
    var $_parametro11;
    var $_parametro12;
    var $_parametro13;
    var $_reglaNegocio;
    var $_topePracticas;
    var $leyenda_os;
    var $orden_completa;
    var $inactiva;
    var $gen_nroautorizacion;
    var $nivel2;

    // inserta tupla
    function crear($codigo, $nombre, $factnbu, $bonos, $medicos_cab, $soportemag, $usuario, $pass, $derivacion, $inc_leyenda, $leyenda, $ing_continuo, $incluye_ab, $aut_directa, $coseguro, $alta_paciente, $coseguro_mf, $nivel3, $tope_anual, $practicas_rechazadas, $orden_completa, $inactiva, $gen_nroautorizacion) {
        $query = "INSERT INTO obsocial (codos, nombre, factnbu, bonos, medicos_cab, soportemag, usuario, pass, derivacion, inc_leyenda, leyenda, ing_continuo, incluye_ab, aut_directa, coseguro, alta_paciente, coseguro_mf, nivel3, tope_anual, practicas_rechazadas, orden_completa, inactiva, gen_nroautorizacion, nivel2) VALUES " .
                "('$codigo', '$nombre', '$factnbu', '$bonos', '$medicos_cab', '$soportemag', '$usuario', '$pass', '$derivacion', '$inc_leyenda', '$leyenda', '$ing_continuo', '$incluye_ab', '$aut_directa', '$coseguro', '$alta_paciente', '$coseguro_mf', '$nivel3', '$tope_anual', '$practicas_rechazadas',"
                . "$orden_completa, $inactiva, $gen_nroautorizacion, $nivel2)";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // borra tupla
    function borrar($codigo) {
        $query = "DELETE FROM obsocial WHERE codos = " . $codigo;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    // actualiza tupla
    function actualizar($codigo, $nombre, $factnbu, $bonos, $medicos_cab, $soportemag, $us, $pa, $derivacion, $inc_leyenda, $leyenda, $ing_continuo, $incluye_ab, $aut_directa, $coseguro, $alta_paciente, $coseguro_mf, $nivel3, $tope_anual, $practicas_rechazadas, $orden_completa, $inactiva, $gen_nroautorizacion, $nivel2) {
        $query = "UPDATE obsocial SET nombre = '$nombre', factnbu = '$factnbu', bonos = '$bonos', medicos_cab = '$medicos_cab', soportemag = '$soportemag', " .
                "usuario = '$us', pass = '$pa', derivacion = '$derivacion', inc_leyenda = '$inc_leyenda', leyenda = '$leyenda' , ing_continuo = '$ing_continuo', " .
                "incluye_ab = '$incluye_ab', aut_directa = '$aut_directa', coseguro = '$coseguro', alta_paciente = '$alta_paciente', coseguro_mf = '$coseguro_mf', " .
                "nivel3 = '$nivel3', tope_anual = '$tope_anual', practicas_rechazadas = '$practicas_rechazadas', orden_completa = $orden_completa, inactiva = $inactiva, gen_nroautorizacion = $gen_nroautorizacion, nivel2 = $nivel2 WHERE codos = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);

        if (!$result)
            return false;
        else
            return true;
    }

    function getObject($codigo) {
        $found = false;
        $query = "SELECT * FROM obsocial WHERE codos = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->codigo = $fila['codos'];
            $this->nombre = $fila['nombre'];
            $this->factnbu = $fila['factnbu'];
            $this->bonos = $fila['bonos'];
            $this->medicos_cab = $fila['medicos_cab'];
            $this->soportemag = $fila['soportemag'];
            $this->usuario = $fila['usuario'];
            $this->pass = $fila['pass'];
            $this->derivacion = $fila['derivacion'];
            $this->inc_leyenda = $fila['inc_leyenda'];
            $this->leyenda = $fila['leyenda'];
            $this->ing_continuo = $fila['ing_continuo'];
            $this->incluye_ab = $fila['incluye_ab'];
            $this->aut_directa = $fila['aut_directa'];
            $this->coseguro = $fila['coseguro'];
            $this->alta_paciente = $fila['alta_paciente'];
            $this->coseguro_mf = $fila['coseguro_mf'];
            $this->nivel3 = $fila['nivel3'];
            $this->tope_anual = $fila['tope_anual'];
            $this->practicas_rechazadas = $fila['practicas_rechazadas'];
            $this->orden_completa = $fila['orden_completa'];
            $this->inactiva = $fila['inactiva'];
            $this->gen_nroautorizacion = $fila['gen_nroautorizacion'];
            $this->nivel2 = $fila['nivel2'];
            $found = true;
        }
        return $found;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getFactNbu() {
        return $this->factnbu;
    }

    function getBonos() {
        return $this->bonos;
    }

    function getMedicos_cab() {
        return $this->medicos_cab;
    }

    function getSQL() {
        return $this->sql;
    }

    function getSoporteMag() {
        return $this->soportemag;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getPass() {
        return $this->pass;
    }

    function getDerivacion() {
        return $this->derivacion;
    }

    function getInc_leyenda() {
        return $this->inc_leyenda;
    }

    function getLeyenda() {
        return $this->leyenda;
    }

    function getIng_continuo() {
        return $this->ing_continuo;
    }

    function getIncluye_ab() {
        return $this->incluye_ab;
    }

    function getAutorizacionDirecta() {
        return $this->aut_directa;
    }

    function getCoseguro() {
        return $this->coseguro;
    }

    function getAltaPaciente() {
        return $this->alta_paciente;
    }

    function getCoseguroMontoFijo() {
        return $this->coseguro_mf;
    }

    function getNivel3() {
        return $this->nivel3;
    }

    function getTopeAnual() {
        return $this->tope_anual;
    }

    function getPracticasRechazadas() {
        return $this->practicas_rechazadas;
    }

    function getOrdenCompleta() {
        return $this->orden_completa;
    }

    function getInactiva() {
        return $this->inactiva;
    }

    function getGeneraNroAutorizacion() {
        return $this->gen_nroautorizacion;
    }
    
    function getNivel2() {
        return $this->nivel2;
    }

    function getObrasSociales() {
        $query = "SELECT * FROM obsocial ORDER BY nombre";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getObrasSocialesExporta() {
        $query = "SELECT * FROM obsocial WHERE soportemag = 'S' ORDER BY nombre";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getListaObsocial($filtro, $RegistrosAEmpezar, $RegistrosAMostrar) {
        if ($filtro == '' or $filtro == 'undefined') {
            $query = "SELECT * FROM obsocial ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        } else {
            $query = "SELECT * FROM obsocial WHERE nombre LIKE '$filtro%' ORDER BY nombre LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
        }
        $this->res = mysql_query($query);
        return $this->res;
    }

    function verificarRPC($codigo) {
        $this->_rpc = false;
        $this->_user = '';
        $this->_pass = '';
        $this->_url = '';
        $this->_parametro1 = '';
        $this->_parametro2 = '';
        $this->_parametro3 = '';
        $this->_reglaNegocio = 0;
        $this->_reglaN = '';
        $this->_parametro4 = '';
        $this->_parametro5 = '';
        $this->_parametro6 = '';
        $this->_parametro7 = '';
        $this->_parametro8 = '';
        $this->_parametro9 = '';
        $this->_parametro10 = '';
        $this->_parametro11 = '';
        $this->_parametro12 = '';
        $this->_parametro13 = '';
        $this->_topePracticas = 0;
        $query = "SELECT * FROM ws_rpc WHERE codos = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->_rpc = true;
            $this->_user = $fila['userID'];
            $this->_pass = $fila['pass'];
            $this->_url = $fila['rpc'];
            $this->_parametro1 = $fila['parametro1'];
            $this->_parametro2 = $fila['parametro2'];
            $this->_parametro3 = $fila['parametro3'];
            $this->_parametro4 = $fila['parametro4'];
            $this->_parametro5 = $fila['parametro5'];
            $this->_parametro6 = $fila['parametro6'];
            $this->_parametro7 = $fila['parametro7'];
            $this->_parametro8 = $fila['parametro8'];
            $this->_parametro9 = $fila['parametro9'];
            $this->_parametro10 = $fila['parametro10'];
            $this->_parametro11 = $fila['parametro11'];
            $this->_parametro12 = $fila['parametro12'];
            $this->_parametro13 = $fila['parametro13'];
            $this->_reglaNegocio = $fila['regla'];
            $this->_reglaN = $fila['regla'];
            $this->_topePracticas = $fila['tope_practicas'];
        }
        return $this->_rpc;
    }
    
    function getObSocialReglaJSON($codos) {      
        $query = "SELECT regla FROM ws_rpc WHERE codos = '$codos'";
        $res = mysql_query($query);

        $rows = null;
        while ($row = mysql_fetch_array($res)) {
            $rows = $row;            
        }

        return json_encode($rows);         
    }

    function getRespuestaRPC($codos, $nrodoc, $expediente, $regla) {
        $r = '';

        $query = "SELECT respuesta FROM ws_respuestas WHERE codos = '$codos' and nrodoc = '$nrodoc' and expediente = '$expediente' and tipo = $regla";
        //echo $query;
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $r = $fila['respuesta'];
        }

        return $r;
    }

    function getObrasSocialesRPC($regla) {
        $query = "SELECT codos FROM ws_rpc WHERE regla = $regla";
        return mysql_query($query);
    }

    function getParametro1() {
        return $this->_parametro1;
    }

    function getParametro2() {
        return $this->_parametro2;
    }

    function getParametro3() {
        return $this->_parametro3;
    }

    function getParametro4() {
        return $this->_parametro4;
    }

    function getParametro5() {
        return $this->_parametro5;
    }

    function getParametro6() {
        return $this->_parametro6;
    }

    function getParametro7() {
        return $this->_parametro7;
    }

    function getParametro8() {
        return $this->_parametro8;
    }

    function getParametro9() {
        return $this->_parametro9;
    }

    function getParametro10() {
        return $this->_parametro10;
    }

    function getParametro11() {
        return $this->_parametro11;
    }

    function getParametro12() {
        return $this->_parametro12;
    }

    function getParametro13() {
        return $this->_parametro13;
    }

    function getLeyendaOS() {
        return $this->leyenda_os;
    }

    function findLeyenda($codigo) {
        $query = "SELECT * FROM leyendas_os WHERE codos = '$codigo'";
        $resultado = mysql_query($query);
        $found = false;
        while ($fila = mysql_fetch_array($resultado)) {
            $found = true;
        }
        return $found;
    }

    function registrarLeyenda($codigo, $leyenda) {
        if (!$this->findLeyenda($codigo))
            $query = "insert into leyendas_os (codos, descrip) values ('$codigo', '$leyenda')";
        else
            $query = "update leyendas_os set descrip = '$leyenda' where codos = '$codigo'";

        $this->sql = $query;
        echo $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function borrarLeyenda($codigo) {
        $query = "DELETE FROM leyendas_os WHERE codos = " . $codigo;
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObjectLeyenda($codigo) {
        $this->leyenda_os = '';
        $query = "SELECT codos, descrip FROM leyendas_os WHERE codos = '$codigo'";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            $this->leyenda_os = $fila['descrip'];
        }
    }

    function getListLeyendas() {
        $query = "SELECT * FROM leyendas_os";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getListObrasSocialesUsuarios($codigos) {
        $query = "SELECT * FROM obsocial WHERE codos IN ($codigos)";
        //echo $query;
        $this->res = mysql_query($query);
        return $this->res;
    }

    function excluirValidacion($codigo) {
        $query = "INSERT IGNORE obsocial_excluir_val (codos) VALUES ('$codigo')";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function borrarValidacion($codigo) {
        $query = "DELETE FROM obsocial_excluir_val WHERE codos = '$codigo'";
        $this->sql = $query;
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getObsocialValidacion($codigo) {
        $this->leyenda_os = '';
        $query = "SELECT codos FROM obsocial_excluir_val WHERE codos = '$codigo'";
        $resultado = mysql_query($query);
        $found = false;
        while ($fila = mysql_fetch_array($resultado)) {
            $found = true;
        }
        return $found;
    }

    function procesarOsExcluida($codigo) {
        if ($this->getObsocialValidacion($codigo))
            $this->borrarValidacion($codigo);
        else
            $this->excluirValidacion($codigo);
    }

}

?>