<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNbuFederada.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNbu.php");
//variables POST

if (isset($_GET['codigo'])) {
    $id = $_REQUEST['codigo'];
} else {
    $id = "";
}
$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $mcodigo = '';
    $mnivel = '';
    $mtope_anual = "";
    $mdescrip = "";
}
if ($modo == 2) {
    $obj = new cNbuFederada();
    $obj->getObject($id);
    $objnbu = new cNbu();
    $objnbu->getObject($id);
    $mcodigo = $obj->getCodigo();
    $mnivel = $obj->getNivel();
    $mdescrip = $objnbu->getDescrip();
    $mtope_anual = $obj->getTopeAnual();
    $estado = 'readonly="true"';
}
?>

<form name="frmeditar_nbu" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Practicas NBU</LEGEND>
        <table width="500" border="0px"  align="left">
            <tr>
                <td height="21"><div align="right">Código:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="codigo" id="codigo" size="6" style="font-size: 10px;" type="text"' . $estado . '" maxlength="6" value = ' . '"' . $mcodigo . '"' . '  onkeypress="javascript: if(ValidarCodigo(event, codigo.value)) {nivel.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <tr>
                <td height="21"><div align="right" valign="top">Descripción:</div></td>
                <td colspan="3" align = "left">
                    <div id="det">
                        <?
                        echo $mdescrip;
                        ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td height="21" width="75" align="right"><div align="right">Nivel:</div></td>
                <td width="58" align = "left"><?
                        echo '<input name="nivel" id="nivel" style="font-size: 10px;" type="text" size="10" maxlength="10" value = ' . '"' . $mnivel . '"' . '   onkeypress="javascript: if(ValidarNivel(event, nivel.value)) {tope_anual.focus()}; return true" />';
                        ?>
                </td>

                <td height="21" width="63"><div align="right">Tope Anual:</div></td>
                <td align = "left" width="286">
                    <?
                    echo '<input name="tope_anual" id="tope_anual" style="font-size: 10px;" type="text" size="10" maxlength="10" value = ' . '"' . $mtope_anual . '"' . '   onkeypress="javascript: if(ValidarTopeAnual(event, tope_anual.value)) {registrar.focus()}; return true" />';
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <div align="right">
                        <?
                        if ($modo == 1) {
                            echo'<input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, nivel.value, tope_anual.value, 1); return false" />';
                        }
                        if ($modo == 2) {
                            echo'<input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, nivel.value, tope_anual.value, 2); return false" />';
                        }
                        ?>
                    </div>
                </td>
                <td align = "left"><input type="button" name="Cerrar" value="Cerrar" class="button gray small" onclick="RecargarControl(); return false" /></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </FIELDSET>

</form>
