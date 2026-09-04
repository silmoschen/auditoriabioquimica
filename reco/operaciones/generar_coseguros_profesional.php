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
echo '<b>C.Pr.</b>';
echo '</td>';

echo '<td width="30%">';
echo '<b>Profesional</b>';
echo '</td>';

echo '<td width="10%">';
echo '<b>C.OS.</b>';
echo '</td>';

echo '<td width="30%">';
echo '<b>Obra Social</b>';
echo '</td>';

echo '<td align="right" width="10%">';
echo '<b>T.Cos.</b>';
echo '</td>';

echo '<td align="right" width="10%">';
echo '<b>Ret.</b>';
echo '</td>';
echo '</tr>';


$total1 = 6;
if ($porcentaje != '')
    $total1 = $porcentaje;

$res = $auditoria->getProfesionalCoseguro($desde, $hasta);

$cos = '';
$dos = '';
$coo = '';

$t1 = 0;
$t2 = 0;

$ef = '';

while ($r = mysql_fetch_array($res)) {

    $obrasocial->getObject($r['codos']);

    if ($obrasocial->getCoseguro() == 'S') {

        if ($r['efector'] != $ef) {

            // Total Coseguro
            if ($t1 + $t2 > 0) {

                echo '<tr>';

                echo '<td width="10%"><b>';
                echo '<b>Tot.Prof.:<b>';
                echo '</td>';

                echo '<td width="30%"><b>';
                echo '</td>';

                echo '<td width="10%">';
                echo '</td>';

                echo '<td width="30%">';
                echo '</td>';

                echo '<td width="10%" align="right"><b>';
                echo number_format($t1, 2, '.', ',');
                echo '</b></td>';

                echo '<td width="10%" align="right"><b>';
                echo number_format($t2, 2, '.', ',');
                echo '<b/></td>';

                echo '<tr/>';

                echo '<tr><td colspan="6"><hr/></td></tr>';

                $t1 = 0;
                $t2 = 0;
            }


            $ef = $r['efector'];
            $efector->getObject($r['efector']);

            echo '<tr>';

            echo '<td width="10%"><b>';
            echo $efector->getCodigo();
            echo '</td>';

            echo '<td width="30%"><b>';
            echo $efector->getNombre();
            echo '</td>';

            echo '<td width="10%">';
            echo '</td>';

            echo '<td width="30%">';
            echo '</td>';

            echo '<td width="10%" align="right">';
            echo '</td>';

            echo '<td width="10%" align="right">';
            echo '</td>';

            echo '<tr/>';
        }

        echo '<tr>';

        echo '<td width="10%">';
        echo '</td>';

        echo '<td width="30%">';
        echo '</td>';

        echo '<td width="10%">';
        echo $r['codos'];
        echo '</td>';

        echo '<td width="30%">';
        echo $obrasocial->getNombre();
        echo '</td>';

        $coseguro_monto = $r['coseguro'] - $r['monto'];

        echo '<td width="10%" align="right">';
        echo number_format($coseguro_monto, 2, '.', ',');
        echo '</td>';

        echo '<td width="10%" align="right">';
        echo number_format(($coseguro_monto * $total1) / 100, 2, '.', ',');
        echo '</td>';

        $t1 = $t1 + $coseguro_monto;
        $t2 = $t2 + ($coseguro_monto * $total1) / 100;
    }
}


// Total Coseguro
if ($t1 + $t2 > 0) {

    echo '<tr>';

    echo '<td width="10%"><b>';
    echo '<b>Tot.Prof.:<b>';
    echo '</td>';

    echo '<td width="30%"><b>';
    echo '</td>';

    echo '<td width="10%">';
    echo '</td>';

    echo '<td width="30%">';
    echo '</td>';

    echo '<td width="10%" align="right"><b>';
    echo number_format($t1, 2, '.', ',');
    echo '</b></td>';

    echo '<td width="10%" align="right"><b>';
    echo number_format($t2, 2, '.', ',');
    echo '<b/></td>';

    echo '<tr/>';

    echo '<tr><td colspan="6"><hr/></td></tr>';

    $t1 = 0;
    $t2 = 0;
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

