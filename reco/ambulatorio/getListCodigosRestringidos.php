<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosRestringidos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');

$codigosrest = new cCodigosRestringidos();
$resultado = $codigosrest->getCodigos($_REQUEST['codos']);
while ($fila = mysql_fetch_array($resultado)) {
    printf($fila['codigo'] . " \r\n");
}

$entidad = new cEntidad();
$entidad->getObject();

printf($entidad->getParametro4() . " \r\n");

?>
