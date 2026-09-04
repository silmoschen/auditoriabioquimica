<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cDiagnosticosOMS.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$obj = new cAuditoria;
$u = new cUtiles;
$objmedico = new cMedicos;
$af = new cAfiliados;
$diagnostico = new cDiagnosticosOMS;
$efector = new cEfector;

$codos        = $_REQUEST['codos'];
$nrodoc       = $_REQUEST['nrodoc'];

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

$resultado = $obj->getOrdenesAfiliado($codos, $nrodoc);

 $af->getObject($codoseq, $nrodoc);

while($f = mysql_fetch_array($resultado)){
  if ($f['anulada'] != 'S') {

    $objmedico->getObject($codoseq, $f['idprof']);
    $diagnostico->getObject($f['iddiag']);    
    $efector->getObject($f['efector']);
   
    $obs = ''; $ley = 'Rechazadas: ';
    if ($f['auditada'] == 'N' and $f['diferida'] == 'S') { 
	$obs = ' (Auditoria Pendiente)'; 
	$ley = 'Pendientes: ';
    }
    echo '<hr>';
    echo '<br>Paciente: <b>' . $nrodoc . ' - ' . $af->getNombre() . '</b><font color="#0066FF">' . $obs . '</font>';
    echo '<br>Nro. Trans.: <b>' . $f['nroauditoria'] . '</b> Fecha: <b>' . $u->getFechaDDMMAAAA($f['fecha']) . '</b>';
    echo '<br>Médico: <b>' . $objmedico->getNombre() . '     </b>Efector: <b> ' . $efector->getNombre() . '</b>';
    echo '<br>Diagnóstico: <b>' . $diagnostico->getoms_cod() . ' - ' . $diagnostico->getDescrip() . '</b>';
    echo '<br>Obs. Auditor: <font color="#FF0000">' . $f['obsauditor'] . '</font>';

    $autorizadas = ''; $rechazadas = '';
    $res = $obj->getDeterminaciones("'" . $f['nroauditoria'] . "'" );
    while($r = mysql_fetch_array($res)){
        if ($r['estado'] == 'A') {
            $autorizadas = $autorizadas . $r['codigo'] . ' ';
        } else {
            $rechazadas = $rechazadas . $r['codigo'] . ' ';
        }
    }
    echo '<br>Autorizadas: <font color="#CC0033">' . $autorizadas . '</font>';
    echo '<br>' . $ley . '<font color="#009966">' . $rechazadas . '</font>';
    
  }
}
  
// --- En Historico
  
$resultado = $obj->getOrdenesAfiliadoHist($codos, $nrodoc);

while($f = mysql_fetch_array($resultado)){
  if ($f['anulada'] != 'S') {

    $objmedico->getObject($codoseq, $f['idprof']);
    $diagnostico->getObject($f['iddiag']);    
    $efector->getObject($f['efector']);
    
    $obs = ''; $ley = 'Rechazadas: ';
    if ($f['auditada'] == 'N' and $f['diferida'] == 'S') { 
	$obs = ' (Auditoria Pendiente)'; 
	$ley = 'Pendientes: ';
    }
    echo '<hr>';
    echo '<br>Paciente: <b>' . $nrodoc . ' - ' . $af->getNombre() . '</b><font color="#0066FF">' . $obs . '</font>';
    echo '<br>Nro. Trans.: <b>' . $f['nroauditoria'] . '</b> Fecha: <b>' . $u->getFechaDDMMAAAA($f['fecha']) . '</b>';
    echo '<br>Médico: <b>' . $objmedico->getNombre() . '     </b>Efector: <b> ' . $efector->getNombre() . '</b>';
    echo '<br>Diagnóstico: <b>' . $diagnostico->getoms_cod() . ' - ' . $diagnostico->getDescrip() . '</b>';
    echo '<br>Obs. Auditor: <font color="#FF0000">' . $f['obsauditor'] . '</font>';

    $autorizadas = ''; $rechazadas = '';
    $res = $obj->getDeterminacionesHist("'" . $f['nroauditoria'] . "'" );
    while($r = mysql_fetch_array($res)){
        if ($r['estado'] == 'A') {
            $autorizadas = $autorizadas . $r['codigo'] . ' ';
        } else {
            $rechazadas = $rechazadas . $r['codigo'] . ' ';
        }
    }
    echo '<br>Autorizadas: <font color="#CC0033">' . $autorizadas . '</font>';
    echo '<br>' . $ley . '<font color="#009966">' . $rechazadas . '</font>';
    
  }

}

?>
