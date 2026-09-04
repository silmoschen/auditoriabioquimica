<?php

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$regla = $_REQUEST['regla'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
$auditoria = new cAuditoria;

echo '<table width="100%">';
echo '<tr>';

echo '<td width="50%">';
echo '<b>Obra Social</b>';
echo '</td>';
echo '<td align="right">';
echo '<b>Monto</b>';
echo '</td>';

echo '<td align="right">';
echo '<b>Coseguro</b>';
echo '</td>';
echo '</tr>';

$total1 = 0;
$total2 = 0;

if (strlen($desde) > 0 && strlen($hasta) > 0 && strlen($regla) > 0) {
    $lista = $auditoria->obrasocial->getObrasSocialesRPC($regla);
    while ($fila = mysql_fetch_array($lista)) {
        $c = $fila['codos'];

        $auditoria->obrasocial->getObject($c);

        $res = $auditoria->getConsumos($desde, $hasta, $c);

        while ($r = mysql_fetch_array($res)) {
            echo '<tr>';

            echo '<td width="50%">';
            echo $auditoria->obrasocial->getNombre();
            echo '</td>';
            echo '<td align="right">';
            echo number_format($r['monto'], 2, '.', ',');
            echo '</td>';

            echo '<td align="right">';
            echo number_format($r['coseguro'], 2, '.', ',');
            echo '</td>';

            $total1 = $total1 + $r['monto'];
            $total2 = $total2 + $r['coseguro'];

            echo '</tr>';
        }
    }
}
echo '</table>';

echo '<hr/>';

echo '<table width="100%">';
echo '<tr>';

echo '<td width="70%">';
echo '<h3>Total Obras Sociales:</h3>';
echo '</td>';
echo '<td align="right">';
echo '<h3>' . number_format($total1, 2, '.', ',') . '</h3>';
echo '</td>';
echo '</tr>';

echo '<tr>';

echo '<td witdh="70%">';
echo '<h3>Total Coseguros:</h3>';
echo '</td>';
echo '<td align="right">';
echo '<h3>' . number_format($total2, 2, '.', ',') . '</h3>';
echo '</td>';

echo '</tr>';

echo '</table>';
?>

