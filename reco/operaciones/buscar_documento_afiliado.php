<table>
<tr>
<td>Buscar Nro. de Documento:</td>
<td><input name="buscarvalor" id="buscarvalor" style="font-size: 10px;"  type="text" maxlength="100" width="250" onkeypress="javascript: if(BuscarValor(event, buscarvalor.value)) {buscarvalor.focus()}; return true"></td>
<td width="5"><input type="button" class="button gray small" name="btnBuscar" id="btnBuscar" value="Buscar" onClick="ProcederBuscar(buscarvalor.value); return false" /></td>
<td width="5"><input name="btnCancelar" class="button gray small" type="button" value="Cancelar" onClick="javascript: if(CancelarBusqueda()); return true"></td>
</tr>
</table>