<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$obj = new cObsocial();
$obj->getObject($codos);
?>

<FIELDSET>
    <LEGEND>Alta de Afiliados</LEGEND>
    <table border="0px">
        <tr>
            <td width="120px"><div align="right">Obra Social:</div></td>
            <td align="left">
                <label>
                    <?
                    echo '<input name="codos" style="font-size: 10px;" type="text" maxlength="6" size="8" readonly="true" value = ' . '"' . $codos . '"' . "/>";
                    echo ' ' . $obj->getNombre();
                    ?>
                </label>
            </td>
        </tr>
        <tr>
            <td><div align="right">Nro. de Documento:</div></td>
            <td align="left">

                <?
                echo '<input name="nrodocaf" style="font-size: 10px;" type="text" maxlength="15" size="15" readonly="true" size="8" value = ' . '"' . $nrodoc . '"' . "/>";
                ?>
            </td>
        </tr>
        <tr>
            <td><div align="right">Apellido y Nombre:</div></td>
            <td align="left">
                <label></label>
                <input name="nombre" type="text" style="font-size: 10px;" maxlength="50" size="50" onkeypress="javascript: if (ValidarNombre(event, nombre.value)) {
                            observac.focus()
                        }
                        ;
                        return true">
            </td>
        </tr>
        <tr>
            <td><div align="right">Observación:</div></td>
            <td align="left">
                <label>
                    <input name="observac" type="text" style="font-size: 10px;" maxlength="50" size="50" onkeypress="javascript: if (ValidarObservac(event, observac.value)) {
                            fechanac.focus()
                        }
                        ;
                        return true">
                </label>
            </td>
        </tr>
        <tr>
            <td><div align="right">Fecha Nacimiento:</div></td>
            <td align="left">
                <label>
                    <input name="fechanac" type="text" style="font-size: 10px;" maxlength="10" size="12" onkeypress="javascript: if (ValidarFechanac(event, fechanac.value)) {
                            depto.focus()
                        }
                        ;
                        return true">
                    (dd/mm/aaaa)
                </label>
            </td>
        </tr>
        <tr>
            <td align="right">Pertenece al Depto. (S/N) ?:</td>
            <td align="left"><input name="depto" type="text"  style="font-size: 10px;" maxlength="1" size="1" onkeypress="javascript: if (ValidarDepto(event, retiva.value)) {
                            retiva.focus()
                        }
                        ;
                        return true"></td>
        </tr>
        <tr>
            <td align="right">Retiene I.V.A. ?:</td>
            <td align="left">
                <select name="retiva" id="retiva" onkeypress="javascript: if (ValidarEnter(event)) {
                            RegistrarAfil.focus();
                        }">
                    <OPTION VALUE ="N">No</OPTION>
                    <OPTION VALUE="S">Sí</OPTION>
                </select>
            </td>
        </tr>
    </table>
    <table border="0px">
        <tr>
            <td colspan="2">
                <input type="button" class="boton" name="RegistrarAfil" value="Registrar" onClick="Registrar_Afiliado(codos.value, nrodocaf.value, nombre.value, observac.value, fechanac.value, depto.value, retiva.value);
                        return false" />
                <input type="button" class="boton" name="Cancelar" value="Cancelar" onClick="CancelarAltaPaciente();
                        return false" />
            </td>
        </tr>
    </table>
</FIELDSET>