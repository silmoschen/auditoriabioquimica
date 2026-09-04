<?php

include_once('__routes.php');

include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cModelos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cDiagnosticosOMS.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosRestringidos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNbuFederada.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaCodigosNBU.php");

$obsocial = new cObSocial();          // Obras Sociales

$codos = '121057';

$obsocial->getObject($codos);

$obsocial->verificarRPC($codos);

$url = $obsocial->_url;

$cc = '660475';
$convenio = $obsocial->getParametro3();

try {
    
    $plan = 9;

    $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));
    
    print_r($client->__getFunctions());

    $CriterioPracticaRequiereAutorizacion = array(
        'CodigoNomencladorConvenio' => $cc,
        'IdConvenio' => $convenio,
        'IdPlan' => $plan);

    $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);

    //Llamada al servicio pasando el parámetro
    $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));

    $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

    $obj = json_decode($res);
    
     echo "<h4>Resultado WS Validar Practica</h4>";
    print_r($ready);
    
    echo '<br/>';

    echo $res;
} catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_WARNING);
}
?>

