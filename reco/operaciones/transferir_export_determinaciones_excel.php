<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time", 2000);

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cExportOrdenes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cItemsAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaNBU.php');

//variables POST
$codos = $_REQUEST['codos'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

$exportar = new cExportOrdenes();
$utiles = new cUtiles();
$items = new cItemsAuditoria();
$nbu = new cNbu();
$eq = new cEquivalenciaNBU();

//----------------------------------------------------------

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=practicas_transferidas.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='0' width='750px' align='center'>";

echo "<tr>";
echo "<td width='150px'>Codigo</td>";
echo "<td width='150px'>Cantidades</td>";
echo "<td width='150px'>Unidades</td>";
echo "</tr>";

$trans = $exportar->getCodigosAutorizados();
while ($fila = mysql_fetch_array($trans)) {

    $__cod = $fila['codigo'];
    if ($nbu->getObject($fila['codigo']) == false)
        $__cod = $eq->getCodigoInverso($codos, $fila['codigo']);

    //$cant = $utiles->LlenarIzquierda($items->getCantidad_Determinaciones_Export($codos, $__cod, $desde, $hasta), 4, ' ');

    $cant = $utiles->LlenarIzquierda($exportar->getCantidadAutorizados($fila['codigo']), 4, ' ');

    $monto = number_format($nbu->getUnidadAdicional($codos, $fila['codigo']), 2, ',', '.');

    echo "<tr>";
    echo "<td width='150px'>" . $fila['codigo'] . "</td>";
    echo "<td width='150px'>" . $cant . "</td>";
    echo "<td width='150px'>" . $monto . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
