<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
$auditoria   = new cAuditoria;

if (strlen($desde) == 8 && strlen($hasta) == 8) {
  $auditoria->Exportar($desde, $hasta);  
}

?>
