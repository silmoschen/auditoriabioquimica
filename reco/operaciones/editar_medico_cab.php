<?
include_once('__routes.php');
include_once("../classes/cMedicosCab.php");

$modo = $_REQUEST['modo'];
if (isset($_GET['idprof'])) {
    $idprof = $_REQUEST['idprof'];
}
$codos = $_REQUEST['codos'];

$obj = new cMedicosCab();

if ($modo == 1) {
    $estado = 'readonly="true"';
}
if ($modo == 2) {
    $obj->getObject($codos, $idprof);
    $estado = 'readonly="true"';
}
?>

<form name="frmeditar_medico_cab" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Definir Médico de Cabecera</LEGEND>
        <table border="0px">

            <tr>
                <td align="left" style="WIDTH: 70px" td><div align="right">Nombre:</div></td>
                <td align="left">
                    <input name="nombremedico" id="nombremedico" type="text" style="font-size: 10px;" maxlength="40" width="15" size="53" onkeypress="javascript: if(ValidarNombreMedico(event, nombremedico.value)) {listMedicos.focus()}; return true" />
                </td>
            </tr>

            <tr>
                <td align="left" style="WIDTH: 70px" td><div align="right">Sel.Med.:</div></td>
                <td align="left">
                    <div id="ListadoMedicos">
                        <?php include('lista_medicos_ingordenes1.php') ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="left" style="WIDTH: 70px" td><div align="right">Cápitas:</div></td>
                <td align="left">
                    <input name="capitas" id="capitas" type="text" disabled="true" style="font-size: 10px;" maxlength="10" width="5" size="10" onkeypress="javascript: if(ValidarCapitas(event, capitas.value)) {estado.focus()}; return true" />
                </td>
            </tr>

            <tr>
                <td align="left" style="WIDTH: 70px" td><div align="right"></div></td>
                <td align="left">
                    <input name="estado" id="estado" type="checkbox" disabled="true" style="font-size: 10px;" onkeypress="javascript: if(ValidarEstado(event, estado.value)) {registrar.focus()}; return true" />Marcar este Médico como Inactivo
                </td>
            </tr>

        </table>
        <table border="0px">
            <tr>
                <td width="70" height="21"><?
                        if ($modo == 1) {
                            echo'<input type="button" name="registrar" value="Registrar" class="button gray small" onClick="Registrar(listMedicos.value, capitas.value, estado.checked, 1); return false" />';
                        }
                        if ($modo == 2) {
                            echo'<input type="button" name="registrar" value="Registrar" class="button gray small" onClick="Registrar(idprof.value, capitas.value, estado.checked, 2); return false" />';
                        }
                        ?>
                    <div align="right"></div></td>
                <td width="184"><input type="button" name="Cerrar" value="Cerrar" class="button gray small" onclick="RecargarMedico(); return false" /></td>
            </tr>
        </table>
    </FIELDSET>

</form>
