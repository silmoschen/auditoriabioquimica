<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codos   = $_REQUEST['codos'];
$periodo = $_REQUEST['periodo'];
$baja    = "ProcederBaja('$codos', '$periodo')";

echo "<FIELDSET>";
echo "<LEGEND>Bajas</LEGEND>";

echo '<table><tr>';
echo '<td width="320px">';

echo "Seguro para Borrar Cápita Obra Social $codos en Período $periodo ? ";

echo "</td>";

echo '<td><input type="button" name="Si" class="button gray small" value="Si" onClick="'.$baja.'; return false" /></td>';
echo '<td><input type="button" name="No" class="button gray small" value="No" onClick="RecargarCapita(); return false" /></td>';

echo "</tr></table>";

echo "</FIELDSET>";

?>
