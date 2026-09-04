<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
//variables POST

if (isset($_GET['codigo'])) {
    $id = $_REQUEST['codigo'];
} else {
    $id = "";
}
$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $mcodigo = '';
    $mdescrip = '';
    $munidad = '';
    $mtramo = '';
    $mnivel = '';
    $munidadact = '';
    $estado = "";
    $mmina = 'disabled="true"';
}
if ($modo == 2) {
    $obj = new cNBU();
    $obj->getObject($id);
    $mcodigo = $obj->getCodigo();
    $mdescrip = $obj->getDescrip();
    $munidad = $obj->getUnidadNbu();
    $munidadact = $obj->getUnidad();
    $mtramo = $obj->getTramo();
    $minactivo = $obj->getInactivo();
    $mnivel = $obj->getNivel();
    $estado = 'readonly="true"';
    if ($obj->getInactivo() == 1) {
        $mmina = "checked=true";
    } else {
        $mmina = "";
    }
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
                    echo '<input name="codigo" id="codigo" size="6" style="font-size: 10px;" type="text"' . $estado . '" maxlength="6" value = ' . '"' . $mcodigo . '"' . '  onkeypress="javascript: if(ValidarCodigo(event, codigo.value)) {descrip.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <tr>
                <td height="21"><div align="right">Descripción:</div></td>
                <td colspan="3" align = "left"><?
                    echo '<input name="descrip" id="descrip" style="font-size: 10px;" type="text" size="72px" maxlength="100" value = ' . '"' . $mdescrip . '"' . '  onkeypress="javascript: if(ValidarDescrip(event, descrip.value)) {unidad.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <tr>
                <td height="21" width="75" align="right"><div align="right">Unidades:</div></td>
                <td width="58" align = "left"><?
                    echo '<input name="unidad" id="unidad" style="font-size: 10px;" type="text" size="10" maxlength="10" value = ' . '"' . $munidad . '"' . '   onkeypress="javascript: if(ValidarUnidad(event, unidad.value)) {tramo.focus()}; return true" />';
                    ?>
                </td>

                <td height="21" width="63"><div align="right">Tramo:</div></td>
                <td align = "left" width="286">
                    <?
                    echo '<input name="tramo" id="tramo" style="font-size: 10px;" type="text" size="10" maxlength="10" value = ' . '"' . $mtramo . '"' . '   onkeypress="javascript: if(ValidarTramo(event, tramo.value)) {nivel.focus()}; return true" />';
                    ?>
                </td>
            </tr>


            <tr>
                <td height="21" width="75" align="right"><div align="right">Nivel:</div></td>
                <td width="58" align = "left">
                    <?
                    echo '<input name="nivel" id="nivel" style="font-size: 10px;" type="text" size="2" maxlength="1" value = ' . '"' . $mnivel . '"' . '   onkeypress="javascript: if(ValidarNivel(event, nivel.value)) {registrar.focus()}; return true" />';
                    ?>
                    (1 ó 3)
                </td>

                <td height="21" width="63"><div align="right"></div></td>
                <td align = "left" width="286">
                    <?
                    echo '<input name="inactivo" id="inactivo" type="checkbox" style="font-size: 10px;"' . " " . $mmina . " " . $minactivo . '  />' . "Inactivar";
                    ?>
                </td>
            </tr>

            <tr>
                <td>
                    <div align="right">
                        <?
                        if ($modo == 1) {
                            echo'<input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, descrip.value, unidad.value, tramo.value, inactivo.checked, 1, nivel.value); return false" />';
                        }
                        if ($modo == 2) {
                            echo'<input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, descrip.value, unidad.value, tramo.value, inactivo.checked, 2, nivel.value); return false" />';
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
    
    <?
    if ($munidad != $mnunidadact) {
        echo '<br/><div align="left">';
        echo 'Unidad Actualizada: <b>' . $munidadact .'</b>';
    }
    ?>

</form>
