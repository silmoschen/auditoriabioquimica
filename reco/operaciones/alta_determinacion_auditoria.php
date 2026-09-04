<?
$modo = $_REQUEST['modo'];
?>
<?
//echo '<td width="100" align="left">';
if ($modo == 1) {
  echo 'Cód. Aut.:';
} else {
  echo 'Cód. Rec.:';
}
?>
<?
echo '<input name="codigo" style="font-size: 10px;" type="text" maxlength="6" width="4" size="6" onkeypress="javascript: if(ValidarCodigo(event, codigo.value,' . $modo . ')) {codigo.focus()}; return true">';
echo '<input name="btnCancelaCod" class="button gray small" type="button" value="X" onClick="javascript: if(CerrarCodigo()); return true">';
?>
