<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');
$modelo = new cModelos;

$RegistrosAMostrar=200;

//estos valores los recibo por GET
if(isset($_GET['pag'])){
	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_GET['pag'];        
//caso contrario los iniciamos
}else{
	$RegistrosAEmpezar=0;
	$PagAct=1;	
}

$codos = $_REQUEST['codos'];

$Resultado = $modelo->getModelosDef($codos, $RegistrosAEmpezar, $RegistrosAMostrar);

echo "<table border='0px'>";

while($MostrarFila=mysql_fetch_array($Resultado)){    
	echo "<tr>";
	echo "<td width='80px'><input type='checkbox' name='list' id='list' value=" . $MostrarFila['oms_cod'] . "  onClick='chequear()' " . '>' . $MostrarFila['oms_cod'] . "</td>";
	echo "<td width='350px'>".$MostrarFila['descrip']."</td>";
    echo "</tr>";    
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros=mysql_num_rows($modelo->getModelosDef($codos, 0, 1000000));

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
echo "<a onclick=\"Pagina('1','','$edbaja')\">Primero</a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt','','$edbaja')\">Anterior</a> ";
echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig','','$edbaja')\">Siguiente</a> ";
echo "<a onclick=\"Pagina('$PagUlt','','$edbaja')\">Ultimo</a>";
echo "<HR></p>";

?>