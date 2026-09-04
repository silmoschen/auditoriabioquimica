<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$url = 'http://www.amur.com.ar/administracion/webservices/WSValidadorAMUR.php?wsdl';

$client = new SoapClient($url,
                    array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => TRUE));
//Autorizacion
$usuario = 'P00031';
$pass = 'w00031centro';
$naut = '000027';
$faut = '2015-04-21';
$tipo = 'T';
//T: TOTAL, A: ATENDIDA

$result = $client->__soapCall('fnconsulta', array('usuario' => $usuario, 'pass' => $pass, 'naut' => $naut, 'faut' => $faut, 'tipo' => $tipo));

print_r($result);

echo 'Nº DE AUTORIZACIÓN: '.$result->naut.'<BR>';
echo 'FECHA DE AUTORIZACIÓN: '.$result->faut.'<BR>';
echo 'DETALLE: '.$result->estado . ': '.$result->mensajes .'<BR>';


?>
