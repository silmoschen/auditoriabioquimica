<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEspecialidades.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTipoPrestadores.php');
//variables POST
$codigo = $_REQUEST['codigo'];
$modo = $_REQUEST['modo'];

if ($modo == 1) {
    $mcodigo = '';
    $mnombre = '';
    $musuario = '';
    $mpass = '';
    $mestado = '';
    $mdireccion = '';
    $mnrocuit = '';
    $memail = '';
    $mid_especialidad = '';
    $mid_tipoprestador = '0';
    $mfechamat = '';
    $mmatricula_nac = '';
    $baja = '';
    $mmatricula1 = '';
    $mparametro2 = '';
    $mparametro3 = '';
    $nivel2 = '0';
}
if ($modo == 2) {
    $obj = new cEfector();
    $obj->getObject($codigo);
    $mcodigo = $_REQUEST['codigo'];
    $mnombre = $obj->getNombre();
    $musuario = $obj->getUsuario();
    $mpass = $obj->getPassword();
    $mdireccion = $obj->getDireccion();
    $mnrocuit = $obj->getNrocuit();
    $memail = $obj->getEmail();
    $mid_especialidad = $obj->getId_especialidad();
    $mid_tipoprestador = $obj->getId_tipoprestador();
    $mfechamat = $obj->getFechamat();
    $mmatricula_nac = $obj->getMatricula_nac();
    $mmatricula1 = $obj->parametro1;
    $estado = 'readonly="true"';
    if ($obj->baja == 'S')
        $baja = 'checked="checked"';
    $mparametro2 = $obj->parametro2;
    $mparametro3 = $obj->parametro3;
    if ($obj->getNivel2())
        $nivel2 = 'checked="checked"';    
    
}

$especialidad = new cEspecialidades();
$tp = new cTipoPrestadores();
?>

<form name="frmeditar_efector" method="post" onsubmit="return false">

    <FIELDSET>
        <LEGEND>Datos del Efector</LEGEND>
        <table border="0px" align="center" width="500px">

            <tr>
                <td width="100" align="right">Código:</td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="codigo" id="codigo" type="text" style="font-size: 10px;" type="text"' . $estado . '"size="8" maxlength="6" size = "6" value = ' . '"' . $mcodigo . '"' . ' onkeypress="javascript: if(ValidarCodigo(event, codigo.value)) {nombre.focus()}; return true" />';
                    ?></td>
            </tr>
            <tr>
                <td width="100" align="right">Nombre:</td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="nombre" id="nombre" type="text" style="font-size: 10px;" type="text" size="77"  maxlength="60" value = ' . '"' . $mnombre . '"' . ' onkeypress="javascript: if(ValidarNombre(event, nombre.value)) {direccion.focus()}; return true" />';
                    ?></td>
            </tr>

            <tr>
                <td align="left" WIDTH=100><div align="right">Dirección:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="direccion" id="direccion" type="text" style="font-size: 10px;" type="text" size="77" maxlength="60" value = ' . '"' . $mdireccion . '"' . ' onkeypress="javascript: if(ValidarDireccion(event, direccion.value)) {nrocuit.focus()}; return true" />';
                    ?></td></tr>

            <tr>
                <td align="left" WIDTH=100><div align="right">Nro. C.U.I.T.:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="nrocuit" id="nrocuit" type="text" style="font-size: 10px;" type="text" size="20" maxlength="13" value = ' . '"' . $mnrocuit . '"' . ' onkeypress="javascript: if(ValidarCuit(event, nrocuit.value)) {email.focus()}; return true" />';
                    ?></td></tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Email:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="email" id="email" type="text" style="font-size: 10px;" type="text" size="77" maxlength="60" value = ' . '"' . $memail . '"' . ' onkeypress="javascript: if(ValidarEmail(event, direccion.value)) {fechamat.focus()}; return true" />';
                    ?></td></tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Fecha Mat.:</div></td>
                <td width="165" align="left">
                    <?
                    echo '<input name="fechamat" id="fechamat" type="text" style="font-size: 10px;" type="text" size="10" maxlength="10" value = ' . '"' . $mfechamat . '"' . ' onkeypress="javascript: if(ValidarFechamat(event, fechamat.value)) {matricula_nac.focus()}; return true" />';
                    ?>(dd/mm/aaaa)</td>

                <td width="83" align="left"><div align="right">Nro.Mat.:</div></td>
                <td width="128" align="left"><?
                    echo '<input name="matricula_nac" id="matricula_nac" type="text" style="font-size: 10px;" type="text" size="12" maxlength="30" value = ' . '"' . $mmatricula_nac . '"' . ' onkeypress="javascript: if(ValidarMatriculanac(event, matricula_nac.value)) {matricula1.focus()}; return true" />';
                    ?></td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Matrícula:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="matricula1" id="matricula1" type="text" style="font-size: 10px; width:150px" type="text" size="77" maxlength="60" value = ' . '"' . $mmatricula1 . '"' . ' onkeypress="javascript: if(ValidarMatricula1(event, matricula1.value)) {parametro2.focus()}; return true" />';
                    ?>
                    (SWISS MEDICAL, etc.)
                </td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Cód.Prest.:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="parametro2" id="parametro2" type="text" style="font-size: 10px; width:150px" type="text" size="77" maxlength="60" value = ' . '"' . $mparametro2 . '"' . ' onkeypress="javascript: if(ValidarParametro2(event, parametro2.value)) {parametro3.focus()}; return true" />';
                    ?>
                    (Código Prestador, solo OSDE)
                </td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Terminal:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="parametro3" id="parametro3" type="text" style="font-size: 10px; width:150px" type="text" size="77" maxlength="60" value = ' . '"' . $mparametro3 . '"' . ' onkeypress="javascript: if(ValidarParametro3(event, parametro3.value)) {listEspecialidades.focus()}; return true" />';
                    ?>
                    (TERMINAL, solo OSDE)
                </td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Especialidad:</div></td>
                <td colspan="3" align="left">
                    <select name="listEspecialidades" id="listEspecialidades" style="width:339px" onkeypress="javascript: if (ValidarEspecialidad(event)) {
                                listTipoPrestadores.focus()
                            }
                            ;
                            return true" />
                    <?php
                    $resultado = $especialidad->getEspecialidades();
                    while ($fila = mysql_fetch_array($resultado)) {
                        if ($fila['id_especialidad'] == $mid_especialidad) {
                            echo '<option selected value =' . '"' . $fila['id_especialidad'] . '"' . '>' . substr($fila['descripcion'], 0, 40) . '</option>';
                        } else {
                            echo '<option value =' . '"' . $fila['id_especialidad'] . '"' . '>' . substr($fila['descripcion'], 0, 40) . '</option>';
                        }
                    }
                    ?>
                    </select></td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Tipo Prestador:</div></td>
                <td colspan="3" align="left">
                    <?
                    $resultado = $tp->getListaTipoPrestadores();
                    ?>
                    <select name="listTipoPrestadores" id="listTipoPrestadores" style="width:339px" onkeypress="javascript: if (ValidarTipoPrestador(event)) {
                                usuario.focus()}; return true" />
                    <?php
                    while ($fila = mysql_fetch_array($resultado)) {
                        if ($fila['id'] == $mid_tipoprestador) {
                            echo '<option selected value =' . '"' . $fila['id'] . '"' . '>' . substr($fila['descrip'], 0, 40) . '</option>';
                        } else {
                            echo '<option value =' . '"' . $fila['id'] . '"' . '>' . substr($fila['descrip'], 0, 40) . '</option>';
                        }
                    }
                    ?>
                    </select></td>
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">Usuario:</div></td>
                <td width="165" align="left">
                    <?
                    echo '<input name="usuario" id="usuario" type="text" style="font-size: 10px;" type="text" size="12" maxlength="10" value = ' . '"' . $musuario . '"' . ' onkeypress="javascript: if(ValidarUsuario(event, usuario.value)) {pass.focus()}; return true" />';
                    ?>
                </td>

                <td width="83" align="left"><div align="right">Password:</div></td>
                <td width="128" align="left">
                    <?
                    echo '<input name="pass" id="pass" type="text" style="font-size: 10px;" type="text" size="12" maxlength="10" value = ' . '"' . $mpass . '"' . ' onkeypress="javascript: if(ValidarPass(event, pass.value)) {registrar.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <?
            if ($modo == 2) {
                echo '<tr>';
                echo '<td align="left" td style="WIDTH: 100px"><div align="right"></div></td>';
                echo '<td width="165" align="left">';

                echo '<input name="baja" id="baja" type="checkbox" style="font-size: 10px;"' . $baja . ' onclick="javascript: bajaEfector(baja.checked); return true" />' . "Inhabilitar Este Efector";

                echo '</td>';

                echo '<td width="83" align="left"><div align="right"></div></td>';
                echo '<td width="128" align="left"></td></tr>';
            }
            ?>
            
             <tr>                
                <td width="83" align="left">
                </td>
                <td width="128" align="left">
                   <?
                    echo '<input name="nivel2" id="nivel2" type="checkbox" style="font-size: 10px;"' . $nivel2 . '" />' . "Factura OSDE en 2º Nivel";
                   ?>
                </td>
               
            </tr>

            <tr>
                <td align="left" td style="WIDTH: 100px"><div align="right">
                        <?
                        if ($modo == 1) {
                            echo'<input type="button" name="registrar" type="text" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, nombre.value, usuario.value, pass.value, direccion.value, nrocuit.value, email.value, listEspecialidades.value, listTipoPrestadores.value, fechamat.value, matricula_nac.value, 1, matricula1.value, parametro2.value, parametro3.value, nivel2.checked); return false" />';
                        }
                        if ($modo == 2) {
                            echo'<input type="button" name="registrar" type="text" class="button gray small" value="Registrar" onClick="Registrar(codigo.value, nombre.value, usuario.value, pass.value, direccion.value, nrocuit.value, email.value, listEspecialidades.value, listTipoPrestadores.value, fechamat.value, matricula_nac.value, 2, matricula1.value, parametro2.value, parametro3.value, nivel2.checked); return false" />';
                        }
                        ?>
                    </div>
                <td align="left" td style="WIDTH: 100px">
                    <?
                    echo '<input type="button" name="Cerrar" type="text" class="button gray small" value="Cerrar" onclick="RecargarControl(); return false" />';
                    ?>
                </td>
            </tr>
        </table>
    </FIELDSET>

</form>