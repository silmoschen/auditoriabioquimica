<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');

$ID = $_REQUEST['nroauditoria'];

$auditoria = new cAuditoria();
$auditoria->getObject($ID);

$autorizada = 'N';
if ($auditoria->getAuditada() == 'S') {
    $autorizada = 'S';
}
if ($auditoria->getDiferida() == 'N'){
    $autorizada = 'S';
}

printf($autorizada);

?>
