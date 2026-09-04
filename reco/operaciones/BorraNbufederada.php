<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$id = "'". $_REQUEST['codigo'] ."'";
$baja = "ProcederBaja(".$id.")";
//$baja = "ProcederBaja(".$id.")";

echo "<FIELDSET>";
echo "<LEGEND>Bajas</LEGEND>";

echo '<table><tr>';
echo '<td width="320px">';

echo "Seguro para Borrar Determinación $id  ?  ";

echo '</td>';

echo '<td><input type="button" name="Si" value="Si" class="button gray small" onClick="'.$baja.'; return false" /></td>';
echo '<td><input type="button" name="No" value="No" class="button gray small" onClick="CancelarBaja(); return false" /></td>';

echo '</tr></table>';

echo "</FIELDSET>";
?>
