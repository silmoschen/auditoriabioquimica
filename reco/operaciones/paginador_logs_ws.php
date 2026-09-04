<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cWsRespuestas.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$log = new cWsRespuestas();
$utiles = new cUtiles();
$obsocial = new cObSocial();

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


$Resultado = $log->getWsRespuestasList($RegistrosAEmpezar, $RegistrosAMostrar);

echo "<table border='0px' width='500px' align='center'>";
echo "<tr>";
echo "<td width='200px'><b>Fecha/Obra Social/Transacción</b></td>";
echo "<td width='500px'><b>Respuesta</b></td>";
echo "</tr>";

while($MostrarFila=mysql_fetch_array($Resultado)) {
    $obsocial->getObject($MostrarFila['codos']);
    echo "<tbody align = 'left'>";
    echo "<tr>";    
    echo "<td width='200px' valign='top'>" . $utiles->getFechaDDMMAA($MostrarFila['fecha']) . "<br/>" . $MostrarFila['codos'] . "<br/><b>" . $obsocial->getNombre() . "</b><br/>" . $MostrarFila['expediente'] .  "</td>";
    echo "<td width='500px'>" . $MostrarFila['respuesta'] . "</td>";    
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
$NroRegistros = $log->getWsRespuestasCount();

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