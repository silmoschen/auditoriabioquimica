<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");

$obj = new cNbu;

$codigo = $_REQUEST['codigo'];

$obj->getObject($codigo);

$nbuds = $obj->getCodigo() . '-' . $obj->getDescrip();
?>

<form name="frmArancelNBU" method="post" onsubmit="return false">
    <?
    echo '<input name="codigo" id="codigo" type="hidden" value="' . $codigo . '"/>';
    ?>
    <FIELDSET>
        <LEGEND>Altas de Aranceles NBU</LEGEND>
        <table width="500px" border="0px">
             <tr>
                <td align="right">Práctica:</td>
                <td align = "left">
                    <b>
                    <?
                    echo $nbuds;
                    ?>
                        </b>
                </td>
            </tr>

            <tr>
                <td align="right">Periodo:</td>
                <td align = "left"><input name="periodo" id="periodo" style="font-size: 10px;" type="text" maxlength="7" width="40" size="8" onkeypress="javascript: if (ValidarPeriodo(event, periodo.value)) {
                            unidad.focus()
                        }
                        ;
                        return true" />
                    (mm/aaaa)
                </td>
            </tr>
            <tr>
                <td align="right">Unidades:</td>
                <td align = "left"><input name="unidad" id="unidad" style="font-size: 10px;" type="text" maxlength="10" width="40" onkeypress="javascript: if (ValidarUnidad(event, unidad.value)) {
                            registrar.focus()}; return true" /></td>
            </tr>
           
        </table>
        <table>
            <tr>
                <td>
                    <input type="button" name="registrar" id="regisrar" class="button gray small" value="Registrar" onClick="registrarUnidadNBU(codigo.value, periodo.value, unidad.value);
                            return false" />
                    <input type="button" name="Cerrar" id="cerrar" class="button gray small" value="Cerrar" onclick="CerrarUnidades();
                            return false" />
                </td>
            </tr>
        </table>
    </FIELDSET>

</form>