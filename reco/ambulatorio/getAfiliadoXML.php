<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once('mysqlAXML-1.0.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');

$paciente = new cAfiliados();
$resultado = $paciente->getConsultaAfiliados($_REQUEST['codos'], $_REQUEST['nrodoc'], '5', 0, 0);

while ($fila = mysql_fetch_array($resultado)) {
    echo($fila['nrodoc'] . " \r\n");
    echo($fila['nombre'] . " \r\n");
    echo($fila['direccion'] . " \r\n");
    echo($fila['id_beneficio'] . " \r\n");
    echo($fila['id_parentesco'] . " \r\n");
    echo($fila['sexo'] . " \r\n");
    echo($fila['tipo_doc'] . " \r\n");
    echo($fila['retiva'] . " \r\n");
}
?>
