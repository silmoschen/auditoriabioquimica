<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");

$obj=new cEntidad();
$obj->getObject();
?>

<table width="500px" border="0" align="center">
<td width="200">Ingrese los Códigos de Practicas</td>
<td width="200">
<a href="javascript://" onclick="CancelarIngresoDeterminaciones()">[ Modificar Datos Anteriores ]</a>
</td>
<td width="100">
[ Con * Finaliza ]
</td>
</table>
<HR>
<table border="0px">
<tr>
<td WIDTH="48" algin="right"><p>Código:</td>
<td width="0"><label>
<?
echo '<td width="16" align="left"><input name="codigo" id="codigo" style="font-size: 10px;" type="text" maxlength="6" width="4" size="6" onkeypress="javascript: if(ValidarCodigo(event, codigo.value, ' . $obj->getParametro4() . ')) {codigo.focus()}; return true">';
?>
</label>
<td width="35">
<?
echo '<input type="button" class="button gray small" name="BuscarNBU" value="?" onClick="BuscarCodigoNBU(); return false" />';
?></td>
<td width="0">
<div id="DescripNBU">
</div>
</td>
</tr>
</table>

<table border="0px">
<div id="ItemsNBU">
<tr><td width="100%">
<textarea name="salida" cols=90 rows=10 readonly="true" style="border: none; font-size:11px"></textarea>
</td></tr>
</div>
</table>

<table width="367" border="0px">
<tr><td width="116">  
<?
echo '<input type="button" name="Finalizar" class="button gray small" value="Finalizar Ingreso Prácticas" onClick="FinalizarCodigos(salida.value); return false" />';
?>
</td>
<td width="104">
<?
echo '<input type="button" name="BorraIt" class="button gray small" value="Borrar Items" onClick="DarDeBajaCodigo(); return false" />';
?>
</td>

<td width="300">
  <div align="right" id="BajaCodigo">
  </div></td>
</tr>
</table>

<HR>

<?
if (strlen($obj->getParametro4()) > 0) {
    echo 'Cód. ' . $obj->getParametro4() . ' se registra automáticamente.';
}
?>