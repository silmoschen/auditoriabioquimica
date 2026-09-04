<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
$auditoria = new cAuditoria;

$nroauditoria = $_REQUEST['nrotrans'];

$auditoria->getObject($nroauditoria);

?>
<table width='500px'>
<tr><td width='490px' align='left'>
Obs. Auditor:
<?
echo '<input name="observacion" id="observacion" value=' . '"' . $auditoria->getObservacion() . '"' . ' style="font-size: 10px;" type="text" maxlength="150" width="85" size="85" onkeypress="javascript: if(ValidarObservacion(event, observacion.value, ' . $nroauditoria . ')) {btnFinalizar.focus()}; return true">';
echo '<input name="btnCancelaObs" class="button gray small" type="button" value="X" onClick="javascript: if(CerrarCodigo()); return true">';
?>
</td></tr>
</table>
