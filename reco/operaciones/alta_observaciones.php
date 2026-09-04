<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
$obsocial = new cObsocial();

$codigos = "'" . $_REQUEST['codigos'] . "'";
$codos = $_REQUEST['codos'];

$obsocial->getObject($codos);
?>

<?
if ($obsocial->getMedicos_cab() != '1') {
    echo '<table width="550px" border="0" align="center">';
    echo '<tr>';
    echo '<td width="500px" align="left">Observaciones:&nbsp;&nbsp;<input name="observacion" id="observacion" style="font-size: 10px;" type="text" maxlength="256" width="200" size="80" onkeypress="javascript: if(FinalizarObservacion(event,' . $codigos . ')) {}; return true">';
    echo '</tr>';
    echo '<tr>';
    echo '<td width="500px" align="center">';
    echo '*** Si tiene alguna Observación para Realizar en esta Orden, utilice este espacio ***';
    echo '</td></tr>';
    echo '<tr>';
    echo '<td width="550px" align="center">';
    echo '[ para Finalizar pulse ENTER ] ó haga Click en las Siguientes Opciones';
    echo '</td></tr>';
    echo '</table>';
}
if ($obsocial->getMedicos_cab() == '1') {
    echo '<table width="550px" border="0" align="center">';
    echo '<tr>';
    echo '<td width="550px" align="left">Observaciones:&nbsp;&nbsp;<input name="observacion" id="observacion" style="font-size: 10px;" type="text" maxlength="256" width="200" size="80" onkeypress="javascript: if(FinalizarObservacionMC(event,' . $codigos . ')) {}; return true">';
    echo '</tr>';
    echo '<tr>';
    echo '<td width="550px" align="center">';
    echo '*** Si tiene alguna Observación para Realizar en esta Orden, utilice este espacio ***';
    echo '</td></tr>';
    echo '</table>';
}

if ($obsocial->getMedicos_cab() == '1') {
    echo '<table width="550px" border="0">';
    echo '<tr>';
    echo '<td width="50px" align="right">Méd.Cab.:</td>';
    echo '<td width="450px" align="left"><input name="medicocabecera" id="medicocabecera" type="text" style="font-size: 10px;" maxlength="40" width="15" size="80" disabled="true" onkeypress="javascript: if(ValidarNombreMedicoCabecera(event, medicocabecera.value)) {listMedicosCabecera.focus()}; return true" /></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td width="50px" align="right">Sel.Med.:</td>';
?>
    <td width="450px" align="left">
        <div id="ListadoMedicosCabecera">
        <?php include("lista_medicos_ingordenes2.php") ?>
    </div>
</td>
<?
        echo '</tr>';
        echo '</table>';
    }

    echo '<table width="550px" border="0">';
    echo '<tr>';
    echo '<td><hr></td>';
    echo '</tr>';
    echo '</table>';

    echo '<table width="550px">';

    if ($obsocial->getMedicos_cab() != '1') {
        echo '<td align="right"><input type="button" name="RegistrarOrdenAuditoria" id="RegistrarOrdenAuditoria" class="button gray small" value="Registrar Orden Auditoría" onClick="RegistrarOrden(' . $codigos . '); return false" /></td>';
        echo '<td><input type="button" name="CancelarRegistroOrden" class="button gray small" value="Cancelar Operación" onClick="CancelarImpresion(); return false" /></td>';
    }

    if ($obsocial->getMedicos_cab() == '1') {
        echo '<td align="right"><input type="button" name="RegistrarOrdenAuditoria" id="RegistrarOrdenAuditoria" disabled="true" class="button gray small" value="Registrar Orden Auditoría" onClick="RegistrarOrdenMC(' . $codigos . '); return false" /></td>';
        echo '<td><input type="button" name="CancelarRegistroOrden" id="CancelarRegistroOrden" class="button gray small" value="Cancelar Operación" onClick="CancelarImpresion(); return false" /></td>';
    }

    echo '</table>';
?>

