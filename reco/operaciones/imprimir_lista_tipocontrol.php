<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 sleep(2);
  
 echo "<FIELDSET>";
 echo "<LEGEND>Opciones de Impresión</LEGEND>";
 echo '<table align = "center" width="500px"><tr>';
 echo '<td width="250px" align="right"><input type="button" class="button gray small" name="btnImpirmir" id="btnImprimir" value="Generar una Copia Impresa" onclick="ImprimirLista()"></td>';
 echo '<td width="250px" align="left"><input type="button" class="button gray small" name="btnCancelarImpresion" id="btnCancelarImpresion" value="Cancelar Impresión" onclick="CancelarImpresion()"></td>';
 echo '</tr>';
 echo '</table>';
 echo "</FIELDSET>";

?>
