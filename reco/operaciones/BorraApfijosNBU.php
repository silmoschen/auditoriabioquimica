<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$id          = $_REQUEST['id'];
$codanalisis = $_REQUEST['codanalisis'];
$monto       = $_REQUEST['monto'];
$periodo     = $_REQUEST['periodo'];
$baja        = "ProcederBajaApfijosNBU($id)";

echo "<FIELDSET>";
echo "<LEGEND>Bajas</LEGEND>";

echo '<table><tr>';
echo '<td width="320px">';
echo "Seguro para Borrar Monto Fijo $codanalisis en Periodo $periodo ? ";
echo '</td>';

echo '<td><input type="button" name="Si" value="Si" class="button gray small" onClick="'.$baja.'; return false" /></td>';
echo '<td><input type="button" name="No" value="No" class="button gray small" onClick="RecargarArancelNBU(); return false" /></td>';
echo '</tr></table>';

echo "</FIELDSET>";
?>
