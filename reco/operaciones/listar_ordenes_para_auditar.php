<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$auditoria = new cAuditoria;
$utiles = new cUtiles;
$afiliado = new cAfiliados;
$efector = new cEfector;

$desde        = $_REQUEST['desde'];
$hasta        = $_REQUEST['hasta'];
$idprof       = $_REQUEST['idprof'];
$codos        = $_REQUEST['codos'];
$modo         = $_REQUEST['modo'];
$nrodoc       = $_REQUEST['nrodoc'];
$efexcluir    = $_REQUEST['efector_excluir'];
$efincluir    = $_REQUEST['efector_incluir'];
$efectoranert = '';
$tc = 4;
if ($idprof != '000000') {
    $tc = 3;
}
//echo $efexcluir . ' '. $modo . ' ' . $efincluir . '....................';
$RegistrosAMostrar=8;

//estos valores los recibo por GET
if(isset($_GET['pag'])){
	$RegistrosAEmpezar=($_GET['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_GET['pag'];        
//caso contrario los iniciamos
}else{
	$RegistrosAEmpezar=0;
	$PagAct=1;	
}

if ($modo == 1) {
  $Resultado= $auditoria->getConsultaOrdenesDiferidasPendientes($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, $RegistrosAEmpezar, $RegistrosAMostrar);
}
if ($modo == 2) {
  $Resultado= $auditoria->getConsultaOrdenesDiferidas($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, $RegistrosAEmpezar, $RegistrosAMostrar);
}
if ($modo == 10) {
  $Resultado= $auditoria->getConsultaOrdenesAfiliado($codos, $nrodoc, $desde, $hasta, $tc, $efexcluir, $efincluir, $RegistrosAEmpezar, $RegistrosAMostrar);
}
if ($modo == 5) {  
  $Resultado= $auditoria->getConsultaOrdenesStandBy($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, $RegistrosAEmpezar, $RegistrosAMostrar);
}

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

echo "<table border='0px' width='550px'>";
echo "<tr><td width='200px'><b>Nro. Auditoría</b></td>";
echo "<td width='200px'><b>Fecha</b></td>";
echo "<td width='50px'><b>Nro.Doc.</b></td>";
echo "</tr>";

$i = 0; $efectoranter = '';
while($MostrarFila=mysql_fetch_array($Resultado)){
    if ($MostrarFila['efector'] != $efectoranter) {
        $efector->getObject($MostrarFila['efector']);
        $efectoranter = $MostrarFila['efector'];
    }

    $i = $i + 2;
    if ($i % 2 == 0) {
        $ext = 'bgcolor="#CCCCCC"';
    } else {
        $ext = '';
    }

    echo "<tr>";
    echo "<td " . $ext . " width='170px'>".$MostrarFila['nroauditoria']."</td>";
    if (strlen($MostrarFila['auditor']) > 0) {
        $audit = '  -  ' . $MostrarFila['auditor'];
    } else {
        $audit = '';
    }
    echo "<td " . $ext . " width='230px'>". $utiles->getFechaDDMMAA($MostrarFila['fecha']) . '  ' . $audit . "</td>";
    echo "<td " . $ext . " width='50px'>".$MostrarFila['nrodoc']."</td>";
    echo "<td " . $ext . " width='30px'></td>";
    $afiliado->getObject($codoseq, $MostrarFila['nrodoc']);
    echo "</tr>";
    echo "<tr>";
    echo "<td width='200px'>Af.:<b>".substr($afiliado->getNombre(), 0, 24)."</b></td>";
    echo "<td width='200px'>Ef.<b>:".substr($efector->getNombre(), 0, 24)."</b></td>";
    $seleccion = "MostrarOrden("."'".$MostrarFila['nroauditoria']."'".","."'".$MostrarFila['codos']."'".","."'".$MostrarFila['nrodoc']."'".")";
    $anulacion = "Anular_Orden("."'".$MostrarFila['nroauditoria']."'".")";
    echo '<td width="1px"><a href="javascript://" onclick="' . $seleccion . '">Auditar</a></td>';
    echo "<td width='30px'><div id=" . $MostrarFila['nroauditoria'] ."></td>";
    if ($modo == 5) {
      echo '<td width="1px"><a href="javascript://" onclick="' . $anulacion . '">Anular</a></td>';
    }
    echo "</tr>";
}
echo "</table>";
//******--------determinar las p�ginas---------******//
if ($modo == 1) {
  $NroRegistros=mysql_num_rows($auditoria->getConsultaOrdenesDiferidasPendientes($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));
}
if ($modo == 2) {
  $NroRegistros=mysql_num_rows($auditoria->getConsultaOrdenesDiferidas($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));
}
if ($modo == 10) {
  $NroRegistros=mysql_num_rows($auditoria->getConsultaOrdenesAfiliado($codos, $nrodoc, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));
}
if ($modo == 5) {
  $NroRegistros=mysql_num_rows($auditoria->getConsultaOrdenesStandBy($codos, $nrodoc, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));
}


$PagAnt=$PagAct-1;
$PagSig=$PagAct+1;
$PagUlt=$NroRegistros/$RegistrosAMostrar;

//verificamos residuo para ver si llevar� decimales
$Res=$NroRegistros%$RegistrosAMostrar;
// si hay residuo usamos funcion floor para que me
// devuelva la parte entera, SIN REDONDEAR, y le sumamos
// una unidad para obtener la ultima pagina
if($Res>0) $PagUlt=floor($PagUlt)+1;
if($NroRegistros==0) {$PagUlt=0;}

echo "<HR>";
echo '<p align="center">';
//desplazamiento
echo "<a onclick=\"Pagina('1')\">Primero</a> ";
if($PagAct>1) echo "<a onclick=\"Pagina('$PagAnt')\">Anterior</a> ";
echo "<strong>Pagina ".$PagAct."/".$PagUlt."</strong>";
if($PagAct<$PagUlt)  echo " <a onclick=\"Pagina('$PagSig')\">Siguiente</a> ";
if($NroRegistros>0) {
  echo "<a onclick=\"Pagina('$PagUlt')\">Ultimo</a>";
}
echo "<HR>";

?>
