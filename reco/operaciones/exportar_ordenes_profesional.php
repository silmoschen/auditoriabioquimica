<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$idprof = $_REQUEST['idprof'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
$auditoria   = new cAuditoria;

if (strlen($desde) > 0 && strlen($hasta) > 0 && strlen($idprof) > 0) {
  $auditoria->ExportarFacturacionProfesional($idprof, $desde, $hasta);  
}

?>
