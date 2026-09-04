<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$codos  = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$ndoc   = "[" . $_REQUEST['nrodoc'] . "]";
$alta = "DarAltaPaciente(".$_REQUEST['codos'].",".$_REQUEST['nrodoc'].")";

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

$obj = new cAfiliados;

if ($obj->getObject($codoseq, $nrodoc)) {
    echo $obj->getNombre();
} else {
    echo 'Paciente Inexistente';
}
?>