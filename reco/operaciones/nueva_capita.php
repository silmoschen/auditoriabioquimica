<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cCapitas.php");

$obj = new cCapitas;

$codos = $_REQUEST['codos'];
?>

<form name="frmnueva_capita" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Alta de Capitas</LEGEND>
        <table width="400px" border="0px" align="left">
            <tr>
                <td colspan="2" align="right"><div align="right">Período:</div></td>
                <td align = "left" width="309"><input name="periodo" id="periodo" type="text" style="font-size: 10px;" maxlength="7" width="40" size="8" onkeypress="javascript: if(ValidarPeriodo(event, periodo.value)) {capita.focus()}; return true" />
                    (mm/aaaa)
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">Cápita Nivel 1:</td>
                <td align = "left"><input name="capita" id="capita" type="text" style="font-size: 10px;" maxlength="10" width="40"  onkeypress="javascript: if(ValidarCapita(event, capita.value)) {capita2.focus()}; return true" /></td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td colspan="2" align="right">Cápita Nivel 3:</td>
                <td align = "left"><input name="capita2" id="capita2" type="text" style="font-size: 10px;" maxlength="10" width="40"  onkeypress="javascript: if(ValidarCapita2(event, capita2.value)) {capita3.focus()}; return true" /></td>
            </tr>
             <tr>
                <td colspan="2" align="right">Cápita Unidades:</td>
                <td align = "left"><input name="capita3" id="capita3" type="text" style="font-size: 10px;" maxlength="10" width="40"  onkeypress="javascript: if(ValidarCapita2(event, capita3.value)) {registrar.focus()}; return true" /></td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <div align="right">
                        <input type="button" name="registrar" value="Registrar" class="button gray small" onclick="Registrar(periodo.value, capita.value, capita2.value, capita3.value); return false" />
                    </div>
                </td>
                <td align = "left"><input type="button" name="Cerrar" class="button gray small" value="Cerrar" onclick="RecargarCapita(); return false" /></td>
            </tr>
        </table>
    </FIELDSET>

</form>
