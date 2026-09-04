<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$auditoria = new cAuditoria;
$utiles = new cUtiles;
$afiliado = new cAfiliados;

$idprof = $_REQUEST['idprof'];
$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$efexcluir = $_REQUEST['efector_excluir'];
$efincluir = $_REQUEST['efector_incluir'];

$tc = 4;

if ($_REQUEST['idprof'] != '000000') {
    $tc = 3;
}

$RegistrosAMostrar = 5;

//estos valores los recibo por GET
if (isset($_GET['pag'])) {
    $RegistrosAEmpezar = ($_GET['pag'] - 1) * $RegistrosAMostrar;
    $PagAct = $_GET['pag'];
//caso contrario los iniciamos
} else {
    $RegistrosAEmpezar = 0;
    $PagAct = 1;
}

$filtro = $_GET['filtro'];
if ($_GET['edbaja'] != '') {
    $edbaja = $_GET['edbaja'];
}

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

if ($nrodoc == '')
    $Resultado = $auditoria->getConsultaOrdenesAuditor($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, $RegistrosAEmpezar, $RegistrosAMostrar);
else
    $Resultado = $auditoria->getConsultaOrdenesAfiliado($codos, $nrodoc, $desde, $hasta, $tc, $efexcluir, $efincluir, $RegistrosAEmpezar, $RegistrosAMostrar);
    
echo '<hr>';
echo "<table border='0px' width='550px'>";
echo "<tbody align = 'left'>";
echo "<td width='200px'><b>Nro. Auditoría</b>";
echo "<td width='5px'><b>Auditor</b>";
echo "<td width='1px'><b>Fecha</b>";
echo "<td width='1px'><b>Nro.Doc.</b>";
echo "<td width='150px'><b>Paciente</b>";
$i = 0;
while ($MostrarFila = mysql_fetch_array($Resultado)) {
    $i = $i + 1;
    if ($i % 2 != 0) {
        $ext = 'bgcolor="#CCCCCC"';
    } else {
        $ext = '';
    }
    echo "<tr>";

    $es = '';
    if ($tc == 5) {
        if ($MostrarFila['anulada'] == 'S') {
            $es = ' [ A ]';
        }
    }

    echo "<td " . $ext . " width='200px'>" . $MostrarFila['nroauditoria'] . $es . "</td>";
    echo "<td " . $ext . " width='5px'>" . $MostrarFila['auditor'] . $es . "</td>";
    echo "<td " . $ext . " width='1px'>" . $utiles->getFechaDDMMAA($MostrarFila['fecha']) . "</td>";
    echo "<td " . $ext . " width='1px'>" . $MostrarFila['nrodoc'] . "</td>";

    $afiliado->getObject($codoseq, $MostrarFila['nrodoc']);
    echo "<td " . $ext . " width='150px'>" . substr($afiliado->getNombre(), 0, 24) . "</td>";

    $seleccion = "MostrarOrden(" . "'" . $MostrarFila['nroauditoria'] . "'" . ")";
    echo '<td width="1px"><a href="javascript://" onclick="' . $seleccion . '">Anular</a></td>';
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
//******--------determinar las p�ginas---------******//
if ($nrodoc == '')
    $NroRegistros = mysql_num_rows($auditoria->getConsultaOrdenesAuditor($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));
    //$NroRegistros = mysql_num_rows($auditoria->getConsultaOrdenesAfiliado($codos, $nrodoc, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));
else
    $NroRegistros = mysql_num_rows($auditoria->getConsultaOrdenesAfiliado($codos, $nrodoc, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));
    //$NroRegistros = mysql_num_rows($auditoria->getConsultaOrdenesAuditor($codos, $idprof, $desde, $hasta, $tc, $efexcluir, $efincluir, 0, 10000000000));

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
echo "<a onclick=\"Pagina('1','','$edbaja', $tc)\">Primero</a> ";
if ($PagAct > 1)
    echo "<a onclick=\"Pagina('$PagAnt','','$edbaja', $tc)\">Anterior</a> ";
echo "<strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong>";
if ($PagAct < $PagUlt)
    echo " <a onclick=\"Pagina('$PagSig','','$edbaja', $tc)\">Siguiente</a> ";
echo "<a onclick=\"Pagina('$PagUlt','','$edbaja', $tc)\">Ultimo</a>        ";
echo "<a onclick=\"CerrarListaOrdenes()\">   Cerrar</a>";
echo "<HR>";
?>
