<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('lib/nusoap/nusoap.php');

$url = "https://200.69.227.201:8004/cawsTest/Servicios?WSDL";
$options["connection_timeout"] = 25;
$options["location"] = $url;
$options['trace'] = 1;

$cliente = new nusoap_client($url, 'wsdl');
echo $cliente->getError();
//echo $cliente->getDebug();

$parametros = array("pNombre" => "argentina");

$result = $cliente->call('testWS', $parametros);
echo $cliente->getError();
echo $result;
?>
