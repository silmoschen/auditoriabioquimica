<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$nbu = new cNBU;
$utiles = new cUtiles;

$codigo = $_GET['codigo'];

$Resultado = $nbu->getUnidadesNBU($codigo);

echo '<hr>';

echo "<table border='0px'>";
echo "<tr>";
echo "<td width='80px' align='left'><b>Período</b></td>";
echo "<td width='150px' align='right'><b>Unidades</b></td>";
echo "<td width='150px' align='right'><b>Unidades Dif.</b></td>";
echo "</tr>";

while ($MostrarFila = mysql_fetch_array($Resultado)) {
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='80px'>" . $utiles->getPeriodoMM_AAAA_FAAAAMM($MostrarFila['periodo']) . "</td>";
    echo "<td width='150px' align='right'>" . number_format($MostrarFila['unidades'], 2, '.', ',') . "</td>";
    echo "<td width='150px' align='right'>" . number_format($MostrarFila['unidaddif'], 2, '.', ',') . "</td>";
    $borra = "BorrarUnidad(".$MostrarFila['codigo']. "," . $MostrarFila['periodo'] .")";
    echo '<td width="90px" align="right"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
?>
