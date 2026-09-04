<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$codos = $_REQUEST['codos'];

$auditoria = new cAuditoria;

echo "<h2>" . $auditoria->getCantidadBonos($codos, $desde, $hasta) . " Bonos" . "</h2>";

?>
