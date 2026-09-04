<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
$nbu = new cNBU;

$RegistrosAMostrar=10;

//estos valores los recibo por GET
if(isset($_GET['pag'])){
	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_GET['pag'];        
//caso contrario los iniciamos
}else{
	$RegistrosAEmpezar=0;
	$PagAct=1;
	
}

$filtro = $_GET['filtro'];
if ($_GET['edbaja'] != '') {
  $edbaja = $_GET['edbaja'];
}

$Resultado = $nbu->getLista($filtro, $RegistrosAEmpezar, $RegistrosAMostrar);

echo "<table border='0px'>";
while($MostrarFila=mysql_fetch_array($Resultado)){
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='30px'>".$MostrarFila['codigo']."</td>";
    echo "<td width='500px'>".$MostrarFila['descrip']."</td>";
    $seleccion = "CodigoNBUSel("."'".$MostrarFila['codigo']."'".")";
    echo '<td><a href="javascript://" onclick="' . $seleccion . '">Sel</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros=mysql_num_rows($nbu->getLista($filtro, 0, 100000000000));

$PagAnt=$PagAct-1;
$PagSig=$PagAct+1;
$PagUlt=$NroRegistros/$RegistrosAMostrar;

//verificamos residuo para ver si llevar� decimales
$Res=$NroRegistros%$RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if($Res>0) $PagUlt=floor($PagUlt)+1;

echo "<HR>";
echo '<p align="center">';
//desplazamiento
echo "<a onclick=\"PaginaNBU('1','','$edbaja')\">Primero</a> ";
if($PagAct>1) echo "<a onclick=\"PaginaNBU('$PagAnt','','$edbaja')\">Anterior</a> ";
echo "<strong>PaginaNBU ".$PagAct."/".$PagUlt."</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig','','$edbaja')\">Siguiente</a> ";
echo "<a onclick=\"PaginaNBU('$PagUlt','','$edbaja')\">Ultimo</a>&nbsp;&nbsp;";
echo "</p>";

?>
