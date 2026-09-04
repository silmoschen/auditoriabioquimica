<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


// Incluir la Clase
require_once('Calculadora.php');

// Crear servidor de Soap
$server = new SoapServer(
                null, // No utilizar WSDL
                array('uri' => 'urn:webservices') // Se debe especificar el URI
);

// Asignar la Clase
$server->setClass('Calculadora');

// Atender los llamados al webservice
$server->handle();
?>
