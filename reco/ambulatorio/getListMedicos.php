<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');

$medico = new cMedicos();
$resultado = $medico->getMedicos($_REQUEST['codos'], $_REQUEST['nombre']);
while ($fila = mysql_fetch_array($resultado)) {
    printf($fila['idprof'] . '::' . $fila['nombre'] . " \r\n");
}
?>
