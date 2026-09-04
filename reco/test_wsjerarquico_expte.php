<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
try {
  //$result = $client->__soapCall('GetJobs', array(), array('soapaction' => 'http://tempuri.org/GetJobs')); 

    $client = new SoapClient("http://servicios.jerarquicossalud.com.ar:9011/AgenteServicios.svc?wsdl",
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
    $CriterioExpedienteAutorizacionSocio = array('FechaDeReferencia' => '2014-04-30T00:00:00',
        'IdSocio' => null,
        'NumeroExpedienteAutorizacion' => 4554491,
        'NumeroSocio' => 549503,
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
?>
