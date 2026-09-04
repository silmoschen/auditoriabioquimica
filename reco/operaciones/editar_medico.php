<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cMedicos.php");

$modo = $_REQUEST['modo'];
if (isset($_GET['idprof'])) {
    $idprof = $_REQUEST['idprof'];
} else {
    $idprof = "";
}

$codos = $_REQUEST['codos'];

$obj = new cMedicos();

$e = 'unchecked="true"';

if ($modo == 1) {
    $midprof = $obj->Nuevo($codos);
    $mnombre = '';
    $mmatricula = '';
    $mlibro = '';
    $mfolio = '';
    $estado = 'readonly="true"';
}
if ($modo == 2) {
    $obj->getObject($codos, $idprof);
    $midprof = $obj->getIdprof();
    $mnombre = $obj->getNombre();
    $mmatricula = $obj->getMatricula();
    $mlibro = $obj->getLibro();
    $mfolio = $obj->getFolio();
    if ($obj->estado == 'S') {
        $e = 'checked="false"';
    } else {
        $e = 'unchecked="true"';
    }
    //$estado = 'readonly="true"';    
}

$estado = '';

?>

<form name="frmeditar_medico" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Datos del Medico</LEGEND>
        <table border="0px">
            <tr>
                <td width="132" align="right">Código:</td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="idprof" id="idprof" style="font-size: 10px;" type="text"' . $estado . '"size="20" maxlength="50" value = ' . '"' . $midprof . '"' . ' onkeypress="javascript: if(ValidarIdprof(event, idprof.value)) {matricula.focus()}; return true" />';
                    ?>
                </td>
            </tr>
            <tr>
                <td width="132" align="right">Matrícula/Libro/Folio:</td>
                <td width="104" align="left">
                    <?
                    echo '<input name="matricula" id="matricula" type="text" style="font-size: 10px;" size="10" maxlength="7" value = ' . '"' . $mmatricula . '"' . ' onkeypress="javascript: if(ValidarMatricula(event, matricula.value)) {libro.focus()}; return true" />';
                    ?></td>
                <td width="106" align="left">
                    <?
                    echo '<input name="libro" id="libro" type="text" style="font-size: 10px;" size="5" maxlength="10" value = ' . '"' . $mlibro . '"' . ' onkeypress="javascript: if(ValidarLibro(event, libro.value)) {folio.focus()}; return true" />';
                    ?>
                </td>
                <td width="106" align="left">
                    <?
                    echo '<input name="folio" id="folio" type="text" style="font-size: 10px;" size="5"   	maxlength="10" value = ' . '"' . $mfolio . '"' . ' onkeypress="javascript: if(ValidarFolio(event, folio.value)) {nombre.focus()}; return true" />';
                    ?>
                </td>
            </tr>
            <tr>
                <td width="132" align="right">Nombre:</td>
                <td colspan="3" align="left" width="300px">
                    <?
                    echo '<input name="nombre" id="nombre" type="text" style="font-size: 10px;" size="60" maxlength="60" value = ' . '"' . $mnombre . '"' . ' onkeypress="javascript: if(ValidarNombre(event, nombre.value)) {estado.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <tr>
                <td width="132" align="right"></td>
                <td colspan="3" align="left" width="300px">
                    <?
                    echo '<input name="estado" id="estado" type="checkbox" ' . $e .' style="font-size: 10px;" onkeypress="javascript: if(ValidarENTER(event)) {registrar.focus()}; return true" />Marcar este Médico como Inactivo';
                    ?>
                </td>
            </tr>

        </table>
        <table border="0px">
            <tr>
                <td width="70" height="21">
                    <?
                    if ($modo == 1) {
                        echo'<input type="button" name="registrar" value="Registrar" class="button gray small" onClick="Registrar(idprof.value, nombre.value, matricula.value, libro.value, folio.value, estado.checked, 1); return false" />';
                    }
                    if ($modo == 2) {
                        echo'<input type="button" name="registrar" value="Registrar" class="button gray small" onClick="Registrar(idprof.value, nombre.value, matricula.value, libro.value, folio.value, estado.checked, 2); return false" />';
                    }
                    ?>
                    <div align="right"></div></td>
                <td width="184"><input type="button" name="Cerrar" value="Cerrar" class="button gray small" onclick="RecargarMedico(); return false" /></td>
            </tr>
        </table>
    </FIELDSET>

</form>
