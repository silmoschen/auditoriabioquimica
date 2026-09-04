<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$nroauditoria = $_REQUEST['nrotrans'];
$baja = "ProcederAnulacion(". "'" .$nroauditoria . "'" .")";

echo "<FIELDSET>";
echo "<LEGEND>Anulación de Ordenes</LEGEND>";

echo '<table width="500px"><tr>';
echo "<td width='420px'>Seguro para Anular Orden $nroauditoria  ?  </td>";
echo '<td><input type="button" name="Si" value="Si" class="button gray small" onClick="'.$baja.'; return false" /></td>';
echo '<td><input type="button" name="No" value="No" class="button gray small" onClick="CancelarBaja(); return false" /></td>';
echo '</tr></table>';
echo "</FIELDSET>";
?>
