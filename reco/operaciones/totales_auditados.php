<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");

//variables POST
$fecha  = $_REQUEST['fecha'];
$codos  = $_REQUEST['codos'];

$obj=new cItemsAuditoria;
$obj->TotalesAuditados($codos, $fecha);
?>