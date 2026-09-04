<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time",1000);

$codos   = $_REQUEST['codos'];
$fecha   = $_REQUEST['desde'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoriaDep.php");

$auditoria   = new cAuditoria;
$dep = new cAuditoriaDep;

echo '<br>Marcando Ordenes para Depurar =>';

$auditoria->MarcarOrdenesParaDepurar($codos, $fecha);

echo '<i> Las Ordenes han sido Marcadas Correctamente</i>';

echo '<br>Transfiriendo Ordenes =>';

$auditoria->DepurarOrdenesMarcadas($codos, $fecha);

echo '<i> Las Ordenes han sido Transferidas Correctamente al Registro Historico</i>';

echo '<br>Eliminando Ordenes =>';

$auditoria->EliminarOrdenesMarcadasParaDepurar($codos, $fecha);
$dep->crear($fecha, $fecha, $codos);

echo '<i> Las Ordenes han sido Eliminadas y Transferidas Correctamente al Registro Historico</i>';

?>
