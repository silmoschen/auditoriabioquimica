<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cArancelesNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$codos = $_REQUEST['codos'];
$tipo  = $_REQUEST['tipo'];

if ($tipo == 'A') {
    $modo = 'Códigos Autorizados';
} else {
    $modo = 'Códigos Rechazados';
}

$items = new cItemsAuditoria();
$nbu = new cNBU();
$arancel = new cArancelNBU();
$entidad = new cEntidad();

$entidad->getObject();

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
// Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td>Prestador: <b>' . $entidad->getNombre() . '</b></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Período: <b>' . $desde . ' - ' . $hasta . '</b></td>';
echo '</tr>';
echo '<tr>';
echo '<td><b>Auditoría Bioquímica: ' . $modo . '</b></td>' ;
echo '</tr>';
echo '<tr>';
echo '<td><hr></td>';
echo '</tr>';

echo '</table>';

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td width="50px">Código</td>';
echo '<td width="200px">Determinación</td>';
echo '<td width="35px" align="right">Cant.</td>';
echo '<td width="35px" align="right">U.NBU</td>';
echo '<td width="35px" align="right">Arancel</td>';
echo '<td width="55px" align="right">Total</td>';
echo '<td width="50px" align="right">Tot NBU</td>';
echo '<td width="60px" align="right">Total</td>';
echo '</tr>';
echo '</table>';

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td><hr></td>';
echo '</tr>';
echo '</table>';

echo '<table width = "550px" align="center">';

$total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0;
$ss = 0;

$resultado = $items->getCodigosEstadistica($desde, $hasta, $codos, $tipo);
while($fila=mysql_fetch_array($resultado)) {

    $res = $items->getCantidadCodigosEstadistica($fila['codigo'], $desde, $hasta, $codos, $tipo);

    $cant = 0;   
    
    if ($res != null) {
        while($f = mysql_fetch_array($res)) {
            $cant = $f['cantidad'];
        }
    }

    if ($cant > 0) {
        $nbu->getObject($fila['codigo']);
        $periodo = substr($desde, 3, 2) . '/' . substr($desde, 6, 4);
        
        $unidad = $nbu->getUnidad();

        if ($ss == 0) {
            $aran = $arancel->getArancelNBU($codoseq, $periodo);
            $ss = 1;
        }
        echo '<tr>';
        echo '<td width="50px">' . $fila['codigo'] . '</td>';
        echo '<td width="200px">' . $nbu->getDescrip() . '</td>';
        echo '<td width="35px" align="right">' . $cant . '</td>';
        echo '<td width="35px" align="right">' . $unidad . '</td>';
        echo '<td width="35px" align="right">' . $aran . '</td>';
        echo '<td width="55px" align="right">' . $unidad * $aran . '</td>';
        echo '<td width="50px" align="right">' . $cant * $unidad . '</td>';
        echo '<td width="60px" align="right">' . $cant * ($unidad * $aran) . '</td>';
        echo '</tr>';

        $total1 = $total1 + $cant;
        $total2 = $total2 + $unidad;
        $total3 = $total3 + ($unidad * $aran);
        $total4 = $total4 + ($cant * $unidad);
        $total5 = $total5 + ($cant * ($unidad * $aran));
    }
}
echo '</table>';

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td><hr></td>';
echo '</tr>';
echo '</table>';

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px" align="right">Total OS.:</td>';
echo '<td width="40px" align="right">' . $total1 . '</td>';
echo '<td width="30px" align="right">' . $total2 . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . $total3 . '</td>';
echo '<td width="50px" align="right">' . $total4 . '</td>';
echo '<td width="60px" align="right">' . $total5 . '</td>';
echo '</tr>';

$cantidad_ordenes = $items->getCantidadOrdenesEstadistica($desde, $hasta, $codos, $tipo);

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px" align="right">Cant. Ordenes:</td>';
echo '<td width="40px" align="right">' . $cantidad_ordenes . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="60px" align="right">' . '</td>';
echo '</tr>';

$cantidad_pacientes = $items->getCantidadPacientesEstadisticas($desde, $hasta, $codos, $tipo);

echo '<table width = "550px" align="center">';
echo '<tr>';
echo '<td width="50px"></td>';
echo '<td width="200px" align="right">Cant. Pacientes:</td>';
echo '<td width="40px" align="right">' . $cantidad_pacientes . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="30px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="50px" align="right">' . '</td>';
echo '<td width="60px" align="right">' . '</td>';
echo '</tr>';

echo '<table width = "550px" align="center">';
echo '<tr><td width="250px" height="200px"></td><td width="250px"></td></tr>';

echo '<tr>';
echo '<td width="250px">---------------------------------------</td>';
echo '<td width="250px">---------------------------------------</td>';
echo '</tr>';
echo '<tr>';
echo '<td width="250px">       Firma y Sello</td>';
echo '<td width="250px">       Firma y Sello</td>';
echo '</tr>';
echo '<tr>';
echo '<td width="250px">Representante Legal</td>';
echo '<td width="250px">Representante Legal</td>';
echo '</tr>';

echo '<tr><td width="250px"></td><td width="250px"></td></tr>';
echo '<tr><td width="250px"></td><td width="250px"></td></tr>';
echo '<tr><td width="250px"></td><td width="250px"></td></tr>';

echo '</table>';

?>

Observaciones:............................................................................................................................................
<br>
...................................................................................................................................................................

