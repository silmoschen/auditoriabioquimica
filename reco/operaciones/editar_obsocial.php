<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
//variables POST
$codigo = $_REQUEST['codigo'];
$modo = $_REQUEST['modo'];

if ($modo == 1) {  // nuevo
    $mcodigo = '';
    $mnombre = '';
    $mfactnbu = '';
    $bonos = 'unchecked';
    $medicos_cab = 'unchecked';
    $soportemag = 'unchecked';
    $derivacion = 'unchecked';
    $inc_leyenda = 'unchecked';
    $ing_continuo = 'unchecked';
    $incluye_ab = 'unchecked';
    $aut_directa = 'unchecked';
    $coseguro = 'unchecked';
    $alta_paciente = 'unchecked';
    $coseguro_mf = 'unchecked';
    $nivel3 = 'unchecked';
    $tope_anual = 'unchecked';
    $practicas_rechazadas = 'unchecked';
    $orden_completa = 'unchecked';
    $mleyenda = '';
    $musuario = '';
    $mpass = '';
    $edita = '';
    $inactiva = '0';
    $gen_nroautorizacion = '0';
    $nivel2 = '0';
}
if ($modo == 2) {  // edición
    $obj = new cObsocial();
    $obj->getObject($codigo);
    $mcodigo = $obj->getCodigo();
    $mnombre = $obj->getNombre();
    $mfactnbu = $obj->getFactNbu();
    $musuario = $obj->getUsuario();
    $mpass = $obj->getPass();
    if ($obj->getBonos() == '1') {
        $bonos = 'checked="checked"';
    } else {
        $bonos = '';
    }
    if ($obj->getMedicos_cab() == '1') {
        $medicos_cab = 'checked="checked"';
    } else {
        $medicos_cab = '';
    }
    if ($obj->getSoportemag() == 'S') {
        $soportemag = 'checked="checked"';
    } else {
        $soportemag = '';
    }
    if ($obj->getDerivacion() == 'S') {
        $derivacion = 'checked="checked"';
    } else {
        $derivacion = '';
    }
    if ($obj->getInc_leyenda() == 'S') {
        $inc_leyenda = 'checked="checked"';
    } else {
        $inc_leyenda = '';
    }
    $mleyenda = $obj->getLeyenda();
    if ($obj->getIng_continuo() == 'S') {
        $ing_continuo = 'checked="checked"';
    } else {
        $ing_continuo = '';
    }
    if ($obj->getIncluye_ab() == 'S') {
        $incluye_ab = 'checked="checked"';
    } else {
        $incluye_ab = '';
    }
    if ($obj->getAutorizacionDirecta() == 'S') {
        $aut_directa = 'checked="checked"';
    } else {
        $aut_directa = '';
    }
    if ($obj->getCoseguro() == 'S') {
        $coseguro = 'checked="checked"';
    } else {
        $coseguro = '';
    }
    if ($obj->getAltaPaciente() == 'S') {
        $alta_paciente = 'checked="checked"';
    } else {
        $alta_paciente = '';
    }
    if ($obj->getCoseguroMontoFijo() == 'S') {
        $coseguro_mf = 'checked="checked"';
    } else {
        $coseguro_mf = '';
    }
    if ($obj->getNivel3() == 'S') {
        $nivel3 = 'checked="checked"';
    } else {
        $nivel3 = '';
    }
    if ($obj->getTopeAnual() == 'S') {
        $tope_anual = 'checked="checked"';
    } else {
        $tope_anual = '';
    }
    if ($obj->getPracticasRechazadas() == 'S') {
        $practicas_rechazadas = 'checked="checked"';
    } else {
        $practicas_rechazadas = '';
    }
    if ($obj->getOrdenCompleta() == 1) {
        $orden_completa = 'checked="checked"';
    } else {
        $orden_completa = '';
    }
    if ($obj->getInactiva() == 1) {
        $inactiva = 'checked="checked"';
    } else {
        $inactiva = '';
    }
    
    if ($obj->getGeneraNroAutorizacion() == 1) {
        $gen_nroautorizacion = 'checked="checked"';
    } else {
        $gen_nroautorizacion = '';
    }
    if ($obj->getNivel2() == 1) {
        $nivel2 = 'checked="checked"';
    } else {
        $nivel2 = '';
    }    

    $edita = 'readonly="true"';
}
?>

<form name="frmeditar_obsocial" method="post" onsubmit="return false">
    <FIELDSET>
        <LEGEND>Datos Obra Social</LEGEND>
        <table border="0px" align="left">
            <tr>
                <td width="100" height="21" align="left" style="WIDTH: 100px" td><div align="right">Codigo:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="codigo" id="codigo" type="text"' . $edita . ' size="8" maxlength="6" style="font-size: 10px;" value = ' . '"' . $mcodigo . '"' . ' onkeypress="javascript: if(ValidarCodigo(event, codigo.value)) {nombre.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right">Nombre:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="nombre" id="nombre" type="text" maxlength="60" size="60" style="font-size: 10px;" value = ' . '"' . $mnombre . '"' . ' onkeypress="javascript: if(ValidarNombre(event, nombre.value)) {factnbu.focus()}; return true" />';
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left"><div align="right">F. NBU (S/N):</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="factnbu" id="factnbu" type="text" maxlength="1" size="1" style="font-size: 10px;" value = ' . '"' . $mfactnbu . '"' . ' onkeypress="javascript: if(ValidarFactNBU(event, factnbu.value)) {bonos.focus()}; return true" />';
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="bonos" id="bonos" type="checkbox" style="font-size: 10px;"' . $bonos . ' onkeypress="javascript: if(ValidarBonos(event, bonos.value)) {medicos_cab.focus()}; return true" />' . 'Aplica Sistema de Bonos ?';
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left">&nbsp;</td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="medicos_cab" id="medicos_cab" type="checkbox" style="font-size: 10px;"' . $medicos_cab . ' onkeypress="javascript: if(ValidarMedicos_cab(event, medicos_cab.value)) {soportemag.focus()}; return true" />' . "Incluye Médico de Cabecera ?";
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="soportemag" id="soportemag" type="checkbox" style="font-size: 10px;"' . $soportemag . ' onkeypress="javascript: if(ValidarSoportemag(event, soportemag.value)) {derivacion.focus()}; return true" />' . "Exporta Datos a Soporte Magnético ?";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="derivacion" id="derivacion" type="checkbox" style="font-size: 10px;"' . $derivacion . ' onkeypress="javascript: if(ValidarDerivacion(event, derivacion.value)) {inc_leyenda.focus()}; return true" />' . "Imprime Leyenda con Pedido de Derivacion ?";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="right">&nbsp;</td>
                <td width="233" align="left">
                    <?
                    echo '<input name="inc_leyenda" id="inc_leyenda" type="checkbox" style="font-size: 10px;"' . $inc_leyenda . ' onkeypress="javascript: if(ValidarInc_leyenda(event, inc_leyenda.value)) {leyenda.focus()}; return true" />' . 'Informa en el Cupon el Monto de las Practicas Rechazadas?';
                    ?>
                </td>
                <td width="79" align="left"><div align="right">Leyenda:</div></td>
                <td width="140" align="left">
                    <?
                    echo '<input name="leyenda" id="leyenda" type="text" maxlength="50" size="25" style="font-size: 10px;" value = ' . '"' . $mleyenda . '"' . ' onkeypress="javascript: if(ValidarLeyenda(event, leyenda.value)) {ing_continuo.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="ing_continuo" id="ing_continuo" type="checkbox" style="font-size: 10px;"' . $ing_continuo . ' onkeypress="javascript: if(ValidarIng_continuo(event, ing_continuo.value)) {incluye_ab.focus()}; return true" />' . "Ingresa las Ordenes en Forma Contínua ?";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="incluye_ab" id="incluye_ab" type="checkbox" style="font-size: 10px;"' . $incluye_ab . ' onkeypress="javascript: if(ValidarIng_continuo(event, incluye_ab.value)) {aut_directa.focus()}; return true" />' . "Incluye el Acto Bioquímico en Orden ?";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="aut_directa" id="aut_directa" type="checkbox" style="font-size: 10px;"' . $aut_directa . ' onkeypress="javascript: if(ValidarIng_continuo(event, incluye_ab.value)) {coseguro.focus()}; return true" />' . "Autoriza Orden Directamente ?";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="coseguro" id="coseguro" type="checkbox" style="font-size: 10px;"' . $coseguro . ' onkeypress="javascript: if(ValidarENTER(event)) {coseguro_mf.focus()}; return true" />' . "Cobra Coseguro ?";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="coseguro_mf" id="coseguro_mf" type="checkbox" style="font-size: 10px;"' . $coseguro_mf . ' onclick="javascript: validarMF();" onkeypress="javascript: if(ValidarENTER(event)) {alta_paciente.focus()}; return true" />' . "Cobra Monto Fijo por Boleta en Concepto de Coseguro ?";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="alta_paciente" id="alta_paciente" type="checkbox" style="font-size: 10px;"' . $alta_paciente . ' onkeypress="javascript: if(ValidarIng_continuo(event, incluye_ab.value)) {nivel3.focus()}; return true" />' . "Permite el Alta de Pacientes al Ingresar Orden ?";
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="nivel3" id="nivel3" type="checkbox" style="font-size: 10px;"' . $nivel3 . ' onkeypress="javascript: if(ValidarENTER(event)) {tope_anual.focus()}; return true" />' . "Discrimina Prácticas de 3º Nivel ?";
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="tope_anual" id="tope_anual" type="checkbox" style="font-size: 10px;"' . $tope_anual . ' onkeypress="javascript: if(ValidarENTER(event)) {practicas_rechazadas.focus()}; return true" />' . "Utiliza Sistema de Autorización por Topes Anuales / Revisión Posterior de Ordenes por parte del Usuario Efector ?";
                    ?>
                </td>
            </tr>
             <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="practicas_rechazadas" id="practicas_rechazadas" type="checkbox" style="font-size: 10px;"' . $practicas_rechazadas . ' onkeypress="javascript: if(ValidarENTER(event)) {orden_completa.focus()}; return true" />' . "Mostrar las Ordenes Rechazadas/Pendientes en Cupón ?";
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="orden_completa" id="orden_completa" type="checkbox" style="font-size: 10px;"' . $orden_completa . ' onkeypress="javascript: if(ValidarIng_continuo(event, incluye_ab.value)) {usuario.focus()}; return true" />' . "Imprime Cupón con Detalle de Determinaciones ?";
                    ?>
                </td>
            </tr>
             <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="inactiva" id="inactiva" type="checkbox" style="font-size: 10px;"' . $inactiva . ' onkeypress="javascript: if(ValidarIng_continuo(event, incluye_ab.value)) {usuario.focus()}; return true" />' . "Inactivar Obra Social";
                    ?>
                </td>
            </tr>
             <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="gen_nroautorizacion" id="gen_nroautorizacion" type="checkbox" style="font-size: 10px;"' . $gen_nroautorizacion . ' onkeypress="javascript: if(ValidarIng_continuo(event, incluye_ab.value)) {nivel2.focus()}; return true" />' . "Genera Número de Autorización";
                    ?>
                </td>
            </tr>
            
            <tr>
                <td align="left"><div align="right"></div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="nivel2" id="nivel2" type="checkbox" style="font-size: 10px;"' . $nivel2 . ' onkeypress="javascript: if(ValidarENTER(event)) {usuario.focus()}; return true" />' . "Permitir Soprte de Segundo Nivel (OSDE)";
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right">Usuario Exportación:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="usuario" id="usuario" type="text" maxlength="20" size="20" style="font-size: 10px;" value = ' . '"' . $musuario . '"' . ' onkeypress="javascript: if(ValidarUsuario(event, usuario.value)) {pass.focus()}; return true" />';
                    ?>
                </td>
            </tr>

            <tr>
                <td align="left"><div align="right">Password:</div></td>
                <td colspan="3" align="left">
                    <?
                    echo '<input name="pass" id="pass" type="text" maxlength="20" size="20" style="font-size: 10px;" value = ' . '"' . $mpass . '"' . ' onkeypress="javascript: if(ValidarPass(event, pass.value)) {registrar.focus()}; return true" />';
                    ?></td>
            </tr>
            
            

            <tr>
                <td width="100" align="right">
                    <?
                    if ($modo == 1) {
                        echo '<input type="button" class="button gray small" name="registrar" id="registrar" value="Registrar" onClick="Registrar(codigo.value, nombre.value, factnbu.value, 1, bonos.checked, medicos_cab.checked, soportemag.checked, usuario.value, pass.value, derivacion.checked, inc_leyenda.checked, leyenda.value, ing_continuo.checked, incluye_ab.checked, aut_directa.checked, coseguro.checked, alta_paciente.checked, coseguro_mf.checked, nivel3.checked, tope_anual.checked, practicas_rechazadas.checked, orden_completa.checked, inactiva.checked, gen_nroautorizacion.checked, nivel2.checked); return false" />';
                    }
                    if ($modo == 2) {
                        echo '<input type="button" class="button gray small" name="registrar" id="registrar" value="Registrar" onClick="Registrar(codigo.value, nombre.value, factnbu.value, 2, bonos.checked, medicos_cab.checked, soportemag.checked, usuario.value, pass.value, derivacion.checked, inc_leyenda.checked, leyenda.value, ing_continuo.checked, incluye_ab.checked, aut_directa.checked, coseguro.checked, alta_paciente.checked, coseguro_mf.checked, nivel3.checked, tope_anual.checked, practicas_rechazadas.checked, orden_completa.checked, inactiva.checked, gen_nroautorizacion.checked, nivel2.checked); return false" />';
                    }
                    ?>
                </td>
                <td colspan="3" align="left">
                    <input type="button" class="button gray small" name="Cerrar" id="Cerrar" value="Cerrar" onclick="RecargarObsocial(); return false" />
                </td>
            </tr>
        </table>

        <label></label>
        <br>
    </FIELDSET>

</form>