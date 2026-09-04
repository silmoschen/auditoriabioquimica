<?php

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];
$porcentaje = $_REQUEST['porcentaje'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

$auditoria = new cAuditoria;
$obrasocial = new cObsocial;
$efector = new cEfector;

echo '<table width="100%">';
echo '<tr>';

echo '<td width="10%">';
echo '<b>C.OS.</b>';
echo '</td>';

echo '<td width="30%">';
echo '<b>Obra Social</b>';
echo '</td>';

echo '<td width="10%">';
echo '<b>C.Pr.</b>';
echo '</td>';

echo '<td width="30%">';
echo '<b>Profesional</b>';
echo '</td>';

echo '<td align="right" width="10%">';
echo '<b>T.Cos.</b>';
echo '</td>';

echo '<td align="right" width="10%">';
echo '<b>Ret.</b>';
echo '</td>';
echo '</tr>';


$total1 = 6;
if ($porcentaje != '') $total1 = $porcentaje;

$res = $auditoria->getObrasSocialesCoseguro($desde, $hasta);

$cos = '';
$dos = '';
$coo = '';

while ($r = mysql_fetch_array($res)) {

    $obrasocial->getObject($r['codos']);
    $efector->getObject($r['efector']);

    if ($obrasocial->getCoseguro() == 'S') {

        if ($r['codos'] != $cos) {
            $coo = $r['codos'];
            $dos = $obrasocial->getNombre();
        } else {
            $coo = '';
            $dos = '';
        }
        $cos = $r['codos'];

        echo '<tr>';

        echo '<td width="10%">';
        echo $coo;
        echo '</td>';

        echo '<td width="30%">';
        echo $dos;
        echo '</td>';

        echo '<td width="10%">';
        echo $r['efector'];
        echo '</td>';

        echo '<td width="30%">';
        echo $efector->getNombre();
        echo '</td>';

        echo '<td width="10%" align="right">';
        echo number_format($r['coseguro'], 2, '.', ',');
        echo '</td>';

        echo '<td width="10%" align="right">';
        echo number_format(($r['coseguro'] * $total1) / 100, 2, '.', ',');
        echo '</td>';
    }
}



/*

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
 * 
 * 
 */


echo '</table>';

echo '<hr/>';

echo '<table width="100%">';
/*

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
 */
echo '</table>';
?>

