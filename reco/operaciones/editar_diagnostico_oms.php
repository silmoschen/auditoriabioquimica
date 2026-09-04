<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cDiagnosticosOMS.php");
//variables POST
if (isset($_GET['oms_cod'])) {
    $oms_cod = "'" . $_REQUEST['oms_cod'] . "'";
} else {
    $oms_cod = "";
}

$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $moms_cod = '';
    $mclave = '';
    $morden = '';
    $mindice = '';
    $mcodrap = '';
    $mdescrip = '';
    $moms = '';
    $estado = '';
}
if ($modo == 2) {
    $obj = new cDiagnosticosOMS();
    $obj->getObject($_REQUEST['oms_cod']);
    $moms_cod = $obj->getoms_cod();
    $mclave = $obj->getClave();
    $morden = $obj->getOrden();
    $mindice = $obj->getIndice();
    $mcodrap = $obj->getCodrap();
    $mdescrip = $obj->getDescrip();
    $moms = $obj->getOms();
    $estado = 'readonly="true"';
}
?>

<form name="frmeditar_diagnosticos_oms" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Diagnosticos OMS</LEGEND>
        <table border="0px" width="255">

            <tr>
                <td width="73px" algin="right"><div align="right">Codigo:</div></td>
                <td width="49px" align="left"><?
echo '<input name="oms_cod" style="font-size: 10px;" type="text"' . $estado . '"size="6" maxlength="6" size="5" value = ' . '"' . $moms_cod . '"' . ' onkeypress="javascript: if(ValidarOms_Cod(event, oms_cod.value)) {clave.focus()}; return true" />';
?>
                <td align="left"><div align="right">Clave:</div></td>
                <td width="73px" align="left"><?
                    echo '<input name="clave" style="font-size: 10px;" type="text" size="3" maxlength="6" value = ' . '"' . $mclave . '"' . ' onkeypress="javascript: if(ValidarClave(event, clave.value)) {orden.focus()}; return true" />';
?>
                    <div align="left"></div>
            </tr>

            <tr>
                <td height="21" algin="right"><div align="right">Orden: </div></td>
                <td align="left"><div align="left">
                        <?
                        echo '<input name="orden" style="font-size: 10px;" type="text" size="3" maxlength="6" value = ' . '"' . $morden . '"' . ' onkeypress="javascript: if(ValidarOrden(event, orden.value)) {indice.focus()}; return true" />';
                        ?>
                    </div>
                <td align="left"><div align="right">Indice:</div>
                <td align="left"><div align="left">
                        <?
                        echo '<input name="indice" style="font-size: 10px;" type="text" size="3" maxlength="6" value = ' . '"' . $mindice . '"' . ' onkeypress="javascript: if(ValidarIndice(event, indice.value)) {codrap.focus()}; return true" />';
                        ?>
                    </div>
            </tr>

            <tr>
                <td width="73px" algin="right"><div align="right">Cod.Rap.: </div></td>
                <td colspan="3" align="left"><?
                        echo '<input name="codrap" style="font-size: 10px;" type="text" size="5" maxlength="6" value = ' . '"' . $mcodrap . '"' . ' onkeypress="javascript: if(ValidarCodRap(event, codrap.value)) {descrip.focus()}; return true" />';
                        ?>  </tr>
                <tr>
                    <td width="73px" algin="right"><div align="right">Descrip.:</div></td>
                    <td colspan="3" align="left"><?
                        echo '<input name="descrip" style="font-size: 10px;" type="text" size="72px" maxlength="150" value = ' . '"' . $mdescrip . '"' . ' onkeypress="javascript: if(ValidarDescrip(event, descrip.value)) {oms.focus()}; return true" />';
                        ?>  </tr>
                <tr>
                    <td width="73px" algin="right"><div align="right">OMS(S/N):</div></td>
                    <td colspan="3" align="left"><?
                        echo '<input name="oms" style="font-size: 10px;" type="text" size="1" maxlength="1" value = ' . '"' . $moms . '"' . ' onkeypress="javascript: if(ValidarOms(event, oms.value)) {registrar.focus()}; return true" />';
                        ?>  </tr>
                <tr>
                    <td width="73px" algin="right"><?
                        if ($modo == 1) {
                            echo'<td><input type="button" class="button gray small" name="registrar" value="Registrar" onClick="Registrar(oms_cod.value, clave.value, orden.value, indice.value, codrap.value, descrip.value, oms.value, 1); return false" /></td>';
                        }
                        if ($modo == 2) {
                            echo'<td><input type="button" class="button gray small" name="registrar" value="Registrar" onClick="Registrar(oms_cod.value, clave.value, orden.value, indice.value, codrap.value, descrip.value, oms.value, 2); return false" /></td>';
                        }
                        ?>
                <td width="270px" align="left"><input type="button" class="button gray small" name="Cerrar" value="Cerrar" onclick="RecargarDiagnosticoOMS(); return false" /></td>
                <td></td>
                <td></td>
            </tr>
        </table>

    </FIELDSET>

</form>
