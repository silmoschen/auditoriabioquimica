<?php
//include_once('conexion.php');
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');

$modelo = new cModelos;

$RegistrosAMostrar = 10;

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

$Resultado = $modelo->getModelosDef($codos, $RegistrosAEmpezar, $RegistrosAMostrar);

echo '<hr>';
echo "<table border='0px' width='550px'>";
echo "<tbody align = 'left'>";
echo "<td width='10px'><b>Id.</b>";
echo "<td width='200px'><b>Descripción</b>";
echo "<td width='2px'><b>Cons.</b>";
while ($MostrarFila = mysql_fetch_array($Resultado)) {
    echo "<tr>";
    echo "<td width='10px'>" . $MostrarFila['oms_cod'] . "</td>";
    echo "<td width='200px'>" . $MostrarFila['descrip'] . "</td>";
    $seleccion = "ConsultarModelo1(" . "'" . $MostrarFila['oms_cod'] . "'" . ")";
    echo '<td width="2px"><a href="javascript://" onclick="' . $seleccion . '">Cons</a></td>';
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros = mysql_num_rows($modelo->getModelosDef($codos, '', ''));

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
echo "<a onclick=\"PaginaMod('1','','$edbaja')\">Primero</a> ";
if ($PagAct > 1)
    echo "<a onclick=\"PaginaMod('$PagAnt','','$edbaja')\">Anterior</a> ";
echo "<strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong>";
if ($PagAct < $PagUlt)
    echo " <a onclick=\"PaginaMod('$PagSig','','$edbaja')\">Siguiente</a> ";
echo "<a onclick=\"PaginaMod('$PagUlt','','$edbaja')\">Ultimo</a>        ";
echo "<a onclick=\"CerrarListaOrdenes()\">Finalizar Consulta</a>";
?>
