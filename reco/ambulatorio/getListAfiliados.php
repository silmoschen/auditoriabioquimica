<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');

$paciente = new cAfiliados();
if ($_REQUEST['opt'] == 'nrodoc')
    $resultado = $paciente->getConsultaAfiliados($_REQUEST['codos'], $_REQUEST['nombre'], '4', 0, 10000); else
    $resultado = $paciente->getConsultaAfiliados($_REQUEST['codos'], $_REQUEST['nombre'], '1', 0, 10000);
while ($fila = mysql_fetch_array($resultado)) {
    printf($fila['nrodoc'] . '::' . $fila['nombre'] . " \r\n");
}
?>
