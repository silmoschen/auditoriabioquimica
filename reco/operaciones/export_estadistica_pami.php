<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$codos = $_REQUEST['codos'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");

$auditoria   = new cAuditoria;
$utiles = new cUtiles;

$cant = 0;
if (strlen($desde) > 8 && strlen($hasta) > 8) {
  $cant = $auditoria->estadisticasSM1($utiles->getFechaAAAAMMDD($desde), $utiles->getFechaAAAAMMDD($hasta), $codos);  
}

if ($cant > 0){
    echo '<a href="/operaciones/exportar_excel1.php">Exportar Ordenes</a>';
}

?>
