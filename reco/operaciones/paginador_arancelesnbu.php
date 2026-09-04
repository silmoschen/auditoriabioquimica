<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cArancelesNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$aranceles = new cArancelNBU;
$utiles = new cUtiles;

$RegistrosAMostrar=5;

//estos valores los recibo por GET
if(isset($_GET['pag'])){
	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_GET['pag'];        
//caso contrario los iniciamos
}else{
	$RegistrosAEmpezar=0;
	$PagAct=1;
	
}

$codos = $_GET['codos'];

$Resultado = $aranceles->getArancelesNBU($codos, $RegistrosAEmpezar, $RegistrosAMostrar);

echo '<hr>';

echo "<table border='0px'>";
echo "<tr>";
echo "<td width='80px' align='left'><b>Periodo</b></td>";
echo "<td width='70px' align='right'><b>Valor</b></td>";
echo "<td width='70px' align='right'><b>V.Dif.</b></td>";
echo "<td width='70px' align='right'><b>Factor</b></td>";
echo "<td width='70px' align='right'><b>NBU Os</b></td>";
echo "<td width='90px' align='right'><b>Bajas</b></td>";
echo "</tr>";

while($MostrarFila=mysql_fetch_array($Resultado)){
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='80px'>". $utiles->getPeriodoMM_AAAA($MostrarFila['periodo'])."</td>";
    echo "<td width='70px' align='right'>".$MostrarFila['valor']."</td>";
    echo "<td width='70px' align='right'>".$MostrarFila['valordif']."</td>";
    echo "<td width='70px' align='right'>".$MostrarFila['modulo']."</td>";
    echo "<td width='70px' align='right'>".$MostrarFila['nbu_os']."</td>";
    $borra = "BorraArancelNBU("."'".$MostrarFila['codos']."'".", "."'". $utiles->getPeriodoMM_AAAA($MostrarFila['periodo']) ."'".")";
    echo '<td width="90px" align="right"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros=mysql_num_rows($aranceles->getArancelesNBU($codos, 0, 1000000));

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
echo "<a onclick=\"PaginaArancelesNBU('1','','$edbaja', '$codos')\">Primero</a> ";
if($PagAct>1) echo "<a onclick=\"PaginaArancelesNBU('$PagAnt','','$edbaja', '$codos')\">Anterior</a> ";
echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"PaginaArancelesNBU('$PagSig','','$edbaja', '$codos')\">Siguiente</a> ";
echo "<a onclick=\"PaginaArancelesNBU('$PagUlt','','$edbaja', '$codos')\">Ultimo</a>";
echo "<br>";
echo "<br>";
echo "</FIELDSET>";
?>
