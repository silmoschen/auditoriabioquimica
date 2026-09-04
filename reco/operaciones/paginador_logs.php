<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cLogs.php');
$log = new cLogs();

$RegistrosAMostrar = 5;

//estos valores los recibo por GET
if(isset($_GET['pag'])) {
    $RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
    $PagAct=$_GET['pag'];
//caso contrario los iniciamos
}else {
    $RegistrosAEmpezar=0;
    $PagAct=1;
}

$usuario = $_REQUEST['usuario'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

$Resultado = $log->getLogs($usuario, $desde, $hasta, $RegistrosAEmpezar, $RegistrosAMostrar);

echo "<table border='0px' width='500px' align='center'>";
echo "<tr>";
echo "<td width='150px'><b>Usuario</b></td>";
echo "<td width='300px'><b>Fecha / Hora</b></td>";
echo "</tr>";

while($MostrarFila=mysql_fetch_array($Resultado)) {
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='150px'>".$MostrarFila['usuario']."</td>";
    echo "<td width='300px'>".$MostrarFila['fechahora']."</td>";
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros=mysql_num_rows($log->getLogs($usuario, $desde, $hasta, 0, 1000000));

$PagAnt=$PagAct-1;
$PagSig=$PagAct+1;
$PagUlt=$NroRegistros/$RegistrosAMostrar;

//verificamos residuo para ver si llevar� decimales
$Res=$NroRegistros%$RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if($Res>0) $PagUlt=floor($PagUlt)+1;

echo "<FIELDSET>";

//desplazamiento
echo "<a onclick=\"Pagina('1')\">Primero</a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt')\">Anterior</a> ";
echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig')\">Siguiente</a> ";
echo "<a onclick=\"Pagina('$PagUlt')\">Ultimo</a>";
echo "<br>";
echo "<br>";
echo "</FIELDSET>";

?>