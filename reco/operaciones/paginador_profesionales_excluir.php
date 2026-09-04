<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfectoresExcluidos.php');

$efector = new cEfector;
$efexcluido = new cEfectoresExcluidos;

$RegistrosAMostrar = 1000;

$codos = $_REQUEST['codos'];

//estos valores los recibo por GET
if (isset($_GET['pag'])) {
    $RegistrosAEmpezar = ($_GET['pag'] - 1) * $RegistrosAMostrar;
    $PagAct = $_GET['pag'];
//caso contrario los iniciamos
} else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
}

if (isset($_GET['filtro'])) {
    $filtro = $_GET['filtro'];
} else {
    $filtro = '';
}
if (isset($_GET['edbaja'])) {
    $edbaja = $_GET['edbaja'];
} else {
    $edbaja = '';
}

$res = $efector->getEfectores($RegistrosAEmpezar, $RegistrosAMostrar, $filtro);

echo '<hr>';
echo "<table border='0px' width='550px'>";
echo "<tbody align = 'left'>";
echo "<td width='20px'/>";
echo "<td width='120px'><b>Código</b></td>";
echo "<td width='400px'><b>Nombre</b></td>";
$i = 0;
while ($MostrarFila = mysql_fetch_array($res)) {

    $i = $i + 1;
    if ($i % 2 != 0) {
        $ext = 'bgcolor="#CCCCCC"';
    } else {
        $ext = '';
    }
    echo "<tr>";

    $e = "";
    if ($efexcluido->getObject($codos, $MostrarFila['idprof']) > 0) $e = "checked='checked'";

    echo "<td width='20px'><input type='checkbox'" . $e . " name='list' id='list' value=" . $MostrarFila['idprof'] . "  onClick='chequear(" . $MostrarFila["idprof"] . ")' " . '>' . $MostrarFila['codigo'] . "</td>";
    echo "<td " . $ext . " width='120px'>" . $MostrarFila['idprof'] . $es . "</td>";
    echo "<td " . $ext . " width='400px'>" . $MostrarFila['nombre'] . "</td>";

    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros = mysql_num_rows($efector->getEfectores($RegistrosAEmpezar, 100000000000, $filtro));

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

echo "<HR>";
echo '<p align="center">';
//desplazamiento
echo "<a onclick=\"Pagina('1','','$edbaja', $tc)\">Primero</a> ";
if ($PagAct > 1)
    echo "<a onclick=\"Pagina('$PagAnt','','$edbaja', $tc)\">Anterior</a> ";
echo "<strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong>";
if ($PagAct < $PagUlt)
    echo " <a onclick=\"Pagina('$PagSig','','$edbaja', $tc)\">Siguiente</a> ";
echo "<a onclick=\"Pagina('$PagUlt','','$edbaja', $tc)\"> Ultimo</a>        ";
echo "<HR>";
?>
