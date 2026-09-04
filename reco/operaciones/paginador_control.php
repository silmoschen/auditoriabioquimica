<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/conexion.php');
$RegistrosAMostrar = 5;

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

if ($filtro == '' or $filtro == 'undefined') {
  $Resultado=mysql_query("SELECT * FROM tipocontrol ORDER BY descrip LIMIT $RegistrosAEmpezar, $RegistrosAMostrar",$con);
} else {
  $Resultado=mysql_query("SELECT * FROM tipocontrol WHERE descrip LIKE '$filtro%' ORDER BY descrip LIMIT $RegistrosAEmpezar, $RegistrosAMostrar",$con);
}

//echo "SELECT * FROM tipocontrol WHERE descrip LIKE '$filtro%'";
echo $ebaja;

echo "<table border='0px'>";
while($MostrarFila=mysql_fetch_array($Resultado)){
    echo "<tbody align = 'left'>";
	echo "<tr>";
	echo "<td width='30px'>".$MostrarFila['idcontrol']."</td>";
	echo "<td width='500px'>".$MostrarFila['descrip']."</td>";
    	if ($edbaja == 'B' or $edbaja == 'T') {
          //$borra = "Borrar(".$MostrarFila['idcontrol'].")";
          $borra = "Borrar("."'".$MostrarFila['idcontrol']."'".")";
          echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
        }
       	if ($edbaja == 'E' or $edbaja == 'T') {
          //$edita = "EditarRegistro(".$MostrarFila['idcontrol'].")";
          $edita = "EditarRegistro("."'".$MostrarFila['idcontrol']."'".")";
          echo '<td><a href="javascript://" onclick="' . $edita . '">Editar</a></td>';
        }
	echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros=mysql_num_rows(mysql_query("SELECT * FROM tipocontrol",$con));

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
echo "<a onclick=\"Pagina('1','','$edbaja')\">Primero</a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt','','$edbaja')\">Anterior</a> ";
echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig','','$edbaja')\">Siguiente</a> ";
echo "<a onclick=\"Pagina('$PagUlt','','$edbaja')\">Ultimo</a>";
echo "<br>";
echo "<br>";
echo "</FIELDSET>";

?>
