<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCapitas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$capita = new cCapitas;
$utiles = new cUtiles;

$RegistrosAMostrar = 5;

//estos valores los recibo por GET
if (isset($_GET['pag'])) {
    $RegistrosAEmpezar = ($_GET['pag'] - 1) * $RegistrosAMostrar;
    $PagAct = $_GET['pag'];
//caso contrario los iniciamos
} else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
}

$codos = $_GET['codos'];
$edbaja = "T";

$Resultado = $capita->getCapitas($codos, $RegistrosAEmpezar, $RegistrosAMostrar);

echo '<hr>';

echo "<table border='0px'>";
echo "<tr>";
echo "<td width='80px' align='left'><b>Período</b></td>";
echo "<td width='70px' align='right'><b>C. 1º Nivel</b></td>";
echo "<td width='70px' align='right'><b>C. 3º Nivel</b></td>";
echo "<td width='70px' align='right'><b>C. Unidades</b></td>";
echo "<td width='90px' align='right'><b>Bajas</b></td>";
echo "</tr>";

while ($MostrarFila = mysql_fetch_array($Resultado)) {
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='80px'>" . $utiles->getPeriodoMM_AAAA_FAAAAMM($MostrarFila['periodo']) . "</td>";
    echo "<td width='70px' align='right'>" . number_format($MostrarFila['capita'], 2, '.', ',') . "</td>";
    echo "<td width='70px' align='right'>" . number_format($MostrarFila['capita2'], 2, '.', ',') . "</td>";
    echo "<td width='70px' align='right'>" . number_format($MostrarFila['capita3'], 2, '.', ',') . "</td>";
    $borra = "Borrar(" . "'" . $MostrarFila['codos'] . "'" . ", " . "'" . $utiles->getPeriodoMM_AAAA_FAAAAMM($MostrarFila['periodo']) . "'" . ")";
    echo '<td width="90px" align="right"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros = mysql_num_rows($capita->getCapitas($codos, 0, 1000000));

$PagAnt = $PagAct - 1;
$PagSig = $PagAct + 1;
$PagUlt = $NroRegistros / $RegistrosAMostrar;

//verificamos residuo para ver si llevar� decimales
$Res = $NroRegistros % $RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if ($Res > 0)
    $PagUlt = floor($PagUlt) + 1;

echo "<FIELDSET>";

//desplazamiento
echo "<a onclick=\"PaginaArancelesNBU('1','','$edbaja', '$codos')\">Primero</a> ";
if ($PagAct > 1)
    echo "<a onclick=\"PaginaArancelesNBU('$PagAnt','','$edbaja', '$codos')\">Anterior</a> ";
echo "<strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong>";
if ($PagAct < $PagUlt)
    echo " <a onclick=\"PaginaArancelesNBU('$PagSig','','$edbaja', '$codos')\">Siguiente</a> ";
echo "<a onclick=\"PaginaArancelesNBU('$PagUlt','','$edbaja', '$codos')\">Ultimo</a>";
echo "<br>";
echo "<br>";
echo "</FIELDSET>";
?>
