<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");

$itemsauditoria = new cItemsAuditoria;

$codos  = $_REQUEST['codos'];
$desde  = $_REQUEST['desde'];
$hasta  = $_REQUEST['hasta'];

$itemsauditoria->RecaulcularMontos($codos, $desde, $hasta);

?>
