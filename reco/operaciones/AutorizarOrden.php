<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaCodigosNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');

$nroauditoria = $_REQUEST['nrotrans'];

$auditoria = new cAuditoria;
$nbu = new cNBU();
$obsocial = new cObsocial();
$wsres = new cWsRespuestas();
$utiles = new cUtiles();
$equivalencia = new cEquivalenciaCodigosNBU();
$efector = new cEfector();

$auditoria->getObject($nroauditoria);

$res = $auditoria->getDeterminaciones("'" . $nroauditoria . "'");

$codos = '';

$obsocial->verificarRPC($auditoria->getCodos());

$estado = ' ';

$tipo_envio = 1;

// FEDERADA
//==============================================================================

if ($obsocial->_reglaNegocio == 6) {

    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $pass = $obsocial->_pass;
    $nroautorizacion = $auditoria->transaccion;

    $metodo = 'ordenconsulta';

    $version = $obsocial->_parametro8;
    $version = 'v1.5.2';

    $parameters = '{"url":"' . $obsocial->_parametro7 . '/","metodo":"validador/' . $version . '/wsvolcs1","api":"x-api-key","apikey":"' . $pass . '"}';

    $efector->getObject($auditoria->getEfector());
    $nrocuit = str_replace('-', '', $efector->getNrocuit());

    $cuit = str_replace('-', '', $obsocial->_parametro5); // CUIT de la Asociacion

    $data = '{"p_Prestador":"' . $obsocial->_parametro3 . '",'
            . '"p_SubPrestador":"' . $obsocial->_parametro4 . '",'
            . '"p_NroSolicitud":"' . $nroautorizacion . '"}';

    $json_data = '{"parameters":' . $parameters . ',' .
            '"data":' . $data . '}"';

    $context = stream_context_create(array(
        'http' => array(
            'protocol_version' => 1.1,
            'user_agent' => 'PHPExample',
            "Cookie => foo=bar\r\n",
            'method' => 'PUT',
            'header' => "Content-type: application/json\r\n" .
            "Connection: close\r\n" .
            "Content-length: " . strlen($json_data) . "\r\n",
            'content' => $json_data,
            'Expect' => '100-continue'
        ),
    ));

    $theurl = $url . $metodo;

    $file = file_get_contents($theurl, false, $context);
    var_dump($file);

    $array = json_decode($file);

    $s = print_r($array, true);

    //echo $data . '<hr/>';
    //echo $file;
}

// SANCOR - v2
//==============================================================================

if ($obsocial->_reglaNegocio == 5) {

    $tipo_envio = 2;

    $codigo_ant = $obsocial->_parametro1;

    $expediente = $auditoria->expediente;

    echo "<fieldset>";
    echo "<legend>Ingrese el Número de Formulario4 y Presione Enter para Validar las Prácticas</legend>";
    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<td width='100px' align='right'>";
    echo 'Formulario 4 Nro.:';
    echo "</td>";
    echo "<td>";
    $na = '"' . $nroauditoria . '"';
    echo "<input type='text' name='form4' id='form4' width='200px'" . " onkeypress='javascript: if(ValidarForm4(event, form4.value, " . $na . "));'" . " value='" . $auditoria->expediente . "'>";
    echo " (oprima ENTER para Procesar)";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</fieldset>";

    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<tbody align = 'left'>";
    echo "<td width='80px'><b>Código</b>";
    echo "<td width='400px'><b>Determinación</b>";
    echo "</tr>";

    $requiere_aut = false;

    while ($MostrarFila = mysql_fetch_array($res)) {

        $nbu->getObject($MostrarFila['codigo']);
        $e = "";
        if ($MostrarFila['estado'] == 'A') {
            $e = "checked='checked'";
        }

        $estado = ' disabled = "true" ';

        $requiere_aut = true;
        $cod_eq = $MostrarFila['codigo'];

        echo "<tr>";
        echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $cod_eq . "  onClick='chequear()' " . '>' . $cod_eq . "</td>";
        echo "<td width='400px'>" . $nbu->getDescrip() . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo '<hr/>';


    echo "<table>";
    echo "<tr>";
    /*
      if ($orden_ok) {
      echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminacionesF4(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
      echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
      } else {
      echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
      echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
      }
     */
    echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';
    echo "</tr>";
    echo "</table>";

    return;
}

// SANCOR
//==============================================================================

if ($obsocial->_reglaNegocio == 5) {

    $tipo_envio = 2;

    $codigo_ant = $obsocial->_parametro1;

    $expediente = $auditoria->expediente;

    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<td width='100px'>Formulario (4) Nro.:";
    echo "<td width='300px'>";
    echo "<input type='text' id='formulario4' value= '" . $expediente . "' width='200px' /></td>";
    echo "</tr></table>";

    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<tbody align = 'left'>";
    echo "<td width='80px'><b>Código</b>";
    echo "<td width='400px'><b>Determinación</b>";
    echo "</tr>";

    $requiere_aut = false;

    while ($MostrarFila = mysql_fetch_array($res)) {

        $nbu->getObject($MostrarFila['codigo']);
        $e = "";
        if ($MostrarFila['estado'] == 'A') {
            $e = "checked='checked'";
            //$estado = ' disabled = "true" ';       
            $estado = '';
        } else {
            //$requiere_aut = true;
            $estado = '';
        }

        $requiere_aut = true;
        $cod_eq = $MostrarFila['codigo'];

        echo "<tr>";
        echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $cod_eq . "  onClick='chequear()' " . '>' . $cod_eq . "</td>";
        echo "<td width='400px'>" . $nbu->getDescrip() . $mensaje . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo '<hr/>';

    echo "<table>";
    echo "<tr>";
    echo '<td width="15" align = "left">Código</td>';
    echo '<td width="20" align = "left"><input type="text" width="100px" id="txtcodigo1"/></td>';
    echo '<td width="50" align = "left"><input type="button" id="btncodigo1" value="Agregar Práctica" class="button green small" onclick="btnadd1(txtcodigo1.value, ' . "'$nroauditoria'" . ', formulario4.value);"/></td>';
    echo '<td align = "left"><div id="practica1"/></td>';
    echo "</tr>";
    echo "</table>";
    echo '<div id="err"/>';

    if ($requiere_aut) {
        $orden_ok = true;
    } else {
        echo '<h3>LA ORDEN NO NECESITA AUTORIZACIÓN o YA FUÉ AUTORIZADA</h3>';
    }

    echo "<hr/>";

    echo "<table>";
    echo "<tr>";
    if ($orden_ok) {
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminacionesF4(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    } else {
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    }
    echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';
    echo "</tr>";
    echo "</table>";

    return;
}

//==============================================================================
//
// INGENIEROS
if ($obsocial->_reglaNegocio == 8) {

    $codigo_ant = $obsocial->_parametro1;
    // Envoltura RPC
    // Actualizacion
    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $pass = $obsocial->_pass;

    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<tbody align = 'left'>";
    echo "<td width='80px'><b>Código</b>";
    echo "<td width='400px'><b>Determinación</b>";
    echo "</tr>";

    $metodo = 'consultarorden';

    $turl = $obsocial->_parametro7 . '/Orden/' . $auditoria->nroautorizacion . '?cuit=' . $obsocial->_parametro5;
    $parameters = '{"url":"' . $turl . '","metodo":"","api":"Authorization","apikey":"' . $obsocial->_parametro1 . '","username":"' . $usuario . '","password":"' . $pass . '"}';

    $data = '{}';
    $json_data = '{"parameters":' . $parameters . ',' .
            '"orden":' . $data . '}"';

    $theurl = $url . $metodo;

    $context = stream_context_create(array(
        'http' => array(
            'protocol_version' => 1.1,
            'user_agent' => 'PHPExample',
            "Cookie => foo=bar\r\n",
            'method' => 'PUT',
            'header' => "Content-type: application/json\r\n" .
            "Connection: close\r\n" .
            "Content-length: " . strlen($json_data) . "\r\n",
            'content' => $json_data,
            'Expect' => '100-continue'
        ),
    ));

    $file = file_get_contents($theurl, false, $context);

    $result = json_decode($file);

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $file, 1);

    while ($MostrarFila = mysql_fetch_array($res)) {

        $nbu->getObject($MostrarFila['codigo']);
        $e = "";
        if ($MostrarFila['estado'] == 'A')
            $e = "checked='checked'";

        $estado = ' disabled = "true" ';

        // Verificamos el estado de la práctica
        for ($p = 0; $p < count($result->Prestaciones); $p++) {

            if ($result->Prestaciones[$p]->Codigo == $MostrarFila['codigo']) {
                $_es = 'R';
                if ($result->Prestaciones[$p]->Autorizado == 'S')
                    $_es = 'A';
                else
                    $e = '';

                $auditoria->actualizarDeterminacion($nroauditoria, $result->Prestaciones[$p]->Codigo, $_es);

                $cod_eq = $result->Prestaciones[$p]->Codigo;
            }
        }

        echo "<tr>";
        echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $cod_eq . "  onClick='chequear()' " . '>' . $cod_eq . "</td>";
        echo "<td width='400px'>" . $nbu->getDescrip() . $mensaje . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo '<hr/>';
    echo '<h2>' . $result->Estado . '</h2>';
    echo '<h3>' . $result->Observacion . '</h3>';

    echo '<hr/>';
    echo $file;
    echo '<hr/>';

    if ($result->Estado == 'APROBADA')
        $orden_ok = true;
    else
        $orden_ok = false;

    echo "<tr>";
    if ($orden_ok) {
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    } else {
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    }
    echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';
    echo "</tr>";
    echo "</table>";

    return;
}

// FEDERADA
if ($obsocial->_reglaNegocio == 6) {

    $codigo_ant = $obsocial->_parametro1;
    // Envoltura RPC
    // Actualizacion
    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $pass = $obsocial->_pass;

    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<tbody align = 'left'>";
    echo "<td width='80px'><b>Código</b>";
    echo "<td width='400px'><b>Determinación</b>";
    echo "</tr>";

    $prestacion = $result->prestaciones;

    // Recuperamos el json de la orden
    $query = $wsres->getSQL1(2, $auditoria->getCodos(), $auditoria->getNrodoc(), $auditoria->nroautorizacion);

    $respuesta = '{}';
    while ($l = mysql_fetch_array($query)) {
        $respuesta = $l['respuesta'];
    }

    $result = json_decode($respuesta);

    $metodo = 'consultapractica';

    $rss = '';

    while ($MostrarFila = mysql_fetch_array($res)) {

        $nbu->getObject($MostrarFila['codigo']);
        $e = "";
        if ($MostrarFila['estado'] == 'A')
            $e = "checked='checked'";

        $estado = ' disabled = "true" ';

        // Verificamos el estado de la práctica
        for ($p = 0; $p < count($result->o_ListaPrestacionesValidadas); $p++) {
            echo '<h1>';
            if ($result->o_ListaPrestacionesValidadas[$p]->PstCod == $MostrarFila['codigo']) {

                $data = '{"p_Prestador":"' . $obsocial->_parametro3 . '",'
                        . '"p_SubPrestador":"' . $obsocial->_parametro4 . '",'
                        . '"p_SubPreCUIT":null,'
                        . '"p_NroAutorizacion":"' . $result->o_ListaPrestacionesValidadas[$p]->NroAutorizacion . '"}';

                //$theurl = 'http://localhost:10060/api/federada/consultapractica?id=' . $data . '&key1={"url":"https://api-test.federada.com/","metodo":"validador/v1.5.2/wsvolca1","api":"x-api-key","apikey":"OmUu2GSw1R1a4QqESLaK48YdXGy90Zx62TO7TDX7"}';
                $parameters = '{"url":"' . $obsocial->_parametro7 . '/","metodo":"validador/v1.5.2/wsvolca1","api":"x-api-key","apikey":"' . $pass . '"}';
                $theurl = $url . $metodo . '?id=' . $data . '&key1=' . $parameters;

                $file = file_get_contents($theurl, false, $context);

                $array = json_decode($file);

                $mensaje = '   (<i>' . $array->o_Status . ' ' . $array->o_StatusAutorizacion . ' ' . ' ' . $array->o_Comentario . '</i>)';

                $_es = 'R';
                if ($array->o_StatusAutorizacion == 'AT')
                    $_es = 'A';

                $auditoria->actualizarDeterminacion($nroauditoria, $MostrarFila['codigo'], $_es);

                $wsres->crear($auditoria->getCodos(), $auditoria->getEfector(), $auditoria->getNrodoc(), $file, 3);

                $rss .= $file . '<br/>';

                $orden_ok = true;

                break;
            }
        }

        echo "<tr>";
        echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $cod_eq . "  onClick='chequear()' " . '>' . $cod_eq . "</td>";
        echo "<td width='400px'>" . $nbu->getDescrip() . $mensaje . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo '<br/>';

    echo "<tr>";
    if ($orden_ok) {
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    } else {
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    }
    echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';
    echo "</tr>";
    echo "</table>";
    echo '<hr/>';
    echo $rss;
    echo '<hr/>';
    return;
}

// AMUR
if ($obsocial->_parametro2 == 'REQUIEREEXPEDIENTE' && $obsocial->_reglaNegocio == 4) {

    $codigo_ant = $obsocial->_parametro1;
    // Envoltura RPC
    // Actualizacion
    $url = $obsocial->_url;
    $usuario = $obsocial->_user;
    $pass = $obsocial->_pass;
    $naut = str_replace("WS", "", $auditoria->transaccion);
    $faut = $utiles->getFechaAAAA_MM_DD($auditoria->getFecha());
    //echo $auditoria->transaccion;

    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    $result = $client->__soapCall('fnconsulta', array('usuario' => $usuario, 'pass' => $pass, 'naut' => $naut, 'faut' => $faut));
    
    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<tbody align = 'left'>";
    echo "<td width='80px'><b>Código</b>";
    echo "<td width='400px'><b>Determinación</b>";
    echo "</tr>";

    //$__codigoparcial = [];
    //$__cantidadparcial = [];
    //$__cantidadtotal = [];
    //$__mensaje = [];
    $__codigoparcial = array();
    $__cantidadparcial = array();
    $__cantidadtotal = array();
    $__mensaje = array();
    $__index = 0;
    $__codanter = '';

    $prestacion = $result->prestaciones;

    while ($MostrarFila = mysql_fetch_array($res)) {
        if ($obsocial->_parametro2 == 'REQUIEREEXPEDIENTE' && $obsocial->_reglaNegocio == 4)
            $estado = ' disabled = "true" ';

        $nbu->getObject($MostrarFila['codigo']);
        $e = "";
        if ($MostrarFila['estado'] == 'A')
            $e = "checked='checked'";

        // Recorremos las prácticas y marcamos las autorizadas  
        $mensaje = '';
        $_es = 'R';
        for ($p = 0; $p < count($prestacion); $p++) {
            //echo $auditoria->getCodos() . ' - ' . $MostrarFila['codigo'];
            $__c = trim($codigo_ant) . $prestacion[$p]->cpre;

            // Verificamos que el código no tenga equivalencias
            $cod_eq = $equivalencia->getCodigoEquivalente($auditoria->getCodos(), $MostrarFila['codigo']);

            if ($cod_eq != '')
                $__c = $cod_eq;

            // 20/08/2015
            $cod_eq = $prestacion[$p]->cpre;
            if (strlen(trim($cod_eq)) == 3)
                $cod_eq = '660' . $cod_eq;
            if (strlen(trim($cod_eq)) == 4)
                $cod_eq = '66' . $cod_eq;
            $__c = $cod_eq;
            //------------------------

            if (trim($__c) == '00001')
                $__c = '660001';  // 23/12/2015

            if (trim($__c) == trim($MostrarFila['codigo'])) {

                if ($prestacion[$p]->estadop == "AA" || $prestacion[$p]->estadop == "OA") {
                    $_es = 'A';
                    $e = "checked='checked'";
                } else {
                    $e = '';
                    $_es = 'R';
                }

                $mensaje = ' (' . $prestacion[$p]->motivo . ')';

                $auditoria->actualizarDeterminacion($nroauditoria, $__c, $_es);

                // Códigos con cantidades parciales
                if ($prestacion[$p]->estadop == "AP" && $__c != $__codanter) {
                    $__codigoparcial[$__index] = $__c;
                    $__cantidadparcial[$__index] = $prestacion[$p]->canta;
                    $__cantidadtotal[$__index] = $prestacion[$p]->cantp;
                    $__mensaje[$__index] = $prestacion[$p]->motivo;
                    $__index = $__index + 1;
                    $__codanter = $__c;
                }

                //echo '<h2>' . $__c . '  ' . $MostrarFila['codigo'] . ' ' . $mensaje;

                break;
            }
        }

        //------------------------------------------------------------------
        // Damos de alta los codigos agregados a las Obra Social
        for ($p = 0; $p < count($prestacion); $p++) {
            // Verificamos que el código no tenga equivalencias
            $cod_eq = $prestacion[$p]->cpre;

            if (strlen(trim($cod_eq)) == 3)
                $cod_eq = '660' . $cod_eq;
            if (strlen(trim($cod_eq)) == 4)
                $cod_eq = '66' . $cod_eq;

            //echo $cod_eq . '   ' . $__c . ' ' . $result->estado . '<br/>';

            if (trim($cod_eq) == '00001')
                $cod_eq = '660001';
            if ($auditoria->verificarDeterminacion($nroauditoria, $cod_eq) == null) {
                //echo '<h1>'. $cod_eq . '<br/>';
                $auditoria->actualizarItemsExternos($nroauditoria, $p + 1, $cod_eq, $auditoria->efector, $auditoria->codos, $auditoria->fecha, $auditoria->nrodoc, $auditoria->estado);

                $nbu->getObject($cod_eq);

                if ($prestacion[$p]->estadop == "AA") {
                    $_es = 'A';
                    $e = "checked='checked'";
                } else {
                    $e = '';
                    $_es = 'R';
                }

                echo "<tr>";
                echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $cod_eq . "  onClick='chequear()' " . '>' . $cod_eq . "</td>";
                echo "<td width='400px'>" . $nbu->getDescrip() . $mensaje . "</td>";
                echo "</tr>";
            }
        }

        echo "<tr>";
        echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $MostrarFila['codigo'] . "  onClick='chequear()' " . '>' . $MostrarFila['codigo'] . "</td>";
        echo "<td width='400px'>" . $nbu->getDescrip() . $mensaje . "</td>";
        echo "</tr>";
    }
    echo "</table>";


    echo '<div align="left">';
    echo '<h3>Prácticas con Autorización Parcial</h3>';

    for ($x = 0; $x <= $__index - 1; $x++) {
        echo 'Práctica: <b>' . $__codigoparcial[$x] . '</b> - Pedidos: <b>' . $__cantidadtotal[$x] . ' </b>Autorizados: <b>' . $__cantidadparcial[$x] . '</b>' .
        ' </b>Motivo: <b>' . $__mensaje[$x] . '</b><br/>';

        $auditoria->actualizarDeterminacionXCantidad($nroauditoria, $__c, 'A', $__cantidadparcial[$x]);
    }

    echo '</div>';

    if ($result->estado == 'AA')
        echo '<h3>Estado: SOLICITUD AUTORIZADA</h3>';
    if ($result->estado == 'CA')
        echo '<h3>Estado: SOLICITUD AUTORIZADA PARCIALMENTE</h3>';
    if ($result->estado == 'PA')
        echo '<h3>Estado: SOLICITUD PENDIENTE DE AUDITORIA MEDICA</h3>';
    if ($result->estado == 'XX')
        echo '<h3>Estado: SOLICITUD DENEGADA</h3>';

    if ($result->mensajes != '')
        echo '<h4>' . $result->mensajes . '</h4>';

    echo '<b><font size="1" color="red">' . $result->mensajes . '</font></b>';

    if ($result->mensajes == 'FALTAN DATOS')
        echo '<br><b><font size="1" color="red">La Orden Ingresada No es Válida. El Diagnóstico ó alguno de los Datos son Incorrectos para esta Obra Social</font></b><br/>';


    $orden_ok = false;
    if ($result->estado == 'AA' || $result->estado == 'CA') {
        echo '<br/><b><font size="2" color="navy">Solicitud Auditada por la Obra Social. <br/>Haga Click en Registrar Cambios y luego Imprima el Cupón</font></b>';
        echo '<br/>';
        $orden_ok = true;
    }

    $s = print_r($result, true);
    //echo $s;
    //echo '<h1>Estado:' . $result->estado . '</h1>';    
    //$wsres->crear($codos, $efector, $nrodoc, $s, 3);   
    //echo $s;
    $wsres->crear($auditoria->getCodos(), $auditoria->getEfector(), $auditoria->getNrodoc(), $s, 3);
    $tipo_envio = 1;    
    echo "<tr>";
    echo '<hr/>';
    if ($orden_ok) {        
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    } else {
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnRegistrarConsulta" value="Registrar Cambios" onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
        echo '<td width="150" align = "left"><input type="button" disabled="true" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    }
    echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';
    echo "</tr>";
    echo "</table>";
    echo '<hr/>';
    print_r($result);    
    echo '<hr/>';
    return;
}

if ($obsocial->_parametro2 == 'REQUIEREEXPEDIENTE' && $obsocial->_reglaNegocio == 3) {
    echo "<fieldset>";
    echo "<legend>Ingrese el Número de Expediente y Presione Enter para Validar las Prácticas</legend>";
    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<td width='100px' align='right'>";
    echo 'Expediente Nro.:';
    echo "</td>";
    echo "<td>";
    $na = '"' . $nroauditoria . '"';
    echo "<input type='text' name='expediente' id='expediente' width='200px'" . " onkeypress='javascript: if(ValidarExpediente(event, expediente.value, " . $na . "));'" . " value='" . $auditoria->expediente . "'>";
    echo " (oprima ENTER para Procesar)";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "</fieldset>";
    $tipo_envio = 2;

    echo "<script>document.getElementById('expediente').focus();</script>";
} else
    echo '<hr>';

echo "<table border='0px' width='550px'>";
echo "<tr>";
echo "<tbody align = 'left'>";
echo "<td width='80px'><b>Código</b>";
echo "<td width='400px'><b>Determinación</b>";
echo "</tr>";
while ($MostrarFila = mysql_fetch_array($res)) {
    if ($obsocial->_parametro2 == 'REQUIEREEXPEDIENTE' && $obsocial->_reglaNegocio == 3)
        $estado = ' disabled = "true" ';

    $obs = '';
    if ($obsocial->_parametro2 == 'REQUIEREEXPEDIENTE' && $obsocial->_reglaNegocio == 5) {// SANCOR
        $estado = ' ';
        if ($MostrarFila['estado'] == 'A')
            $estado = ' disabled = "true" ';

        $es = $auditoria->getObservacionDeterminacion($nroauditoria, $MostrarFila['codigo']);

        if (substr(trim($es), 0, 1) != '0') {
            $estado = '';
            $obs = "  ==> Obs.: " . $es;
        }
    }

    $nbu->getObject($MostrarFila['codigo']);
    $e = "";
    if ($MostrarFila['estado'] == 'A')
        $e = "checked='checked'";

    echo "<tr>";
    echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $MostrarFila['codigo'] . "  onClick='chequear()' " . '>' . $MostrarFila['codigo'] . "</td>";
    echo "<td width='400px'>" . $nbu->getDescrip() . $obs . "</td>";
    echo "</tr>";
}
echo "</table>";

$estado = ' ';
if ($obsocial->_parametro2 == 'REQUIEREEXPEDIENTE' && $obsocial->_reglaNegocio == 3)
    $estado = ' disabled = "true" alt="Debe Validar Primero el Numero de Expediente" ';

echo "<h3>*** Orden Sujeta a Autorización Externa ***</h3>";

echo "<table border='0px' width='550px'>";
if ($obsocial->_parametro2 != 'REQUIEREEXPEDIENTE') {
    echo "<tr>";
    echo "<td colspan='3' align='left'>";
    echo 'Selecciona las Prácticas que han sido Autorizadas';
    echo "</td>";
    echo "</tr>";
}
echo "<tr>";
echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnRegistrar" value="Registrar Cambios"' . $estado . ' onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';
echo "</tr>";
echo "</table>";

echo '<hr>';
?>
