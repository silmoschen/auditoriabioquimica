<?php

try {

    //$result = $client->__soapCall('GetJobs', array(), array('soapaction' => 'http://tempuri.org/GetJobs')); 

    //$client = new SoapClient("http://servicios.jerarquicossalud.com.ar:10500/AgenteServicios.svc?wsdl",
      //              array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    $wsdl = "http://servicios.jerarquicossalud.com.ar:10500/AgenteServicios.svc?WSDL";
    $wsdl = "http://servicios.jerarquicossalud.com.ar:9011/AgenteServicios.svc?wsdl";
    $wsdl = "http://servicios.jerarquicos.com:10500/AgenteServicios.svc?wsdl";
    $wsdl = "http://servicios.jerarquicos.com:9011/AgenteServicios.svc?wsdl";
    $wsdl = "http://servicios.jerarquicos.com:10500/AgenteServicios.svc?wsdl";
    
    $client = new SoapClient($wsdl,
                    array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    //IMPRIME LAS FUNCIONES
    echo "<h4>Funciones WS Validar Afiliado</h4>";
    print_r($client->__getFunctions());
    //echo "<hr/>";
    //var_dump($client->__getFunctions());
    echo "<hr/>";

    $us = 6270700;
    $so = substr($us, 0, strlen($us) - 2);
    $or = substr($us, strlen($us) - 2, 2);


    //echo "antes";
    $CriterioElegibilidadSocioServiciosSalud = array('FechaDeReferencia' => '2013-12-24T00:00:00',
        'NumeroSocio' => $so,
        'OrdenSocio' => $or,
        'IdTipoDocumento' => null,
        'NumeroDocumento' => null);

    $SolicitudElegibilidadSocioServiciosSalud = array('CriterioElegibilidadSocioServiciosSalud' => $CriterioElegibilidadSocioServiciosSalud);

    //Llamada al servicio pasando el parámetro
    $ready = $client->DeterminarElegibilidadSocioServiciosSalud(array('solicitudElegibilidadSocioServiciosSalud' => $SolicitudElegibilidadSocioServiciosSalud));

    echo "<h4>Resultado WS</h4>";
    print_r($ready);

    echo "<hr/>";

    
    $res = $ready->DeterminarElegibilidadSocioServiciosSaludResult->DTOSerializado;

    $obj = json_decode($res);
    print $obj->{'Numero'} . '  ' . $obj->{'Apellido'} . '  ' . $obj->{'Nombre'} . '  ' . $obj->{'PlanVigente'}->{'PlanId'};

    $plan = $obj->{'PlanVigente'}->{'PlanId'};
} catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_WARNING);
}

echo '<hr>' . "Test Afiliado Finalizado </hr>";
/*hr
try {

    $client = new SoapClient("http://servicios.jerarquicossalud.com.ar:10500/AgenteServicios.svc?wsdl",
                    array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    $CriterioPracticaRequiereAutorizacion = array(
        'CodigoNomencladorConvenio' => '66.97.59',
        'IdConvenio' => null,
        'IdPlan' => $plan);

    $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);

    //Llamada al servicio pasando el parámetro
    $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));

    echo "<h4>Resultado WS Validar Practica</h4>";
    print_r($ready);

    echo "<hr/>";

    $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

    $obj = json_decode($res);
    if (!$obj->{'RequiereAutorizacion'})
        $autoriza = 'S'; else
        $autoriza = 'N';
    print $obj->{'CodigoNomenclador'} . '  ' . $obj->{'Descripcion'} . '  ' . $autoriza;

    echo '<hr/>';

    $CriterioPracticaRequiereAutorizacion = array(
        'CodigoNomencladorConvenio' => '660475',
        'IdConvenio' => 207,
        'IdPlan' => $plan);

    $SolicitudValidacionPracticaRequiereAutorizacion = array('CriterioPracticaRequiereAutorizacion' => $CriterioPracticaRequiereAutorizacion);

    //Llamada al servicio pasando el parámetro
    $ready = $client->ValidarPracticaRequiereAutorizacion(array('solicitudValidacionPracticaRequiereAutorizacion' => $SolicitudValidacionPracticaRequiereAutorizacion));

    echo "<h4>Resultado WS Validar Practica</h4>";
    print_r($ready);

    echo "<hr/>";

    $res = $ready->ValidarPracticaRequiereAutorizacionResult->DTOSerializado;

    $obj = json_decode($res);
    if (!$obj->{'RequiereAutorizacion'})
        $autoriza = 'S'; else
        $autoriza = 'N';
    print $obj->{'CodigoNomenclador'} . '  ' . $obj->{'Descripcion'} . '  ' . $autoriza;


    echo '<hr>' . "Test Practica Finalizado </hr>";
} catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_WARNING);
}


try {

    //$result = $client->__soapCall('GetJobs', array(), array('soapaction' => 'http://tempuri.org/GetJobs')); 

    $client = new SoapClient("http://servicios.jerarquicossalud.com.ar:10500/AgenteServicios.svc?wsdl",
                    array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));

    //$wsdl = "http://servicios.jerarquicossalud.com.ar:10500/AgenteServicios.svc?WSDL";
    //$client = new SoapClient($wsdl, array('trace' => 1, 'exceptions' => 1));
    //IMPRIME LAS FUNCIONES
    echo "<h4>Funciones WS Validar Afiliado</h4>";
    print_r($client->__getFunctions());
    //echo "<hr/>";
    //var_dump($client->__getFunctions());
    echo "<hr/>";

    $us = 3847300;
    $so = substr($us, 0, strlen($us) - 2);
    $or = substr($us, strlen($us) - 2, 2);

    $ex = 420296;


    //echo "antes";
    $CriterioExpedienteAutorizacionSocio = array('FechaDeReferencia' => '2013-12-24T00:00:00',
        'IdSocio' => null,
        'NumeroExpedienteAutorizacion' => 4125037,
        'NumeroSocio' => 54967,
        'OrdenSocio' => 0);

    $SolicitudObtencionExpedienteAutorizacion = array('CriterioExpedienteAutorizacionSocio' => $CriterioExpedienteAutorizacionSocio);

    //Llamada al servicio pasando el parámetro
    $ready = $client->ObtenerExpedienteAutorizacion(array('solicitudObtencionExpedienteAutorizacion' => $SolicitudObtencionExpedienteAutorizacion));

    echo "<h4>Resultado WS Expediente</h4>";
    echo "<pre>";
    print_r($ready);
    echo "</pre>";

    echo "<hr/>";

    $res = $ready->ObtenerExpedienteAutorizacionResult->DTOSerializado;

    echo "<hr/>";

    echo $res;

    $obj = json_decode($res, true);

    echo 'Expte: ' . $obj['NumeroExpedienteAutorizacion'];
    echo "<hr/>";

    foreach ($obj['DetalleExpedienteAutorizacion'] as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $key1 => $rs) {
                foreach ($rs as $key2 => $rss) {
                    if ($key2 == 'CodigoNomenclador')
                        $cod = $rss;
                    if ($key2 == 'Autorizado')
                        $aut = $rss;
                }

                echo $cod . ' - ' . $aut . '<br/>';
            }
        }
    }
} catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_WARNING);
}
     * 
     */
?>