<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");

$obj = new cObsocial;

$codos = $_REQUEST['codos'];

$obj->getObject($codos);

$obsocial = $obj->getCodigo() . '-' . $obj->getNombre();
?>

<form name="frmArancelNBU" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Altas de Aranceles NBU</LEGEND>
        <table width="500px" border="0px">

            <tr><td align="right" style="WIDTH: 100px" td><p>O.Social:</td>
                <td align="left">
                <?php
                echo '<input name="codos" style="font-size: 10px;" type="text=" value="' . $obsocial . '" width="100" size="50" readonly="true" />';
                ?>
                </td>
            </tr>

            <tr>
                <td align="right">Periodo:</td>
                <td align = "left"><input name="periodo" id="periodo" style="font-size: 10px;" type="text" maxlength="7" width="40" size="8" onkeypress="javascript: if(ValidarPeriodo(event, periodo.value)) {unidad.focus()}; return true" />
                    (mm/aaaa)
                </td>
            </tr>
            <tr>
                <td align="right">Unidades:</td>
                <td align = "left"><input name="unidad" id="unidad" style="font-size: 10px;" type="text" maxlength="10" width="40" onkeypress="javascript: if(ValidarUnidad(event, unidad.value)) {unidaddif.focus()}; return true" /></td>
            </tr>
            <tr>
                <td align="right">Coseguro:</td>
                <td align = "left"><input name="unidaddif" id="unidaddif" style="font-size: 10px;" type="text" maxlength="10" width="40"   onkeypress="javascript: if(ValidarUnidadDif(event, unidaddif.value)) {modulo.focus()}; return true" /></td>
            </tr>
            <tr>
                <td align="right">Factor:</td>
                <td align = "left"><input name="modulo" id="modulo" style="font-size: 10px;" type="text" maxlength="10" width="40"  onkeypress="javascript: if(ValidarModulo(event, modulo.value)) {nbu_os.focus()}; return true" /></td>
            </tr>
              <tr>
                <td align="right">Arancel:</td>
                <td align = "left"><input name="nbu_os" id="nbu_os" style="font-size: 10px;" type="text" maxlength="10" width="40"  onkeypress="javascript: if(ValidarNbuos(event, nbu_os.value)) {registrar.focus()}; return true" />
                (de referencia de la Obra Social)
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <input type="button" name="registrar" id="regisrar" class="button gray small" value="Registrar" onClick="registrarArancelNBU(codos.value, periodo.value, unidad.value, unidaddif.value, modulo.value, nbu_os.value); return false" />
                    <input type="button" name="Cerrar" id="cerrar" class="button gray small" value="Cerrar" onclick="RecargarArancelNBU(); return false" />
                </td>
            </tr>
        </table>
    </FIELDSET>

</form>