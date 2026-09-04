<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

$auditoria = new cAuditoria;

$nrotrans  = $_REQUEST['nrotrans'];

$auditoria->ListarObsAuditor($nrotrans);

?>
