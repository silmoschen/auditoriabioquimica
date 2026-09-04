<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

$auditoria = new cAuditoria;

$orden1  = $_REQUEST['orden1'];
$orden2  = $_REQUEST['orden2'];
$orden3  = $_REQUEST['orden3'];
$orden4  = $_REQUEST['orden4'];
$orden5  = $_REQUEST['orden5'];
$orden6  = $_REQUEST['orden6'];
$orden7  = $_REQUEST['orden7'];
$orden8  = $_REQUEST['orden8'];
$orden9  = $_REQUEST['orden9'];
$orden10  = $_REQUEST['orden10'];
$orden11  = $_REQUEST['orden11'];
$orden12  = $_REQUEST['orden12'];
$orden13  = $_REQUEST['orden13'];
$orden14  = $_REQUEST['orden14'];
$orden15  = $_REQUEST['orden15'];



if ($orden1 != '') {
  $auditoria->ListarOrden($orden1);
}

if ($orden2 != '') {
  $auditoria->ListarOrden($orden2);
}

if ($orden3 != '') {
  $auditoria->ListarOrden($orden3);
}

if ($orden4 != '') {
  $auditoria->ListarOrden($orden4);
}

if ($orden5 != '') {
  $auditoria->ListarOrden($orden5);
}

if ($orden6 != '') {
  $auditoria->ListarOrden($orden6);
}

if ($orden7 != '') {
  $auditoria->ListarOrden($orden7);
}

if ($orden8 != '') {
  $auditoria->ListarOrden($orden8);
}

if ($orden9 != '') {
  $auditoria->ListarOrden($orden9);
}

if ($orden10 != '') {
  $auditoria->ListarOrden($orden10);
}

if ($orden11 != '') {
  $auditoria->ListarOrden($orden11);
}

if ($orden12 != '') {
  $auditoria->ListarOrden($orden12);
}

if ($orden13 != '') {
  $auditoria->ListarOrden($orden13);
}

if ($orden14 != '') {
  $auditoria->ListarOrden($orden14);
}

if ($orden15 != '') {
  $auditoria->ListarOrden($orden15);
}



?>
