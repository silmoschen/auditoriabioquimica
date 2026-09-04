<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$auditoria = new cAuditoria;
$utiles = new cUtiles;
$afiliado = new cAfiliados;
$efector = new cEfector;
$obsocial = new cObSocial;

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$codos = $_REQUEST['codos'];

$resultado = $auditoria->detalle->getListCoseguros($codos, $desde, $hasta);

$obsocial->getObject($codos);

echo "<h4>Listado de Coseguros Lapso: $desde - $hasta </h4>";
echo "Obra Social: <b>" . $obsocial->nombre . "</b>";

echo '<hr>';
echo "<table border='0px' width='550px'>";
echo "<tr><td width='200px' font size='x-small'><b>Profesional</b>";
echo "<td width='150px' font size='x-small'><b>Nro. Auditoría</b>";
echo "<td width='80px' font size='x-small'><b>Fecha</b>";
echo "<td width='80px' align='right' font size='x-small'><b>Coseguro</b>";
echo "</tr>";

$monto = 0;
$coseguro = 0;
$efectoranter = "";
$nombre = "";
$nroanter = "";
$fechaanter = "";
$total = 0;

while ($MostrarFila = mysql_fetch_array($resultado)) {


    if ($MostrarFila['nroauditoria'] != $nroanter && $monto > 0) {
        echo "<tr>";
        echo "<td width='200px' font size='1'>" . $nombre . "</font></td>";
        echo "<td width='150px' font size='1'>" . $nroanter . "</font></td>";
        echo "<td width='80px' font size='1'>" . $fechaanter . "</font></td>";
        echo "<td width='80px' align='right' font size='1'>" . number_format($coseguro - $monto, 2, '.', ',') . "</font></td>";
        echo "</tr>";
        $total = $total + ($coseguro - $monto);
        $coseguro = 0;
        $monto = 0;
        $nombre = "";
    }

    if ($MostrarFila['efector'] != $efectoranter) {
        $efector->getObject($MostrarFila['efector']);
        $efectoranter = $MostrarFila['efector'];
        $nombre = $efector->nombre;
        if ($total > 0) {
            echo "<tr>";
            echo "<td width='200px' font size='1'><b>Tot. Efector:</b></font></td>";
            echo "<td width='150px' font size='1'></font></td>";
            echo "<td width='80px' font size='1'></font></td>";
            echo "<td width='80px' align='right' font size='1'><b>" . number_format($total, 2, '.', ',') . "</b></font></td>";
            echo "</tr>";
            echo "<tr></tr>";
            $total = 0;
        }
    }

    $monto = $monto + $MostrarFila['monto'];
    $coseguro = $coseguro + $MostrarFila['coseguro'];
    $fechaanter = $utiles->getFechaDDMMAA($MostrarFila['fecha']);
    $nroanter = $MostrarFila['nroauditoria'];
}

echo "<tr>";
echo "<td width='200px'><font-face='Arial' size='1'>" . $nombre . "</font></td>";
echo "<td width='150px'><font-face='Arial' size='1'>" . $nroanter . "</font></td>";
echo "<td width='80px'><font-face='Arial' size='1'>" . $fechaanter . "</font></td>";
echo "<td width='80px' align='right'><font-face='Arial' size='1'>" . number_format($coseguro - $monto, 2, '.', ',') . "</font></td>";
echo "</tr>";
$total = $total + ($coseguro - $monto);

if ($total > 0) {
    echo "<tr>";
    echo "<td width='200px' font size='1'><b>Tot. Efector:</b></font></td>";
    echo "<td width='150px' font size='1'></font></td>";
    echo "<td width='80px' font size='1'></font></td>";
    echo "<td width='80px' align='right' font size='1'><b>" . number_format($total, 2, '.', ',') . "</b></font></td>";
    echo "</tr>";
    echo "<tr></tr>";
    $total = 0;
}

echo "</table>";
?>