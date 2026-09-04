<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cDiagnosticosOMS.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cModelos.php");

$obj = new cNBU;
$dx = new cDiagnosticosOMS;
$modelo = new cModelos;

$resultado = $obj->getListaNBU();

$modo = $_REQUEST['modo'];

if (isset($_GET['idcontrol'])) {
    $idcontrol = $_REQUEST['idcontrol'];
} else {
    $idcontrol = "";
}
if (isset($_GET['codos'])) {
    $codos = $_REQUEST['codos'];
} else {
    $codos = "";
}
if (isset($_GET['codigo'])) {
    $cod = $_REQUEST['codigo'];
} else {
    $cod = "";
}



$codigo = '';
$frecuencia = '';
$tipofrecuencia = '';
$det = '';
if (strlen($idcontrol) > 0) {
    $modelo->getObject($codos, $idcontrol, $cod);
    $codigo = $modelo->getCodigo();
    $frecuencia = $modelo->getFrecuencia();
    $tipofrecuencia = $modelo->getTipofrecuencia();
    $obj->getObject($codigo);
    $det = $obj->getDescrip();    
}

$dx->getObject($idcontrol);
?>

<div style="margin:auto;width:550px;text-align:center;">

    <form name="frm_modelo_nbu" method="post" onsubmit="return false">

        <FIELDSET>
            <LEGEND>Alta de Practicas en Tipo de Control</LEGEND>

            <table border="0px" width="500px">
                <tr><td width="90"><div align="right">Control Sel.:</div></td>
                    <td colspan="3" align="left">
                        <?php
                        echo '<input name="idcontrol" style="font-size: 10px;" type="text=" value="' . $idcontrol . '" width="5" size="5" readonly="true" />';
                        echo $dx->getDescrip();
                        ?></td>
                    <td></td>
                </tr>

                <tr><td width="90"><div align="right">Practica:</div></td>
                    <td width="25" align="left">
                        <?
                        if (strlen($codigo) == 0) {
                            echo '<input name="codigo" id="codigo" style="font-size: 10px;" type="text" maxlength="6" width="6" size="6" value=' . '"' . $codigo . '"' . ' onkeypress="javascript: if(ValidarCodigo(event, codigo.value)) {frecuencia.focus()}; return true" />';
                        } else {
                            echo '<input name="codigo" id="codigo" style="font-size: 10px;" type="text" maxlength="6" width="6" size="6" value=' . '"' . $codigo . '"' . ' readonly = "true" />';
                        }
                        ?>
                    </td>
                    <td width="21" align="left"><input name="buscarCodigo" style="font-size: 10px;" type="button" id="buscarCodigo" class="button gray small" value="?"  onClick="BuscarCodigoNBU(); return false" /></td>
                    <td width="280" align="left" style="font-size: 10px;">
                        <div id="Determinacion"></div>
                        <? echo $det; ?>
                    </td>

                    <td width="7" align="left">
                <tr>
                    <td align="right">Frecuencia:</td>
                    <td colspan="3" align="left">
                        <?
                        echo '<input name="frecuencia" id="frecuencia" style="font-size: 10px;" type="text" maxlength="3" width="5" size="4" value= ' . '"' . $frecuencia . '"' . ' onkeypress="javascript: if(ValidarFrecuencia(event, frecuencia.value)) {tipofrecuencia.focus()}; return true" />';
                        ?>
                    </td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right">Tipo de Frec.:</td>
                    <td colspan="2" align="left">
                        <?
                        echo '<input name="tipofrecuencia" id="tipofrecuencia" value= ' . '"' . $tipofrecuencia . '"' . ' style="font-size: 10px;" type="text" maxlength="1" width="1" size="1" onkeypress= ' . '"javascript: if(ValidarTipoFrecuencia(event, tipofrecuencia.value)) {btnRegistrar.focus()}; return true" />';
                        ?>
                    </td>
                    <td align="left">1.Dias/2.Meses/3.Anual/4.Sin Limite</td>
                    <td align="left">&nbsp;</td>
                </tr>
                <tr><td align="right" width="90"><p align="right"></td>
                    <td colspan="3" align="left">
                        <?
                        echo '<input type="button" class="button gray small" name="btnRegistrar" value="Registrar" onclick="RegistrarModelo(idcontrol.value, codigo.value, frecuencia.value, tipofrecuencia.value,' . $modo . '); return false" />';
                        ?>
                        <input type="button" class="button gray small" value="Cerrar" onclick="OcultarModelo(); return false" /></td>
                    <td align="left">&nbsp;</td>
                </tr>
            </table>

            <label></label>

        </FIELDSET>

    </form>

</div>