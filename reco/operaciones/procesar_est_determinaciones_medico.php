<?
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time",1000);

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");

$codos  = $_REQUEST['codos'];
$desde  = $_REQUEST['desde'];
$hasta  = $_REQUEST['hasta'];
$codigo = $_REQUEST['codigo'];

$auditoria = new cAuditoria();
$items = new cItemsAuditoria();
$medico = new cMedicos();
$entidad = new cEntidad();

$entidad->getObject();

echo '<table width = "500px" align="center">';
echo '<tr>';
echo '<td>Prestador: <b>' . $entidad->getNombre() . '</b></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Período: <b>' . $desde . ' - ' . $hasta . '</b></td>';
echo '</tr>';
echo '<tr>';
echo '<td><b>Determinaciones Solicitadas por Médicos</b></td>' ;
echo '</tr>';
echo '<tr>';
echo '<td><hr></td>';
echo '</tr>';

echo '</table>';

echo '<table width = "500px" align="center">';
echo '<tr>';
echo '<td width="270px">Médico</td>';
echo '<td width="80px" align="right">C.Ordenes</td>';
echo '<td width="80px" align="right">C.Determ.</td>';
echo '<td width="80px" align="right">Promedio</td>';
echo '</tr>';
echo '</table>';

echo '<table width = "500px" align="center">';
echo '<tr>';
echo '<td><hr></td>';
echo '</tr>';
echo '</table>';

echo '<table width = "500px" align="center">';

$total1 = 0; $total2 = 0;

$resultado = $auditoria->getMedicosConOrdenes($codos, $desde, $hasta);
while($fila=mysql_fetch_array($resultado)) {

    $medico->getObject($codos, $fila['idprof']);
    $cant1 = $auditoria->getCantidadOrdenesMedico($codos, $desde, $hasta, $fila['idprof'], $codigo);
    $cant2 = $auditoria->getCantidadDeterminacionesMedico($codos, $desde, $hasta, $fila['idprof'], $codigo);

    if ($cant1 > 0 and $cant2 > 0) {
        $promedio = number_format($cant2 / $cant1, 2,'.',',');
    } else {
        $promedio = 0;
    }

    if ($cant1 > 0 and $cant2 >= 0) {
        echo '<tr>';
        echo '<td width="270px">' . $medico->nombre . '</td>';
        echo '<td width="80px" align="right">' . $cant1 . '</td>';
        echo '<td width="80px" align="right">' . $cant2 . '</td>';
        echo '<td width="80px" align="right">' . $promedio . '</td>';
        echo '</tr>';
    }

    $total1 = $total1 + $cant1;
    $total2 = $total2 + $cant2;
}
echo '</table>';

echo '<table width = "500px" align="center">';
echo '<tr>';
echo '<td><hr></td>';
echo '</tr>';
echo '</table>';

echo '<table width = "500px" align="center">';
echo '<tr>';
echo '<td width="270px">Total OS.:</td>';
echo '<td width="80px" align="right">' . $total1 . '</td>';
echo '<td width="80px" align="right">' . $total2 . '</td>';
echo '<td width="80px" align="right">' . '</td>';
echo '</tr>';

echo '<table width = "500px" align="center">';
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

echo '</table>'

?>

Observaciones:............................................................................................................................................
<br>
...................................................................................................................................................................


