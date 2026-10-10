<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$codos = $_REQUEST['codos'];
$nbu = new cNBU;
$resultado = $nbu->getPracticasObraSocial($codos);

echo "<hr>";
echo "<table border='0' align='left' style='text-align:left;'>";
echo "<tr>";
echo "<td align='left'><b>Código</b></td>";
echo "<td align='left'><b>Determinación</b></td>";
echo "<td align='right'><b>Unidad</b></td>";
echo "</tr>";

while ($fila = mysql_fetch_array($resultado)) {
    echo "<tr>";
    echo "<td align='left'>" . $fila['codigo'] . "</td>";
    echo "<td align='left'>" . $fila['descrip'] . "</td>";
    echo "<td align='right'>" . $fila['unidad'] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>
