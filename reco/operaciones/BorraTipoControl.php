<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$id = $_REQUEST['idcontrol'];
$baja = "ProcederBaja(".$id.")";

echo "<FIELDSET>";
echo "<LEGEND>Bajas</LEGEND>";

echo "Seguro para Borrar Tipo Control $id  ?  ";

echo '<input type="button" class="button gray small" name="Si" value="Si" onClick="'.$baja.'; return false" />';
echo '<input type="button" class="button gray small" name="No" value="No" onClick="CancelarBaja(); return false" />';
echo "</FIELDSET>";
?>
