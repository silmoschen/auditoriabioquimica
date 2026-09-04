<table width="500px">
<tr>
<?php
  $leyenda = $_REQUEST['leyenda'];
?>
<?
  if(strlen($leyenda) == 0) {
      $l =  "Buscar:";
  } else {
      $l = $leyenda;
  }

  echo "<td width='120px' align='right'>" . $l . "</td>";
?>

<td width='100px' align='left'><input name="buscarvalor" id="buscarvalor" style="font-size: 10px;" type="text" maxlength="100" size="30" onkeypress="javascript: if(BuscarValor1(event)) {buscarvalor.focus()}; return true"></td>
<td width="30px" align="right"><input type="button" name="btnBuscar" value="Buscar" class="button gray small" onClick="ProcederBuscar1(); return false" /></td>
<td width="30px" align="left"><input name="btnCancelar" class="button gray small" type="button" value="Cancelar" onClick="javascript: if(CancelarBusqueda1()); return true"></td>
</tr>
</table>
<hr>