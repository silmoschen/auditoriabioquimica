<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMFBoletas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$mf = new cMFBoletas();
$utiles = new cUtiles;

$RegistrosAMostrar= 10;

$codos = $_GET['codos'];

$Resultado = $mf->getApFijos($codos, 0, 100000000000);

echo "<table border='0px'>";
echo "<tr>";
echo "<td width='80px' align='left'><b>Periodo</b></td>";
echo "<td width='80px' align='left'><b>Vig.Desde</b></td>";
echo "<td width='120px' align='left'><b>Concepto</b></td>";
echo "<td width='100px' align='right'><b>Monto</b></td>";
echo "<td width='100px' align='right'><b>Tipo</b></td>";
echo "<td width='90px' align='right'><b>Bajas</b></td>";
echo "</tr>";

while($MostrarFila=mysql_fetch_array($Resultado)){
    if ($MostrarFila['tipo'] == 1) {
        $tm = 'x Boleta';
    } else {
        $tm = 'x Determinación';
    }
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='80px'>". $utiles->getPeriodoMM_AAAA($MostrarFila['periodo'])."</td>";
    echo "<td width='80px'>". $utiles->getFechaDDMMAAAA($MostrarFila['fecha'])."</td>";
    echo "<td width='120px' align='left'>".$MostrarFila['concepto']."</td>";
    echo "<td width='100px' align='right'>". number_format($MostrarFila['monto'], 2, ',', '.') ."</td>";
    echo "<td width='100px' align='right'>".$tm."</td>";
    $borra = "Borrar(".$MostrarFila['ID'].")";
    echo '<td width="90px" align="right"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
?>
