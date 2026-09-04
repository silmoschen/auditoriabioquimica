<?php

session_start();

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//echo $_SESSION["nivel"] . ' xxxx ' . $_SESSION['susuario'];
// Si No es Usuario de Auditoria, la rechaza
// 01/02/2022
if ($_SESSION["nivel"] == '1') {
    //exit;
}

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");

$auditoria = new cAuditoria;
$utiles = new cUtiles;
$items = new cItemsAuditoria;

$nroauditoria = $_REQUEST['nrotrans'];
$autorizadas = $_REQUEST['autorizadas'];
$rechazadas = $_REQUEST['rechazadas'];
$capitadas = $_REQUEST['capitadas'];
$observaciones = $_REQUEST['observaciones'];
$usuario = $_REQUEST['usuario'];
$expediente = $_REQUEST['expediente'];

$auditoria->getObject($nroauditoria);

// separamos los códigos autorizados
$c = '';
$j = 0;
$item = 0;
for ($i = 0; $i < 500; $i++) {
    $c = substr($autorizadas, $j, 6);
    $j = $j + 6;
    if ($c == 0) {
        break;
    }

    $item = $item + 1;
    $it = $utiles->LlenarIzquierda($item, 3, '0');
    $items->ActualizarItems($nroauditoria, $it, $c, $auditoria->getEfector(), $auditoria->getCodos(), $auditoria->getFecha(), $auditoria->getNrodoc(), 'A');
}

// separamos los códigos rechazados
$c = '';
$j = 0;
for ($i = 0; $i < 500; $i++) {
    $c = substr($rechazadas, $j, 6);
    $j = $j + 6;
    if ($c == 0) {
        break;
    }

    $item = $item + 1;
    $it = $utiles->LlenarIzquierda($item, 3, '0');
    $items->ActualizarItems($nroauditoria, $it, $c, $auditoria->getEfector(), $auditoria->getCodos(), $auditoria->getFecha(), $auditoria->getNrodoc(), 'R');
}

// separamos los códigos capitados
$c = '';
$j = 0;
$itt = 0;
for ($i = 0; $i < 500; $i++) {
    $c = substr($capitadas, $j, 6);
    $j = $j + 6;
    if ($c == 0) {
        break;
    }

    $itt = $itt + 1;
    $it = $utiles->LlenarIzquierda($itt, 3, '0');
    $items->GuardarItemsCapitas($nroauditoria, $it, $c, 'A', $auditoria->getFecha(), $auditoria->getCodos());
}

// 05/07/2018 $items->actualizarUnidadesNbu($nroauditoria); // 04/07/2018

if ($itt == 0) {
    $items->AjustarItemsCapitas($nroauditoria);
}

if ($observaciones == 'nullzztop95') {
    
} else {
    $auditoria->ActualizarObservacionAuditor($nroauditoria, $observaciones);
}

if ($expediente != '')
    $auditoria->actualizarExpediente($nroauditoria, $expediente);

$auditoria->MarcarComoAuditada($nroauditoria, $usuario);

// Si es FESALUD ...
$auditoria->obrasocial->verificarRPC($auditoria->getCodos());
if ($auditoria->obrasocial->_reglaNegocio == 10) {
    $auditoria->aplicarCoseguroReglas($nroauditoria, $auditoria->getCodos(), $auditoria->getNrodoc());
    exit;
}

$auditoria->RecalcularCoseguro($nroauditoria, $auditoria->getCodos(), $auditoria->getFecha());

//sleep(1);
?>