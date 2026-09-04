<?php

//$url = "https://cawsdes.acasalud.com.ar:8004/cawsTest/Servicios?WSDL";
$url = "https://caws.acasalud.com.ar:4444/cawsProd/Servicios?WSDL";
//$url = "https://caws.acasalud.com.ar:4444/cawsProd/Servicios?operation=transaccionStr";

$options["connection_timeout"] = 25;
$options["location"] = $url;
$options['trace'] = 1;

$client = new SoapClient($url, $options);

//IMPRIME LAS FUNCIONES
echo "<h4>Funciones WS</h4>";
print_r($client->__getFunctions());
echo "<hr/>";

//TEST MENSAJE
echo "<h4>TEST</h4>";
echo $client->testWs('Silvio');
echo "<hr/>";

?>
