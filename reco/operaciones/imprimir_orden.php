<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 sleep(2);
  
 echo "<FIELDSET>";
 echo "<LEGEND>Imprimir Orden</LEGEND>";
 echo '<table align = "center" width="500px"><tr>';
 echo '<td td width="250px" align="right"><input type="button" class="button gray small" name="btnImpirmir" id="btnImprimir" value="Imprimir Orden [ENTER]" onclick="ImprimirOrden()"></td>';
 echo '<td td width="250px" align="left"><input type="button" class="button gray small" name="btnCancelarImpresion" value="Finalizar Operación" onclick="CancelarImpresion()"></td>';
 echo '</tr>';
 echo '</table>';
 echo "</FIELDSET>";

?>
