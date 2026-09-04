<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');

$modelo = new cModelos();
$resultado = $modelo->getModelosDef($_REQUEST['codos'], 0, 1000000);
while ($fila = mysql_fetch_array($resultado)) {
    printf($fila['oms_cod'] . '::' . $fila['descrip'] . " \r\n");
}
?>
