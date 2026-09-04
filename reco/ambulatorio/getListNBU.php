<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$nbu = new cNBU();
$resultado = $nbu->getLista($_REQUEST['descrip'], 0, 100000);
while ($fila = mysql_fetch_array($resultado)) {
    printf($fila['codigo'] . '::' . $fila['descrip'] . " \r\n");
}
?>
