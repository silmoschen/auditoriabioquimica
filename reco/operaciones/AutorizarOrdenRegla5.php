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
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

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
$efector = new cEfector();

$auditoria->getObject($nroauditoria);
$efector->getObject($auditoria->getEfector());

$codos = '';

$obsocial->verificarRPC($auditoria->getCodos());

$estado = ' ';

$expedienteOK = false;

//==============================================================================
// SANCOR v2
if ($obsocial->_reglaNegocio == 5) {

    $url = $obsocial->_url . 'formulario4';
    $prestador = str_replace('-', '', $obsocial->_parametro7);
    $ef = str_replace('-', '', $efector->getNrocuit());

    $json_data = '{"IdentificadorAfiliado":"' . $auditoria->getNrodoc() . '", "IDPrestador":"' . $prestador . '", "parametro1":"' . $ef . '", "transaccion":"' . $expediente . '"}';

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

    $post = file_get_contents($url, false, $context); //, -1, $l);

    $result = json_decode($post);

    echo '<br/>';

    $s = print_r($result, true);
    $wsres->crear($codos, $idprof, $nrodoc, $s, 3);

    echo $s;

    $it = 0;
    $__it = 299;
    for ($p = 0; $p < count($result->practicas); $p++) {
        if ($p == 0) {
            echo '<hr/>';
            echo "<table border='0px' width='550px'>";
            echo "<tr>";
            echo "<tr><td>***** PRÁCTICAS AUTORIZADAS VÍA FORMULARIO 4 *****</td></tr>";
            echo "<tr><td>====================================================================</td></tr>";
        }
        $nbu->getObject($result->practicas[$p]->codigo);
        echo '<tr><td>[' . $result->practicas[$p]->estado . '] ' . $result->practicas[$p]->codigo . ' - ' . $nbu->getDescrip() . '</td></tr>';

        // Altas        
        $rs = $auditoria->findPracticaAuditoria($_REQUEST['nrotrans'], $result->practicas[$p]->codigo);
        if ($rs == false) {
            $__it++;
            $auditoria->addItemsExterno($_REQUEST['nrotrans'], $__it, $result->practicas[$p]->codigo, $auditoria->efector, $auditoria->codos, $auditoria->fecha, $auditoria->nrodoc, $result->practicas[$p]->estado);
        } else { // Actualización
            $auditoria->actualizarDeterminacion($nroauditoria, $result->practicas[$p]->codigo, $result->practicas[$p]->estado);
        }
    }

    if ($p > 0) {
        echo "</tr>";
        echo "</table>";
        
        $auditoria->actualizarExpediente($_REQUEST['nrotrans'], $expediente);
    }

    $tipo_envio = 1;

    echo '<hr/>';

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

