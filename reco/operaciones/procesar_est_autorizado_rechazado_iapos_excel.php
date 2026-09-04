<?php

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=estadistica.xls");
header("Pragma: no-cache");
header("Expires: 0");

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cArancelesNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$codos = $_REQUEST['codos'];
$tipo  = $_REQUEST['tipo'];

if ($tipo == 'A') {
    $modo = 'Codigos Autorizados';
} else {
    $modo = 'Codigos Rechazados';
}

$items = new cItemsAuditoria();
$nbu = new cNBU();
$arancel = new cArancelNBU();
$entidad = new cEntidad();

$entidad->getObject();

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
// Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px">';
echo 'Prestador: <b>' . $entidad->getNombre() . '</b>';
echo '</td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="55px" align="right"></td>';
echo '<td width="50px" align="right"></td>';
echo '<td width="60px" align="right"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px">';
echo 'Periodo: <b>' . $desde . ' - ' . $hasta . '</b>';
echo '</td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="55px" align="right"></td>';
echo '<td width="50px" align="right"></td>';
echo '<td width="60px" align="right"></td>';
echo '</tr>';

echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px">';
echo '<b>Auditoria Bioquimica: ' . $modo . '</b>';
echo '</td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="35px" align="right"></td>';
echo '<td width="55px" align="right"></td>';
echo '<td width="50px" align="right"></td>';
echo '<td width="60px" align="right"></td>';
echo '</tr>';

echo '<tr></tr>';
echo '<tr>';
echo '<td width="50px">Codigo</td>';
echo '<td width="200px">Determinacion</td>';
echo '<td width="35px" align="right">Cant.</td>';
echo '<td width="35px" align="right">U.NBU</td>';
echo '<td width="35px" align="right">Arancel</td>';
echo '<td width="55px" align="right">Total</td>';
echo '<td width="50px" align="right">Tot NBU</td>';
echo '<td width="60px" align="right">Total</td>';
echo '</tr>';

$total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0;
$ss = 0;

$resultado = $items->getCodigosEstadistica($desde, $hasta, $codos, $tipo);
while($fila=mysql_fetch_array($resultado)) {

    $res = $items->getCantidadCodigosEstadistica($fila['codigo'], $desde, $hasta, $codos, $tipo);

    $cant = 0;
    if ($res != null) {
        while($f = mysql_fetch_array($res)) {
            $cant = $f['cantidad'];
        }
    }

    if ($cant > 0) {
        $nbu->getObject($fila['codigo']);
        $periodo = substr($desde, 3, 2) . '/' . substr($desde, 6, 4);

        if ($ss == 0) {
            $aran = $arancel->getArancelNBU($codoseq, $periodo);
            $ss = 1;
        }
        echo '<tr>';
        echo '<td width="50px">' . $fila['codigo'] . '</td>';
        echo '<td width="200px">' . $nbu->getDescrip() . '</td>';
        echo '<td width="35px" align="right">' . number_format($cant, 2,',','.') . '</td>';
        echo '<td width="35px" align="right">' . number_format($nbu->getUnidad(), 2,',','.') . '</td>';
        echo '<td width="35px" align="right">' . number_format($aran, 2,',','.') . '</td>';
        echo '<td width="55px" align="right">' . number_format($nbu->getUnidad() * $aran, 2,',','.') . '</td>';
        echo '<td width="50px" align="right">' . number_format($cant * $nbu->getUnidad(), 2,',','.') . '</td>';
        echo '<td width="60px" align="right">' . number_format($cant * ($nbu->getUnidad() * $aran), 2,',','.') . '</td>';
        echo '</tr>';

        $total1 = $total1 + $cant;
        $total2 = $total2 + $nbu->getUnidad();
        $total3 = $total3 + ($nbu->getUnidad() * $aran);
        $total4 = $total4 + ($cant * $nbu->getUnidad());
        $total5 = $total5 + ($cant * ($nbu->getUnidad() * $aran));
    }
}
echo '</table>';

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px">Totales:</td>';
echo '<td width="40px" align="right">' . $total1 . '</td>';
echo '<td width="30px" align="right">' . $total2 . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . $total3 . '</td>';
echo '<td width="50px" align="right">' . $total4 . '</td>';
echo '<td width="60px" align="right">' . $total5 . '</td>';
echo '</tr>';

$cantidad_ordenes = $items->getCantidadOrdenesEstadistica($desde, $hasta, $codos, $tipo);

echo '<table width = "550px" align="center">';
echo '<tr></tr>';
echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px">Cant. Ordenes</td>';
echo '<td width="40px" align="right">' . $cantidad_ordenes . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="60px" align="right">' . '</td>';
echo '</tr>';

$cantidad_pacientes = $items->getCantidadPacientesEstadisticas($desde, $hasta, $codos, $tipo);

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px">Cant. Pacientes</td>';
echo '<td width="40px" align="right">' . $cantidad_pacientes . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="60px" align="right">' . '</td>';
echo '</tr>';

?>
