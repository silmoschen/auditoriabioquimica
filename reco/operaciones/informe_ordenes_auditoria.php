<?php
//include_once('conexion.php');

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
$efectoranert = '';
$tc = 4;
if ($idprof != '000000') {
    $tc = 3;
}

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

$Resultado= $auditoria->getConsultaOrdenes($codos, $idprof, $desde, $hasta, $tc, 0, 1000);

echo '<hr>';
echo "<h4>Listado de Ordenes Generadas entre el $desde y $hasta </h4>";
echo '<hr>';
echo "<table border='0px' width='550px'>";
echo "<tbody align = 'left'>";
echo "<tr><td width='200px'><b>Nro. Auditoría</b>";
echo "<td width='200px'><b>Fecha</b>";
echo "<td width='50px'><b>Nro.Doc.</b>";
echo "</tr>";

$cantidad = 0; $efectoranter = '';

while($MostrarFila=mysql_fetch_array($Resultado)){
    if ($MostrarFila['efector'] != $efectoranter) {
        $efector->getObject($MostrarFila['efector']);
        $efectoranter = $MostrarFila['efector'];
    }
	echo "<tr>";    
	echo "<td width='200px'>".$MostrarFila['nroauditoria']."</td>";
    echo "<td width='200px'>". $utiles->getFechaDDMMAA($MostrarFila['fecha'])."</td>";
    echo "<td width='50px'>".$MostrarFila['nrodoc']."</td>";
    
    $afiliado->getObject($codoseq, $MostrarFila['nrodoc']);
    echo "</tr>";
    echo "<tr>";
    echo "<td width='200px'>Af.:<font color = 'navy'>".substr($afiliado->getNombre(), 0, 20)."</font></td>";
    echo "<td width='200px'>Ef.<font color = 'navy'>:".substr($efector->getNombre(), 0, 20)."</font></td>";
    echo '<td width="1px">['.$MostrarFila['auditada'].']</td>';
    echo "</tr>";
    $cantidad = $cantidad + 1;
}
echo "</tbody>";
echo "</table>";

echo "<p align='center'><b>$cantidad Registros Listados</b></9>";

?>
