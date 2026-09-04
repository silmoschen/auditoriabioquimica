<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");

$nbu = new cNBU();

//$result = $client->__soapCall('GetJobs', array(), array('soapaction' => 'http://tempuri.org/GetJobs')); 
//$client = new SoapClient("http://servicios.jerarquicossalud.com.ar:10500/AgenteServicios.svc?wsdl",
//              array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));
//$wsdl = "http://servicios.jerarquicossalud.com.ar:10500/AgenteServicios.svc?WSDL";
$wsdl = "http://servicios.jerarquicossalud.com.ar:9011/AgenteServicios.svc?wsdl";
$wsdl = "http://servicios.jerarquicos.com:9011/AgenteServicios.svc?wsdl";

$client = new SoapClient($wsdl, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));



try {

    $plan = 5;

    $CriterioPracticaRequiereAutorizacion = array(
        'CodigoNomencladorConvenio' => '669759',
        'IdConvenio' => 207,
        'IdPlan' => $plan);

    $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);

//Llamada al servicio pasando el parámetro
    $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));
    
    print_r($ready);

    echo "Práctica: " . $obj->{'CodigoNomenclador'}  . "<br/>";

    $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

    $obj = json_decode($res);
    if (!$obj->{'RequiereAutorizacion'})
        $autoriza = 'S';
    else
        $autoriza = 'N';
    print $obj->{'CodigoNomenclador'} . '  ' . $obj->{'Descripcion'} . '  ' . $autoriza;

    echo '<hr/>';

    $CriterioPracticaRequiereAutorizacion = array(
        'CodigoNomencladorConvenio' => '660412',
        'IdConvenio' => 207,
        'IdPlan' => $plan);

    $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);
   
    $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));

    echo "<h4>Resultado WS Validar Practica</h4>";
    print_r($ready);

    echo "<hr/>";

    $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

    $obj = json_decode($res);
    if (!$obj->{'RequiereAutorizacion'})
        $autoriza = 'S';
    else
        $autoriza = 'N';
    print $obj->{'CodigoNomenclador'} . '  ' . $obj->{'Descripcion'} . '  ' . $autoriza;
    
    
    $CriterioPracticaRequiereAutorizacion = array(
        'CodigoNomencladorConvenio' => '660475',
        'IdConvenio' => 207,
        'IdPlan' => $plan);

    $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);
   
    $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));

    echo "<h4>Resultado WS Validar Practica</h4>";
    print_r($ready);

    echo "<hr/>";

    $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

    $obj = json_decode($res);
    if (!$obj->{'RequiereAutorizacion'})
        $autoriza = 'S';
    else
        $autoriza = 'N';
    print $obj->{'CodigoNomenclador'} . '  ' . $obj->{'Descripcion'} . '  ' . $autoriza . ' Mensaje: ' . $ready->ValidarPracticaRequiereAutorizacionResult->mensaje;

    $CriterioPracticaRequiereAutorizacion = array(
        'CodigoNomencladorConvenio' => '660137',
        'IdConvenio' => 207,
        'IdPlan' => $plan);

    $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);
   
    $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));

    echo "<h4>Resultado WS Validar Practica</h4>";
    print_r($ready);

    echo "<hr/>";

    $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

    $obj = json_decode($res);
    if (!$obj->{'RequiereAutorizacion'})
        $autoriza = 'S';
    else
        $autoriza = 'N';
    print $obj->{'CodigoNomenclador'} . '  ' . $obj->{'Descripcion'} . '  ' . $autoriza . ' Mensaje: ' . $ready->ValidarPracticaRequiereAutorizacionResult->mensaje;

    echo '<hr>' . "Test Practica Finalizado </hr>";
} catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_WARNING);
}

echo '<h1>Equivalencia</h1>';

if ($nbu->getPracticaAdicional('121057', '661475')) echo 'SI'; else echo 'NO';

echo '<h1>FIN</h1>';



?>