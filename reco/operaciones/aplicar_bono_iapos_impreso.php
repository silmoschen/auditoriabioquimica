<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTransaccionesCoseguroIapos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$nroauditoria = $_REQUEST['nroauditoria'];
$anula = $_REQUEST['anula'];

$c = new cTransaccionesCoseguroIapos();
$u = new cUtiles();

if ($anula == 'S') {
    $c->getUltimaTx($nroauditoria);
    $c->crear($u->getUUID(), $nroauditoria, 'BONOIMP', 'COSEGURO-ANULA', $u->getFechaHoraActualYYYY_MM_DD(), '0', 'Anulación Bono Impreso', null, null, null, null);
    return;
}

if (strlen($nroauditoria) > 0) {
    $c->crear($u->getUUID(), $nroauditoria, 'BONOIMP', 'COSEGURO', $u->getFechaHoraActualYYYY_MM_DD(), '0', 'Bono Impreso', null, null, null, null);
}
?>

