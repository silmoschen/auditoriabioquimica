<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTramos.php");
//variables POST

$id = "";
$codos = "";
if (isset($_GET['codos'])) {
    $codos = $_REQUEST['codos'];
}
if (isset($_GET['id'])) {
    $id = $_REQUEST['id'];
}
$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $mcodigo = '';
    $mdescrip = '';
    $mtope = '';
    $mcantbonos = '';
    $estado = '';
}
if ($modo == 2) {
    $obj = new cTramos();
    $obj->getObject($codos, $id);
    $mcodigo = $obj->getId();
    $mdescrip = $obj->getDescrip();
    $mtope = $obj->getTope();
    $mcantbonos = $obj->getCantbonos();
    $estado = 'readonly="true"';
}
?>

<form name="frmeditar_tramos" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Practicas NBU</LEGEND>
        <table width="350" border="0px"  align="left">
            <tr>
                <td height="21"><div align="right">Id.:</div></td>
                <td width="58" align="left">
                    <?
                    echo '<input name="codigo" size="6" style="font-size: 10px;" type="text"' . $estado . '" maxlength="6" value = ' . '"' . $mcodigo . '"' . '  onkeypress="javascript: if(ValidarId(event, codigo.value)) {descrip.focus()}; return true" />';
                    ?></td>
                <td></td>
                <td width="45"></td>
            </tr>

            <tr>
                <td height="21"><div align="right">Descripción:</div></td>
                <td colspan="3" align = "left"><?
                    echo '<input name="descrip" style="font-size: 10px;" type="text" size="62" maxlength="100" value = ' . '"' . $mdescrip . '"' . '  onkeypress="javascript: if(ValidarDescrip(event, descrip.value)) {tope.focus()}; return true" />';
                    ?></td>
            </tr>

            <tr>
                <td height="21" width="75" align="right"><div align="right">Tope de Prácticas:</div>
                </td>
                <td align = "left"><?
                    echo '<input name="tope" style="font-size: 10px;" type="text" size="10" maxlength="10" value = ' . '"' . $mtope . '"' . '   onkeypress="javascript: if(ValidarTope(event, tope.value)) {cantbonos.focus()}; return true" />';
                    ?></td>

                <td height="21" width="154"><div align="right">Cant. de Bonos:</div></td>
                <td align = "left"><?
                    echo '<input name="cantbonos" style="font-size: 10px;" type="text" size="10" maxlength="10" value = ' . '"' . $mcantbonos . '"' . '   onkeypress="javascript: if(ValidarTramo(event, cantbonos.value)) {registrar.focus()}; return true" />';
                    ?></td>
            </tr>
            <tr>
                <td><div align="right">
                        <?
                        if ($modo == 1) {
                            echo'<td><input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, descrip.value, tope.value, cantbonos.value, 1); return false" /></td>';
                        }
                        if ($modo == 2) {
                            echo'<td><input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, descrip.value, tope.value, cantbonos.value, 2); return false" /></td>';
                        }
                        ?>
                    </div>
                <td align = "left"><input type="button" class="button gray small" name="Cerrar" value="Cerrar" onclick="RecargarTramo(); return false" /></td>
            </tr>
        </table>
    </FIELDSET>

</form>