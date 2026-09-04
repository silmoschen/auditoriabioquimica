<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

class cExportOrdenes {

//constructor
    var $modulo;
    var $items;
    var $linea;
    var $nroauditoria;
    var $it_error;
    var $nbu;
    var $res;
    var $eq;

    function cExportOrdenes() {
        $this->eq = new cEquivalenciaNBU();
        $this->nbu = new cNBU();
        $this->items = 0;
        $this->it_error = 0;
    }

    // inserta tupla
    function add($modulo, $linea, $nroauditoria) {
        $this->items = $this->items + 1;
        $query = "INSERT INTO export_temp (modulo, items, linea, nroauditoria) VALUES ('$modulo', $this->items, '$linea', $nroauditoria)";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function verificarNumeroAfiliado($id_beneficio) {  // 14/09/2011
        $existe = false;
        $query = "SELECT id_beneficio FROM export_temp WHERE modulo = 'AFILIADO' AND id_beneficio = '$id_beneficio'";
        $this->res = mysql_query($query);
        while ($l = mysql_fetch_array($this->res)) {
            $existe = true;
        }
        return $existe;
    }

    function addDP($modulo, $linea, $nroauditoria, $tipo_doc, $nro_doc, $codigo, $id_beneficio, $fecha) {
        if ($modulo == 'REL_PRACTICASSOLICITADASXAMBULATORIO') {  // No admite duplicados
            
            $query = "select nroauditoria from export_temp where modulo = 'REL_PRACTICASSOLICITADASXAMBULATORIO' and nroauditoria = $nroauditoria and codigo = '$codigo'";
            $result = mysql_query($query);

            $cant = 0;
            while ($l = mysql_fetch_array($result)) {
                $cant++;
            }

            if ($cant > 0) {
                $query = "update export_temp set cant = $cant where modulo = 'REL_PRACTICASSOLICITADASXAMBULATORIO' and nroauditoria = $nroauditoria and codigo = '$codigo'";
                $result = mysql_query($query);
                return false;
            }
        }
        
        if ($modulo == 'AMBULATORIO'){  // Solo se lo define una vez
            $query = "select nroauditoria from export_temp where modulo = 'AMBULATORIO' and tipo_doc = '$tipo_doc' and nrodoc = '$nro_doc' and fecha='$fecha'";

            $result = mysql_query($query);
             while ($l = mysql_fetch_array($result)) {
                return false;
            }        
        }

        $this->items = $this->items + 1;
        $query = "INSERT INTO export_temp (modulo, items, linea, nroauditoria,  tipo_doc, nrodoc, codigo, id_beneficio, fecha) VALUES ('$modulo', $this->items, '$linea', $nroauditoria,  '$tipo_doc', '$nro_doc', '$codigo', '$id_beneficio', '$fecha')";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function addDPM($modulo, $linea, $nroauditoria, $tipo_doc, $nro_doc, $codigo, $id_beneficio, $monto) {
        $this->items = $this->items + 1;
        $query = "INSERT INTO export_temp (modulo, items, linea, nroauditoria,  tipo_doc, nrodoc, codigo, id_beneficio, monto) VALUES ('$modulo', $this->items, '$linea', $nroauditoria,  '$tipo_doc', '$nro_doc', '$codigo', '$id_beneficio', $monto)";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function iniciar() {
        $query = "DELETE FROM export_temp";
        $result = mysql_query($query);
        $query = "DELETE FROM export_pacientes";
        $result = mysql_query($query);
        $query = "DELETE FROM export_errores";
        $result = mysql_query($query);
    }

    function finalizar() {
        $query = "DELETE FROM export_temp";
        $result = mysql_query($query);
        $query = "DELETE FROM export_pacientes";
        $result = mysql_query($query);
    }

    function getLineas($modulo) {
        $query = "SELECT * FROM export_temp WHERE modulo = '$modulo' ORDER by items";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getLineaUnica($modulo) {
        $query = "SELECT DISTINCT linea, id_beneficio FROM export_temp WHERE modulo = '$modulo' ORDER by items";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getOrdenes() {
        $query = "SELECT DISTINCT nroauditoria FROM export_temp WHERE nroauditoria <> '0'";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCantItemsPaciente($modulo, $tipo_doc, $nrodoc) {
        $cant = 0;
        $query = "SELECT COUNT(modulo) AS cantidad FROM export_temp WHERE modulo = '$modulo' AND tipo_doc = '$tipo_doc' AND nrodoc = '$nrodoc'";
        $this->res = mysql_query($query);
        while ($l = mysql_fetch_array($this->res)) {
            $cant = $l['cantidad'];
        }
        return $cant;
    }

    function getItemsPaciente($modulo, $tipo_doc, $nrodoc) {
        $query = "SELECT linea FROM export_temp WHERE modulo = '$modulo' AND tipo_doc = '$tipo_doc' AND nrodoc = '$nrodoc' ORDER BY linea";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getOrdenesPaciente($tipo_doc, $nrodoc) {
        $query = " SELECT * FROM export_temp where tipo_doc = '$tipo_doc' and nrodoc = '$nrodoc' and (modulo = 'AMBULATORIO' or modulo = 'REL_DIAGNOSTICOSXAMBULATORIO' or modulo = 'REL_PRACTICASREALIZADASXAMBULATORIO' or modulo = 'REL_PRACTICASSOLICITADASXAMBULATORIO')";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getLineasDatosPaciente() {
        $query = "SELECT * FROM export_pacientes";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getPracticasPaciente($modulo, $tipo_doc, $nrodoc) {
        $query = "SELECT codigo, tipo_doc, nrodoc, linea FROM export_temp WHERE modulo = '$modulo' AND tipo_doc = '$tipo_doc' AND nrodoc = '$nrodoc' ORDER BY codigo";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCantidadPracticasPaciente($modulo, $tipo_doc, $nrodoc, $codigo) {
        $cant = 1;
        $query = "SELECT COUNT(codigo) AS cantidad FROM export_temp WHERE modulo = '$modulo' AND tipo_doc = '$tipo_doc' AND nrodoc = '$nrodoc' AND codigo='$codigo'";
        $this->res = mysql_query($query);
        while ($l = mysql_fetch_array($this->res)) {
            $cant = $l['cantidad'];
        }
        return $cant;
    }

    function getListaPracticasPaciente($modulo) {
        $cant = 1;
        $query = "SELECT tipo_doc, nrodoc, codigo FROM export_temp WHERE modulo = '$modulo'";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getItems($modulo, $nroauditoria) {
        $query = "SELECT * FROM export_temp WHERE modulo = '$modulo' AND nroauditoria = $nroauditoria ORDER by items";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCodigosAutorizados() {
        $query = "SELECT distinct(codigo) FROM export_temp where modulo = 'REL_PRACTICASREALIZADASXAMBULATORIO' order by codigo";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getCantidadAutorizados($codigo) {
        $query = "SELECT COUNT(codigo) AS cant FROM export_temp where modulo = 'REL_PRACTICASREALIZADASXAMBULATORIO' and codigo = '$codigo'";
        $this->res = mysql_query($query);
        $c = 0;
        while ($l = mysql_fetch_array($this->res)) {
            $c = $l['cant'];
        }
        return $c;
    }

    function getMontoCodigosAutorizados($codigo) {
        $monto = 0;
        $query = "select monto FROM export_temp where modulo = 'REL_PRACTICASREALIZADASXAMBULATORIO' and codigo = '" . $codigo . "'";
        $this->res = mysql_query($query);
        while ($l = mysql_fetch_array($this->res)) {
            $monto = $l['monto'];
        }
        return $monto;
    }

    // inserta tupla
    function addPaciente($tipo_doc, $nrodoc, $nroauditoria, $id_beneficio, $linea) {
        // Tomamos como unico el ID de Beneficiario
        $found = false;
        $query = "SELECT tipo_doc, nrodoc FROM export_pacientes WHERE tipo_doc = '$tipo_doc' and nrodoc = '$nrodoc'";
        $result = mysql_query($query);
        while ($l = mysql_fetch_array($result)) {
            $found = true;
            break;
        }

        if ($found == false) {
            $query = "INSERT INTO export_pacientes (tipo_doc, nrodoc, nroauditoria, id_beneficio, linea) VALUES ('$tipo_doc', '$nrodoc', $nroauditoria, '$id_beneficio', '$linea')";
            $result = mysql_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    function getListaPacientes() {
        $query = "SELECT DISTINCT id_beneficio, tipo_doc, nrodoc FROM export_pacientes";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function getVerificarBeneficioAfiliado($tipo_doc, $nrodoc, $id_beneficio) {
        $found = false;
        $query = "SELECT tipo_doc, nrodoc, id_beneficio FROM export_pacientes WHERE tipo_doc = '$tipo_doc' AND nrodoc = '$nrodoc' AND id_beneficio = '$id_beneficio'";
        $result = mysql_query($query);
        while ($l = mysql_fetch_array($result)) {
            $found = true;
            break;
        }
        return $found;
    }

    // -- Tratamiento de Errores

    function addError($modulo, $error) {
        $this->it_error = $this->it_error + 1;
        $query = "INSERT INTO export_errores (modulo, items, error) VALUES ('$modulo', $this->it_error, '$error')";
        $result = mysql_query($query);
        if (!$result)
            return false;
        else
            return true;
    }

    function getListaErrores() {
        $query = "SELECT DISTINCT modulo, error FROM export_errores ORDER BY modulo, error";
        $this->res = mysql_query($query);
        return $this->res;
    }

    function VerificarErrores() {
        $found = false;
        $query = "SELECT COUNT(items) FROM export_errores";
        $result = mysql_query($query);
        while ($l = mysql_fetch_array($result)) {
            $found = true;
            break;
        }
        return $found;
    }

    // -- Validaciones Varias

    function validarDx($codigo) {
        $found = false;
        $query = "SELECT vch_coddiagnostico FROM diagnostico WHERE vch_coddiagnostico = '$codigo'";
        $result = mysql_query($query);
        while ($l = mysql_fetch_array($result)) {
            $found = true;
            break;
        }
        if ($found == false) {
            $this->addError('DIAGNOSTICOS', 'El Codigo de Diagnostico ' . $codigo . ' es Incorrecto');
        }
        return $found;
    }

    function validarPractica($codos, $codigo) {
        // Código Normal

        $found = false;
        $error = true;
        $query = "SELECT vch_codprestacion FROM practica WHERE vch_codprestacion = '$codigo'";
        $result = mysql_query($query);
        while ($l = mysql_fetch_array($result)) {
            $found = true;
            $error = false;
            break;
        }

        // Equivalencias
        if ($found == false) {
            $eq = '';
            $eq = $this->getEquivalencia($codos, $codigo);
            if ($eq != '') {
                $found = true;
                $error = false;
            }
        }

        if ($error == true) {  // Reportamos el Error
            $this->nbu->getObject($codigo);
            $this->addError('PRACTICAS', 'El Cód. de Practica ' . $codigo . ' - ' . $this->nbu->getDescrip() . ' es Incorrecto');
        }

        return $found;
    }

    function getEquivalencia($codos, $codigo) {
        return $this->eq->getCodigoEquivalente($codos, $codigo);
    }

}

?>