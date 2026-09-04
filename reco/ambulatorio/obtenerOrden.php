<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');

$ID = $_REQUEST['nroauditoria'];

$auditoria = new cAuditoria();
$auditoria->ListarOrden($ID);

printf(" \r\n" . "A::" . $auditoria->codigos_autorizados . "B::" . $auditoria->bonos_auditoria . "C::" . $auditoria->coseguro_auditoria  );

?>
