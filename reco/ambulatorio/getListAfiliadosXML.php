<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once('mysqlAXML-1.0.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');

$paciente = new cAfiliados();
$resultado = $paciente->getConsultaAfiliados($_REQUEST['codos'], $_REQUEST['nombre'], '1', 0, 10000);

header ('Content-type: text/xml');
echo mysql_XML($resultado);

?>
