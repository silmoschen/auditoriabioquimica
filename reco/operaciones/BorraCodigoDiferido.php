<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codigo1 = "'". $_REQUEST['codos'] ."'";
$codigo2 = "'". $_REQUEST['codigo'] ."'";
$baja = "ProcederBaja(".$codigo1.",".$codigo2.")";

echo "<FIELDSET>";
echo "<LEGEND>Bajas</LEGEND>";

echo '<table><tr>';
echo '<td width="320px">';

echo "Seguro para Borrar Código $codigo2 ? ";

echo '</td>';

echo '<td><input type="button" name="Si" class="button gray small" value="Si" onClick="'.$baja.'; return false" /></td>';
echo '<td><input type="button" name="No" class="button gray small" value="No" onClick="CancelarBaja(); return false" /></td>';

echo "</tr></table>";

echo "</FIELDSET>";
?>
