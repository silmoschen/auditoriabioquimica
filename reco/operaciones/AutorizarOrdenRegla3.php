<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");

$nroauditoria = $_REQUEST['nrotrans'];
$expediente = $_REQUEST['expediente'];

$utiles = new cUtiles();

$auditoria = new cAuditoria;
$nbu = new cNBU();

$obsocial = new cObsocial();
$afiliado = new cAfiliados();
$wsres = new cWsRespuestas();
$entidad = new cEntidad();
$entidad->getObject(1);

$auditoria->getObject($nroauditoria);

$codos = '';

$obsocial->verificarRPC($auditoria->getCodos());

$estado = ' ';

$expedienteOK = false;

//==============================================================================
// JERARQUICO
if ($obsocial->_reglaNegocio == 3) {

    try {

        // 08/03/2017
        $auditoria->marcarItemsAutorizados($nroauditoria); // Por defecto, autorizamos todo

        $client = new SoapClient($obsocial->_url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

        $us = $auditoria->getNrodoc();
        $so = substr($us, 0, strlen($us) - 2);
        $or = substr($us, strlen($us) - 2, 2);

        $CriterioExpedienteAutorizacionSocio = array('FechaDeReferencia' => $utiles->getFechaAAAA_MM_DD($utiles->getFechaActual()),
            'IdSocio' => null,
            'NumeroExpedienteAutorizacion' => $expediente,
            'NumeroSocio' => $so,
            'OrdenSocio' => $or);

        $SolicitudObtencionExpedienteAutorizacion = array('CriterioExpedienteAutorizacionSocio' => $CriterioExpedienteAutorizacionSocio);

        //Llamada al servicio pasando el parámetro
        $ready = $client->ObtenerExpedienteAutorizacion(array('solicitudObtencionExpedienteAutorizacion' => $SolicitudObtencionExpedienteAutorizacion));

        $res = $ready->ObtenerExpedienteAutorizacionResult->DTOSerializado;

        $obj = json_decode($res, true);  // parseamos como un array

        if ($obj['NumeroExpedienteAutorizacion'] != '') {

            // Ahora controlamos que coincida elafiliado con el nro. de expediente ingresado
            $afiliado->getObject($auditoria->getCodos(), $auditoria->getNrodoc());

            //echo $auditoria->getCodos() . '   ' . $auditoria->getNrodoc() . '  ' . $afiliado->getCodos() . '---' . trim($obj['IdSocio']) . '  == '  . trim($afiliado->getIdOS()) . '<hr/>';
            //echo "<br/><input type='checkbox' id='rws' value='Mostrar Resultado WS' onClick='MostrarResultado(rws.checked,`' . $res . '`)/>Mostrar Resultados WS<br/>";
            //echo "<br/><input type='checkbox' id='rws' value='Mostrar Resultado WS' onClick='if (rws.checked) { document.getElementById('rws').value = 'ssss'; }'/>Mostrar Resultados WS<br/>";

            echo '<div id="resultadows">';
            echo '<fieldset width:"550px"><legend>Resultado WS</legend>';
            echo '<h1>*** EXPEDIENTE ' . $expediente . ' CORRECTO ***</h1>';
            echo $res . '</fieldset>';
            echo '</div>';

            if (trim($obj['IdSocio']) == trim(trim($obj['IdSocio']))) {
                $expedienteOK = true;
                echo '<h4>Presione en Registrar Cambios para Finalizar la Transacción</h4><hr/>';
                $wsres->crear1($auditoria->getCodos(), $auditoria->getEfector(), $afiliado->getIdOS(), $res, 3, $obj['NumeroExpedienteAutorizacion']);
            } else {
                echo 'ERROR:: El Expediente ' . $expediente . ' pertenece a otro Afiliado ' . $afiliado->getNombre() . '(' . $afiliado->getIdOS() . ')' . '<br/>' . $res;
            }
        } else {
            // 25/07/2018
            if ($expediente == 'MANUAL') {
                echo '<h2>Seleccione Manualmente los Items a Autorizar</h2>';
            } else {
                echo '<h1>*** NUMERO DE EXPEDIENTE INCORRECTO ***</h1>';
            }
            // Fin 25/07/2018
            //echo '<h1>*** NUMERO DE EXPEDIENTE INCORRECTO ***</h1>';
        }
    } catch (Exception $e) {
        trigger_error($e->getMessage(), E_USER_WARNING);
    }


    $res = $auditoria->getDeterminaciones("'" . $_REQUEST['nrotrans'] . "'");

    $codigos = array();
    $estados = array();
    $codaltas = array();
    $__estados = array();
    $cantidadautorizada = array();

    $i = 0;
    if ($expedienteOK) {  // Marcamos las prácticas autorizadas
        // Actualizamos el nro de transacción
        $auditoria->actualizarExpediente($_REQUEST['nrotrans'], $_REQUEST['expediente']);

        foreach ($obj['DetalleExpedienteAutorizacion'] as $key => $value) {
            $cantt = 0;
            if (is_array($value)) {
                foreach ($value as $key1 => $rs) {
                    foreach ($rs as $key2 => $rss) {
                        if ($key2 == 'CodigoNomenclador')
                            $cod = $rss;
                        if ($key2 == 'Autorizado')
                            $aut = $rss;
                        if ($key2 == 'Cantidad')
                            $cantt = $rss;
                    }

                    $codigo = str_replace('.', '', $cod);

                    //echo $codigo . '  ' . $cod . ' - ' . $aut . '<br/>';

                    $estado = 'R';
                    if ($aut == 1)
                        $estado = 'A';

                    $auditoria->actualizarDeterminacion($_REQUEST['nrotrans'], $codigo, $estado);

                    $codigos[$i] = $codigo;
                    $cantidadautorizada[$i] = $cantt;

                    if ($aut == 1)
                        $estados[$i] = $aut;
                    else
                        $estados[$i] = 0;

                    $__estados[$i] = 22;

                    $i++;
                }
            }
        }
    }

// Si existe alguna determinación dada de alta por la obra social la agregamos
    $m = 0;
    for ($j = 0; $j <= $i; $j++) {
        $found = false;
        mysql_data_seek($res, 0);
        //$res = $auditoria->getDeterminaciones("'" . $_REQUEST['nrotrans'] . "'");
        while ($MostrarFila = mysql_fetch_array($res)) {
            if ($MostrarFila['codigo'] == trim($codigos[$j])) {
                $found = true;
                break;
            }
        }

        if ($found == false) {
            $nbu->getObject(trim($codigos[$j]));
            if ($nbu->getCodigo() != '') {
                $codaltas[$m] = trim($codigos[$j]);
                $m++;
            }
        }
    }

    $n = 0;
    for ($j = 0; $j <= $m; $j++) {
        // agrego los codigos que faltan
        $n = $n + 1;
        if ($codaltas[$j] != '') {
            $auditoria->actualizarItemsExternos($_REQUEST['nrotrans'], $n, $codaltas[$j], $auditoria->efector, $auditoria->codos, $auditoria->fecha, $auditoria->nrodoc, $auditoria->estado);

            /*
            echo $cantidadautorizada[$i] . ' - ' . $codaltas[$m];

            if ($cantidadautorizada[$i] == 2) {
                $n = $n + 1;
                $auditoria->actualizarItemsExternos($_REQUEST['nrotrans'], $n, $codaltas[$j], $auditoria->efector, $auditoria->codos, $auditoria->fecha, $auditoria->nrodoc, $auditoria->estado);
            }
             * 
             */
        }
    }
    
    // Determinaciones x 2 - 16/03/2020
    for ($j = 0; $j <= $i; $j++) {
      if ($cantidadautorizada[$j] > 1) {
        //echo trim($codigos[$j]) . '  ' . $cantidadautorizada[$j] . ' cant: ' . $auditoria->countPracticas($nroauditoria, trim($codigos[$j])) . '<br/>';    
        $__c = $auditoria->countPracticas($nroauditoria, trim($codigos[$j]));
        if ($__c == 1) {
            // Agregamos
            $cp = $auditoria->countPracticasOrden($nroauditoria);
            $cp = $cp + 1;
            $auditoria->actualizarItemsExternos($_REQUEST['nrotrans'], $cp, trim($codigos[$j]), $auditoria->efector, $auditoria->codos, $auditoria->fecha, $auditoria->nrodoc, $auditoria->estado);
        }
      }
    }

    $res = $auditoria->getDeterminaciones("'" . $_REQUEST['nrotrans'] . "'");

    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo "<tbody align = 'left'>";
    echo "<td width='80px'><b>Código</b>";
    echo "<td width='400px'><b>Determinación</b>";
    echo "</tr>";
    while ($MostrarFila = mysql_fetch_array($res)) {
        if ($obsocial->_parametro2 == 'REQUIEREEXPEDIENTE')
            $estado = ' disabled = "true" ';

        if ($expediente == 'MANUAL')
            $estado = ''; // 25/07/2018

        $nbu->getObject($MostrarFila['codigo']);
        $e = "";
        // Modificado 29/04/2014
        //if ($MostrarFila['estado'] == 'A')  // Modificado 27/03/2014, solo se chequean los items que trae el expediente
        //$e = "checked='checked'";
        //----------------------
        // Chequeamos las que están autorizadas        
        // Habilitamos 09/08/2018
        if ($MostrarFila['estado'] == 'A')
            $e = "checked='checked'";
        //-----------

        for ($j = 0; $j <= $i; $j++) {

            if ($MostrarFila['codigo'] == trim($codigos[$j])) {
                $rr = " [ verificado ]";
                if ($estados[$j] == 1)
                    $e = "checked='checked'";
                else
                    $e = "";
            }

            if ($MostrarFila['items'] >= '300') {
                $rr = ' [alta obra social]';
                $e = "checked='checked'";
            }
        }

        // 15/03/2017
        if ($MostrarFila['codigo'] == $entidad->parametro4)
            $e = "checked='checked'";

        echo "<tr>";
        echo "<td width='80px'><input type='checkbox'" . $e . $estado . " name='list' id='list' value=" . $MostrarFila['codigo'] . "  onClick='chequear()' " . '>' . $MostrarFila['codigo'] . "</td>";
        echo "<td width='400px'>" . $nbu->getDescrip() . $rr . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    $tipo_envio = 1;

    echo '<hr>';

    $estado = ' disabled = "true" ';
    echo "<table border='0px' width='550px'>";
    echo "<tr>";
    echo '<td width="150" align = "left"><input type="button" class="button gray small" id="btnRegistrar" name="btnRegistrar" value="Registrar Cambios"' . $estado . ' onclick="AutorizarDeterminaciones(' . "'" . $nroauditoria . "'" . "," . $tipo_envio . '); return false" /></td>';
    echo '<td width="150" align = "left"><input type="button" class="button gray small" name="btnImprimir" value="Imprimir Orden" onclick="ImprimirLista(); return false" /></td>';
    echo '<td align = "left"><input type="button" class="button gray small" name="btnCancelar" value="Cerrar" onclick="CancelarDeterminaciones(); return false" /></td>';
    echo "</tr>";
    echo "</table>";

    echo '<hr>';

    return;
}
?>

