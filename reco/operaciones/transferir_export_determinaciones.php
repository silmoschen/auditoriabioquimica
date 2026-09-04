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

echo "<table border='0px' width='550px'>";

//variables POST
$codos = $_REQUEST['codos'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

$exportar = new cExportOrdenes();
$utiles = new cUtiles();
$items = new cItemsAuditoria();
$nbu = new cNbu();
$eq = new cEquivalenciaNBU();

// Transferimos los datos al archivo
$salida = '../actualizar/ordenes_determinaciones.txt';
$archivo = fopen($salida, 'w');
//----------------------------------------------------------
echo '<tr><td align="center">*** RESUMEN DE PROCESOS CORRIDOS ***</td></tr>';
echo '<tr><td align="left">Procesando DETERMINACIONES :: ' . date('H:i:s') . '</td></tr>';

$trans = $exportar->getCodigosAutorizados();
while ($fila = mysql_fetch_array($trans)) {
    //if ($nbu->getObject($fila['codigo']) != null and $fila['codigo'] != '660000' /*and $exportar->validarPractica($codos, $fila['codigo'])*/) {

    $__cod = $fila['codigo'];
    if ($nbu->getObject($fila['codigo']) == false)
        $__cod = $eq->getCodigoInverso($codos, $fila['codigo']);

    //$cant = $utiles->LlenarIzquierda($items->getCantidad_Determinaciones_Export($codos, $__cod, $desde, $hasta), 4, ' ');
    
    $cant = $utiles->LlenarIzquierda($exportar->getCantidadAutorizados($fila['codigo']), 4, ' ');
    
    $monto = number_format($nbu->getUnidadAdicional($codos, $fila['codigo']), 2, '.', ',');
    fwrite($archivo, $fila['codigo'] . '     ' . $cant . '    ' . $monto . " \r\n");

    //}
}

fclose($archivo);

echo '<tr><td align="left">FIN DEL PROCESO:: ' . date('H:i:s') . '</td></tr></table>';

echo '<hr>';

$root = "../actualizar/";
$file = "ordenes_determinaciones.csv";

echo "<table border='0px' width='550px' align='center'>";
echo '<tr>';
echo '<td width="100px">Finalizado ...!</td>';
echo '<td width="350px" align="center">' . $mensaje_final . '</td>';
echo '<td width="100px"><div id="ver_ocultar"></div></td>';
echo '</tr>';
// Link para Descargamos el Archivo
echo '<tr>';
echo '<td width="100px"></td>';
echo '<td width="350px" align="center"><a href="/' . $ruta . '/operaciones/download.php?root=' . $root . '&file=' . $file . '">Descargar Archivo de Practicas Realizadas</a></td>';
echo '<td width="100px"></td>';
echo '<tr>';
echo '</table>';
?>
