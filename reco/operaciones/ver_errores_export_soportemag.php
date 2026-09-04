<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cExportOrdenes.php');

$exportar=new cExportOrdenes();

echo "<hr>";

echo "<table border='0px'>";

echo '<td width="50px">Modulo</td>';
echo '<td width="450px">Tipo de Advertencia</td>';

$errores = false;

$items = $exportar->getListaErrores();
while($lista=mysql_fetch_array($items)){
  echo '<tr>';
  echo '<td width="50px">' . $lista['modulo'] . '</td>';  
  echo '<td width="450px">' . $lista['error'] . '</td>';
  echo '</tr>';
  $errores = true;
}

if ($errores) {
  echo '<tr>';
  echo '<td width="50px"><i>Observación:</i></td>';
  echo '<td width="450px"><i>Algunas Ordenes No se pudieron Exportar</i></td>';
  echo '</tr>';
}

echo '</table>';

?>
