<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cApfijosNBU.php');

$apfijos = new cApfijosNBU;

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

$Resultado = $apfijos->getApFijos($codos, $RegistrosAEmpezar, $RegistrosAMostrar);

echo '<hr>';

echo "<table border='0px'>";
echo "<tr>";
echo "<td width='80px' align='left'><b>Codigo</b></td>";
echo "<td width='70px' align='right'><b>Periodo</b></td>";
echo "<td width='70px' align='right'><b>Monto</b></td>";
echo "<td width='90px' align='right'><b>Per.Hasta</b></td>";
echo "<td width='90px' align='right'><b>Per.Baja</b></td>";
echo "</tr>";

while($MostrarFila=mysql_fetch_array($Resultado)){
    echo "<tbody align = 'left'>";
    echo "<tr>";
    echo "<td width='80px'>". $MostrarFila['codanalisis']."</td>";
    echo "<td width='70px' align='right'>".$utiles->getPeriodoMM_AAAA($MostrarFila['periodo'])."</td>";
    echo "<td width='70px' align='right'>".$MostrarFila['importe']."</td>";
    echo "<td width='70px' align='right'>".$utiles->getPeriodoMM_AAAA($MostrarFila['perhasta'])."</td>";
    echo "<td width='70px' align='right'>".$utiles->getPeriodoMM_AAAA($MostrarFila['perbaja'])."</td>";
    $edita = "EditaApfijosNBU(".$MostrarFila['id'].")";
    echo '<td width="45px" align="right"><a href="javascript://" onclick="' . $edita . '">Editar</a></td>';
    $borra = "BorraApfijosNBU(".$MostrarFila['id']. ", " . $MostrarFila['codanalisis'] . ", " . $MostrarFila['importe']  . ", " . "'". $utiles->getPeriodoMM_AAAA($MostrarFila['periodo']) . "'" . ")";
    echo '<td width="45px" align="right"><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros=mysql_num_rows($apfijos->getApFijos($codos, 0, 1000000));

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
echo "<a onclick=\"PaginaApfijosNBU('1','','$edbaja', '$codos')\">Primero</a> ";
if($PagAct>1) echo "<a onclick=\"PaginaApfijosNBU('$PagAnt','','$edbaja', '$codos')\">Anterior</a> ";
echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"PaginaApfijosNBU('$PagSig','','$edbaja', '$codos')\">Siguiente</a> ";
echo "<a onclick=\"PaginaApfijosNBU('$PagUlt','','$edbaja', '$codos')\">Ultimo</a>";
echo "<br>";
echo "<br>";
echo "</FIELDSET>";
?>