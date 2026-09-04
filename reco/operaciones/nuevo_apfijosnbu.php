<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cApfijosNBU.php");

$obj = new cObsocial;

$codos = $_REQUEST['codos'];
$id    = $_REQUEST['ID'];

$objNBU = new cNBU;

$afijos = new cApfijosNBU;

$codigo1 = ''; $monto = 0; $periodo = ''; $perhasta = ''; $perbaja = '';
if ($id > 0) {
    $afijos->getObject($id);
    $codigo1 = $afijos->getCodanalisis();
    $monto = $afijos->getImporte();
    $periodo = $afijos->getPeriodo();
    if (strlen($afijos->getPerhasta()) == 7) { $perhasta = $afijos->getPerhasta(); }
    if (strlen($afijos->getPerbaja()) == 7) { $perbaja = $afijos->getPerbaja(); }
    $codos   = $afijos->getCodos();
}

$obj->getObject($codos);

$obsocial = $obj->getCodigo() . '-' . $obj->getNombre();
$codos = $obj->getCodigo();
$descrip = $obj->getNombre();

?>

<form name="frmnuevoapfijos_nbu" method="post" onsubmit="return false">
<FIELDSET>
<LEGEND>Altas de Montos Fijos NBU</LEGEND>
<div align="left"></div>
<table width="450" border="0px" align="center">
  <tr>
    <td colspan="2" align="right">O.Social:</td>
    <td colspan="2" align = "left">
    <?php
    echo '<input name="codos" id="codos" style="font-size: 10px;" type="text=" value="' . $codos .'" width="5" size="6" readonly="true" />';
    echo $descrip;
  ?>
 </td>
  </tr>
  <tr>
    <td colspan="2" align="right"><div align="right">Código: </div></td>
    <td width="42" align = "left">
      <?
      echo '<input name="codigo1" id="codigo1" type="text" style="font-size: 10px;" maxlength="6" width="2" size="6" value=' . '"' . $codigo1 . '"' . ' onkeypress="javascript: if(ValidarCodNBU(event, codigo1.value)) {periodo.focus();}; return true" />';
      ?>
    </td>
    <td width="478" align = "left"><div id="determinacion"></div></td>
  </tr>
  <tr>
    <td colspan="2" align="right"><div align="right">Periodo:</div></td>
    <td colspan="2" align = "left">
    <?
    echo '<input name="periodo" id="periodo" value=' . '"' . $periodo . '"' . ' type="text" style="font-size: 10px;" maxlength="7" width="40" size="8" onkeypress="javascript: if(ValidarPeriodo(event, periodo.value)) {monto.focus()}; return true" />';
    ?>
    (mm/aaaa)
    </td>
  </tr>
  <tr>
    <td colspan="2" align="right">Monto:</td>
    <td colspan="2" align = "left">
    <?
    echo '<input name="monto" id="monto" value=' . '"' . $monto . '"' . ' type="text" style="font-size: 10px;" maxlength="10" width="40"  onkeypress="javascript: if(ValidarMonto(event, monto.value)) {perhasta.focus()}; return true" />';
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="right"><div align="right">Per.Hasta:</div></td>
    <td colspan="2" align = "left">
    <?
    echo '<input name="perhasta" id="perhasta" type="text" value=' . '"' . $perhasta . '"' . ' style="font-size: 10px;" maxlength="7" width="40" size="8"  onkeypress="javascript: if(ValidarPeriodo1(event, perhasta.value)) {perbaja.focus()}; return true" />';
    ?>
    (mm/aaaa)
    </td>
  </tr>
  <tr>
    <td colspan="2" align="right">Per.Baja:</td>
    <td colspan="2" align = "left">
    <?
    echo '<input name="perbaja" id="perbaja" type="text" value= ' . '"' . $perbaja . '"' . ' style="font-size: 10px;" maxlength="7" width="40" size="8" onkeypress="javascript: if(ValidarPeriodo1(event, perbaja.value)) {registrar.focus()}; return true" />';
    ?>
    (mm/aaaa)
    </td>
  </tr>
  <tr>
    <td colspan="2" align="right"><div align="right">
      <input type="button" name="registrar" id="registrar" id="registrar" disabled="false" value="Registrar" class="button gray small" onclick="registrarApfijosNBU(codos.value, codigo1.value, periodo.value, monto.value, perhasta.value, perbaja.value); return false" />
    </div></td>
    <td colspan="2" align = "left"><input type="button" name="Cerrar" id="cerrar" class="button gray small" value="Cerrar" onclick="RecargarArancelNBU(); return false" /></td>
  </tr>
</table>
</FIELDSET>

</form>
