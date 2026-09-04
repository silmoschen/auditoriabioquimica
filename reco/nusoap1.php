<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("nusoap/nusoap.php");
 
//Create a new soap server
$server = new soap_server();
 
//Define our namespace
$namespace = "https://200.69.227.201:8004/cawsTest/Servicios";
$server->wsdl->schemaTargetNamespace = $namespace;

echo $server->methodname();

?>
