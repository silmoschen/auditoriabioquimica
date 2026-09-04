<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTipoDoc.php");
//variables POST
$codos = $_REQUEST['codos'];
$objos = new cObsocial();
$tipodoc = new cTipoDoc();
$objos->getObject($codos);

$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $mnrodoc = '';
    $mnombre = '';
    $mobservac = '';
    $mfechanac = '';
    $estado = '';
    $mdireccion = '';
    $mid_beneficio = '';
    $mid_parentesco = '';
    $msexo = '';
    $mid_tipo_doc = '';
    $mretiva = "N";
}
if ($modo == 2) {
    $nrodoc = $_REQUEST['nrodoc'];
    $obj = new cAfiliados();
    $obj->getObject($codos, $nrodoc);

    $mnrodoc = $obj->getNrodoc();
    $mnombre = $obj->getNombre();
    $mobservac = $obj->getObservacion();
    $mfechanac = $obj->getFechanac();
    $mdireccion = $obj->getDireccion();
    $mid_beneficio = $obj->getId_beneficio();
    $mid_parentesco = $obj->getId_parentesco();
    $msexo = $obj->getSexo();
    $mid_tipo_doc = $obj->getTipo_doc();
    $mretiva = $obj->getRetiva();
    $estado = 'readonly="true"';

    if ($obj->inactivo == 'S') {
        $inactivo = 'checked';
    } else {
        $inactivo = 'unchecked';
    }
}
?>

<form name="frmeditar_afiliado" method="post" onsubmit="return false">

    <FIELDSET>
        <LEGEND>Datos del Afiliado</LEGEND>
        <table border="0px">
            <tr>
                <td width="100" align="left" style="WIDTH: 100px" td><div align="right">O.Social:</div></td>
                <td colspan="2">  
                    <?
                    echo '<input name="codos" id="codos" type="text" style="font-size: 10px;" readonly="true" size="8" maxlength="6" value = ' . '"' . $objos->getCodigo() . '"' . " />";
                    echo $objos->getNombre();
                    ?></td>  
                <td></td>
            </tr>

            <tr>
                <td width="100" align="left" style="WIDTH: 100px" td><div align="right">Nro.Doc.:</div></td>
                <td width="29" align="left">
                    <?
                    echo '<input name="nrodoc" id="nrodoc" style="font-size: 10px;" type="text"' . $estado . '"size="8" maxlength="12" value = ' . '"' . $mnrodoc . '"' . ' onkeypress="javascript: if(ValidarNrodoc(event, nrodoc.value)) {listTipoDoc.focus()}; return true" />';
                    ?></td>
                <td width="72" align="left"><div align="right"></div></td>
                <td>  </td>
            </tr>

            <tr>
                <td align="right" td style="WIDTH: 100px"><div align="right">Tipo Doc.:</div></td>
                <td align="left">
                    <?
                    $resultado = $tipodoc->getTiposDoc();
                    ?>

                    <select name="listTipoDoc" id="listTipoDoc" style="width:250px" onkeypress="javascript: if (ValidarTipodoc(event)) {
            nombre.focus()
        }
        ;
        return true" />
                            <?php
                            while ($fila = mysql_fetch_array($resultado)) {
                                if ($fila['id_tipo_doc'] == $mid_tipo_doc) {
                                    echo '<option selected value =' . '"' . $fila['id_tipo_doc'] . '"' . '>' . substr($fila['descripcion'], 0, 40) . '</option>';
                                } else {
                                    echo '<option value =' . '"' . $fila['id_tipo_doc'] . '"' . '>' . substr($fila['descripcion'], 0, 40) . '</option>';
                                }
                            }
                            ?>
                    </select>
                </td>

            </tr>
            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Nombre:</div></td>
                <td colspan="2" align="left">
                    <?
                    echo '<input name="nombre" id="nombre" style="font-size: 10px;" type="text" size="55" maxlength="60" value = ' . '"' . $mnombre . '"' . ' onkeypress="javascript: if(ValidarNombre(event, nombre.value)) {direccion.focus()}; return true" />';
                    ?></td>
                <td></td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Dirección:</div></td>
                <td colspan="2" align="left">
                    <?
                    echo '<input name="direccion" id="direccion" style="font-size: 10px;" type="text" size="55" maxlength="55" value = ' . '"' . $mdireccion . '"' . ' onkeypress="javascript: if(ValidarDireccion(event, direccion.value)) {observacion.focus()}; return true" />';
                    ?></td>
                <td></td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Observac.:</div></td>
                <td colspan="2" align="left">
                    <?
                    echo '<input name="observacion" id="observacion" style="font-size: 10px;" type="text" size="55" maxlength="70" value = ' . '"' . $mobservac . '"' . ' onkeypress="javascript: if(ValidarObservacion(event, observacion.value)) {fechanac.focus()}; return true" />';
                    ?></td>
                <td></td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">F.Nac.:</div></td>
                <td colspan="2" align="left">
                    <?
                    echo '<input name="fechanac" id="fechanac" style="font-size: 10px;" type="text" size="8" maxlength="10" value = ' . '"' . $mfechanac . '"' . ' onkeypress="javascript: if(ValidarFechaNac(event, fechanac.value)) {sexo.focus()}; return true" />';
                    ?>
                    (dd/mm/aaaa)</td>
                <td align="right">Sexo(F/M):</td>
                <td width="32" align="left">
                    <?
                    echo '<input name="sexo" id="sexo" style="font-size: 10px;" type="text" size="1" maxlength="1" value = ' . '"' . $msexo . '"' . ' onkeypress="javascript: if(ValidarSexo(event, sexo.value)) {id_beneficio.focus()}; return true" />';
                    ?>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Nro.Beneficio:</div></td>
                <td colspan="2" align="left">
                    <?
                    echo '<input name="id_beneficio" id="id_beneficio" style="font-size: 10px;" type="text" size="20" maxlength="30" value = ' . '"' . $mid_beneficio . '"' . ' onkeypress="javascript: if(ValidarBeneficio(event, id_beneficio.value)) {id_parentesco.focus()}; return true" />';
                    ?></td>
                <td align="right">Parentesco:</td>
                <td width="32" align="left">
                    <?
                    echo '<input name="id_parentesco" id="id_parentesco" style="font-size: 10px;" type="text" size="2" maxlength="2" value = ' . '"' . $mid_parentesco . '"' . ' onkeypress="javascript: if(ValidarParentesco(event, id_parentesco.value)) {registrar.focus()}; return true" />';
                    ?>
            </tr>
            <tr>
                <td align="right">Retiene I.V.A. ?:</td>
                <td align="left">
                    <select name="retiva" id="retiva" 
                            onkeypress="javascript: if (ValidarEnter(event)) {
            RegistrarAfil.focus();
        }">
                                <?
                                if ($mretiva == 'N' || $mretiva == null) {
                                    echo '<OPTION VALUE="N">No</OPTION>';
                                    echo '<OPTION VALUE="S">Sí</OPTION>';
                                } else {
                                    echo '<OPTION VALUE="N">No</OPTION>';
                                    echo '<OPTION SELECTED VALUE="S">Sí</OPTION>';
                                }
                                ?>
                    </select>
                </td>
            </tr>
        </table>
        <table border="0px">
            <tr><td>
                    <label>
                        <?
                        if ($modo == 1) {
                            echo'<td><input type="button" class="button gray small" name="registrar" id="registrar" value="Registrar" onClick="RegistrarAfiliado(codos.value, nrodoc.value, nombre.value, observacion.value, fechanac.value, direccion.value, id_beneficio.value, id_parentesco.value, sexo.value, listTipoDoc.value, false, 1, retiva.value); return false" /></td>';
                        }
                        if ($modo == 2) {
                            echo '<input name="inactivo" id="inactivo" type="checkbox" style="font-size: 10px;"' . $inactivo . ' />' . 'Afiliado Inactivo</td>';
                            echo '<td></td></tr>';
                            echo '<tr>';
                            echo'<td><input type="button" class="button gray small" name="registrar" id="registrar" value="Registrar" onClick="RegistrarAfiliado(codos.value, nrodoc.value, nombre.value, observacion.value, fechanac.value, direccion.value, id_beneficio.value, id_parentesco.value, sexo.value, listTipoDoc.value, inactivo.checked, 2, retiva.value); return false" /></td>';
                        }
                        ?>
                        <td><input type="button" name="Cerrar" value="Cerrar" class="button gray small" onclick="Recargar();
        return false" /></td>
                    </label>
            </tr>
        </table>
    </FIELDSET>

</form>
