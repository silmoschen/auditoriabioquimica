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
$idprof = $_REQUEST['idprof'];

$auditoria = new cAuditoria;

$auditoria->obrasocial->verificarRPC($codos);

//==============================================================================
// JERARQUICO
if ($auditoria->obrasocial->_reglaNegocio == 3) {
    $res = $auditoria->getOrdenesPendientesAutorizacion($desde, $hasta, $idprof, $codos);
    //echo $auditoria->sql;
    if ($res > 0)
        echo '<font color="#FF0000">Tiene ' . $res . ' Ordenes Pendientes de Autorización. Las mismas deberán estar Autorizadas Antes de ser Facturadas.
            Recuerde que la Autorización, en esta Obra Social es por medio de Expediente.</font>';
} else
    echo '';
?>
