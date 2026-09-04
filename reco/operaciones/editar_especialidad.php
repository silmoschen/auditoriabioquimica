<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEspecialidades.php");
//variables POST

if (isset($_GET['id'])) {
    $id = $_REQUEST['id'];
}
$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $mid = '';
    $mdescrip = '';
    $estado = "";
}
if ($modo == 2) {
    $obj = new cEspecialidades();
    $obj->getObject($id);
    $mid = $obj->getId_especialidad();
    $mdescrip = $obj->getDescripcion();
    $estado = 'readonly="true"';
}
?>

<form name="frmeditar_especialidad" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Especialidades</LEGEND>
        <table width="500" border="0px"  align="left">
            <tr>
                <td height="21"><div align="right">Código:</div></td>
                <td colspan="3" align="left"><?
echo '<input name="codigo" id="codigo" size="6" style="font-size: 10px;" type="text"' . $estado . '" maxlength="6" value = ' . '"' . $mid . '"' . '  onkeypress="javascript: if(ValidarCodigo(event, codigo.value)) {descrip.focus()}; return true" />';
?></td>
            </tr>

            <tr>
                <td height="21"><div align="right">Descripción:</div></td>
                <td colspan="3" align = "left"><?
                    echo '<input name="descrip" id="descrip" style="font-size: 10px;" type="text" size="65" maxlength="100" value = ' . '"' . $mdescrip . '"' . '  onkeypress="javascript: if(ValidarDescrip(event, descrip.value)) {registrar.focus()}; return true" />';
?></td>
            </tr>

            <tr>
                <td><div align="right">
                        <?
                        if ($modo == 1) {
                            echo'<input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, descrip.value, 1); return false" />';
                        }
                        if ($modo == 2) {
                            echo'<input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, descrip.value, 2); return false" />';
                        }
                        ?>
                    </div></td>
                <td align = "left"><input type="button" name="Cerrar" value="Cerrar" class="button gray small" onclick="RecargarControl(); return false" /></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </FIELDSET>

</form>