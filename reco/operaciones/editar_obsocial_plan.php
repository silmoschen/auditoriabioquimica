<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObsocialPlanes.php");
//variables POST

$id = "0";
if (isset($_GET['id'])) {
    $id = $_REQUEST['id'];
}
if (isset($_GET['codos'])) {
    $codos = $_REQUEST['codos'];
}
$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $mdescrip = '';
    $estado = '';
}
if ($modo == 2) {    
    $obj = new cObsocialPlanes;
    $obj->getObject($id);
    $mdescrip = $obj->descrip;
    $mcodigo1 = $obj->codigo1;
    $mcodigo2 = $obj->codigo2;
    $estado = 'readonly="true"';
}
?>

<form name="frmeditar_tramos" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Planes Obra Social</LEGEND>
        <table width="350" border="0px"  align="left">
            <tr>
                <td height="21"><div align="right">Descripción:</div></td>
                <td colspan="3" align = "left"><?
                    echo '<input name="descrip" style="font-size: 10px;" type="text" size="62" maxlength="100" value = ' . '"' . $mdescrip . '"' . '  onkeypress="javascript: if(ValidarDescrip(event, descrip.value)) {codigo1.focus()}; return true" />';
                    ?></td>
            </tr>
            <tr>
                <td height="21"><div align="right">Cód. Export.:</div></td>
                <td colspan="3" align = "left"><?
                    echo '<input name="codigo1" style="font-size: 10px;" type="text" size="62" maxlength="100" value = ' . '"' . $mcodigo1 . '"' . '  onkeypress="javascript: if(ValidarDescrip(event, descrip.value)) {codigo2.focus()}; return true" />';
                    ?></td>
            </tr>
            <tr>
                <td height="21"><div align="right">Cód. Import.:</div></td>
                <td colspan="3" align = "left"><?
                    echo '<input name="codigo2" style="font-size: 10px;" type="text" size="62" maxlength="100" value = ' . '"' . $mcodigo2 . '"' . '  onkeypress="javascript: if(ValidarDescrip(event, descrip.value)) {registrar.focus()}; return true" />';
                    ?></td>
            </tr>
            <td><div align="right">
                    <?
                    if ($modo == 1) {
                        echo'<td><input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(' .$id . ', descrip.value, codigo1.value, codigo2.value, 1); return false" /></td>';
                    }
                    if ($modo == 2) {
                        echo'<td><input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(' .$id . ', descrip.value, codigo1.value, codigo2.value, 2); return false" /></td>';
                    }
                    ?>
                </div>
            <td align = "left"><input type="button" class="button gray small" name="Cerrar" value="Cerrar" onclick="RecargarTramo();
                    return false" /></td>
            </tr>
        </table>
    </FIELDSET>
</form>