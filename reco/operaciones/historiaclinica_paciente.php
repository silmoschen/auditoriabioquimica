<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cDiagnosticosOMS.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$obj = new cAuditoria;
$u = new cUtiles;
$objaf = new cAfiliados;
$objmedico = new cMedicos;
$diagnostico = new cDiagnosticosOMS;
$nbu = new cNBU;
$efector = new cEfector;
$entidad = new cEntidad;
$obsocial = new cObsocial;

$entidad->getObject();

$nroauditoria = '"' . $_REQUEST['nrotrans'] . '"';
$nrotrans = $_REQUEST['nrotrans'];
$codos = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];
$navegador = $_REQUEST['navegador'];

// Verificamos si la obra social no utiliza el padrón de otra
$codoseq = $codos;
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codoseq = $eq->getCodigo2();
}

$obj->getObject($_REQUEST['nrotrans']);

$objmedico->getObject($codoseq, $obj->getIdprof());
$diagnostico->getObject($obj->getIddiag());
$objaf->getObject($codoseq, $nrodoc);
$efector->getObject($obj->getEfector());

echo '<hr>Paciente: <b>' . $nrodoc . ' - ' . $objaf->getNombre() . '</b>';
echo '<br>Nro. Trans.: <b>' . $_REQUEST['nrotrans'] . '</b> Fecha: <b>' . $obj->getFecha() . '</b>';
echo '<br>Médico: <b>' . $objmedico->getNombre() . '     </b>Efector: <b> ' . $efector->getNombre() . '</b>';
echo '<br>Diagnóstico: <b>' . $diagnostico->getDescrip() . '</b>';
echo '<br>Obs. Prestador: <b>' . $obj->getObservacionEfector() . '</b>';
echo '<br>Obs. Auditor: <b>' . $obj->getObservacion() . '</b>';

echo '<hr>';
?>

<table width='550px'>
    <tr>
        <td> 
            <font color="#0066ff">
            <b>Unidades Autorizadas</b> 
            </font>
        </td>
        <td align="left" width="25">
            <font color="#ff0000">
            <b>
                <div id="divunidades" align="left">
                </div>
            </b>
            </font>
        </td>
    </tr>
</table>
<hr/>

<table width='550px'>

    <tr>
        <td width='100px' align='left'>
            <?
            echo '<input type="button" class="button gray small" value="Observaciones del Auditor" name="btnObsAuditor" onclick="ObservacionAuditor(' . "'" . $nrotrans . "'" . '); return false" />';
            ?>
        </td>
        <td width='100px' align='left'>
            <?
            echo '<input type="button" class="button gray small" value="Dejar Pendiente" name="btnPendiente" onclick="DejarPendiente(' . "'" . $nrotrans . "'" . '); return false" />';
            ?>
        </td>
        <td width='80px' align='right'>
            <?
            echo '<input type="button" class="button gray small" value="Confirmar" name="btnFinalizar" onclick="FinalizarCambios(' . "'" . $nrotrans . "'" . '); return false" />';
            ?>
        </td>
        <td width='100px' align='right'>
            <input type="button" value="Cancelar" class="button gray small" name="btnCancelar" onclick="CancelarCambios();
                    return false" />
        </td>
    </tr>

</table>

<table>
    <tr>
        <td width='160px'><div id="determinacion"></div>
        </td>
        <td width='350px'><div id="descripdeter"></div>
        </td>
</table>

<table width='550px'>
    <tr>
        <td width="200px" bgcolor="#D7D7D7"><b>Autorizado</b></td>
        <td width="200px" bgcolor="#D7D7D7"><b>Rechazado</b></td>
        <td width="150px" bgcolor="#CCFFCC"><b>a Cápita</b></td>
    </tr>
</table>

<table width='500px'>

    <?
    $resultadodet = $obj->getDeterminaciones($nroauditoria);
    echo '<tr><td width="200px">';
    echo '<select id="autorizadas" size="8" multiple="multiple" style="border: none; font-size:11px; width:200px" onkeypress="javascript: if(ControlAutorizadas(event)) {autorizadas.focus()}; return true;">';

    $i = 0;
    while ($f = mysql_fetch_array($resultadodet)) {
        if ($f['estado'] == 'A') {
            $nbu->getObject($f['codigo']);
            $i++;
            echo '<option value=' . $f['codigo'] . '>' . $f['codigo'] . '-' . $nbu->getDescrip() . '</option>';
        }
    }
    if ($i == 0) {
        echo "<option value='-'>-</option>";
    }


    echo '</select>';
    echo '</td>';

    echo '<td width="200px">';
    echo '<select id="rechazadas" size="8" style="border: none; font-size:11px; width:200px" onkeypress="javascript: if(ControlRechazadas(event)) {rechazadas.focus()}; return true;">';

    $resultadodet = $obj->getDeterminaciones($nroauditoria);
    $i = 0;
    while ($f = mysql_fetch_array($resultadodet)) {
        if ($f['estado'] == 'R') {
            $nbu->getObject($f['codigo']);
            $i++;
            echo '<option value=' . $f['codigo'] . '>' . $f['codigo'] . '-' . $nbu->getDescrip() . '</option>';
        }
    }
    if ($i == 0) {
        echo "<option value='-'>-</option>";
    }

    echo '</select>';
    echo '</td>';


    echo '<td width="100px">';
    echo '<select id="capitadas" size="8" style="border: none; font-size:11px; width:140px" onkeypress="javascript: if(ControlCapitadas(event)) {rechazadas.focus()}; return true;">';

    $resultadocap = $obj->getItemsCapitas($nroauditoria);
    $i = 0;
    while ($f = mysql_fetch_array($resultadocap)) {
        $nbu->getObject($f['codigo']);
        $i++;
        echo '<option value=' . $f['codigo'] . '>' . $f['codigo'] . '-' . $nbu->getDescrip() . '</option>';
    }
    if ($i == 0) {
        echo "<option value='-'>-</option>";
    }

    echo '</select>';
    echo '</td></tr>';
    ?>

</table>

<table width='550px'>
    <tr>
        <td width="100px">
            <input type="button" class="button green small" value="Agregar" name="AgregarRechazado" onclick="CargarAutorizado();
                    return false" />
        </td>
        <td width="150px" align="right">
            <input type="button" class="button gray small" value="Rechazar >" name="btnRechazar" onclick="Rechazar();
                    return false" />
        </td>
        <td width="150px" align="right">
            <input type="button" class="button gray small" value="< Autorizar" name="btnAutorizar" onclick="Autorizar();
                    return false" />
        </td>
        <td width="150px">
            <input type="button" class="button green small" value="Agregar" name="AgregarAutorizado" onclick="CargarRechazado();
                    return false" />
        </td>
        <td width="100px" align="right">
            <input type="button" class="button orange small" value=">>" name="btnRechazarCapita" onclick="RechazarCapita();
                    return false" />
        </td>
        <td width="10px" align="right">
            <input type="button" class="button orange small" value="<<" name="btnAutorizarCapita" onclick="AutorizarCapita();
                    return false" />
        </td>
    </tr>

</table>
<table width='500px'>
    <tr>
        <td align="left" width="20px"></td>
        <td align="left" width="200px">
            <font style="font-size: 9px">+ Agrega, - Borra Determinación</font>
        </td>
        <td align = "left" width="250px">
            <font style="font-size: 9px">SPACE Mueve Determinación.</font>
        </td>

    </tr>

</table>

<hr>

<textarea cols="90" rows="16" wrap="off" style="border: none; font-size:11px" readonly="true">
    <?php
    $numerotrans = $_REQUEST['nrotrans'];

// Determinamos si unifica o no el historial del paciente con las obras sociales
    if ($entidad->getUnifica_hist() != '1') {
        $resultadoaudit = $obj->getOrdenesAfiliado($codos, $nrodoc);
    } else {
        $resultadoaudit = $obj->getOrdenesAfiliado('*', $nrodoc);
    }

    while ($fila = mysql_fetch_array($resultadoaudit)) {
        // Excluimos la orden actual
        if ("'" . $fila['nroauditoria'] . "'" != "'" . $numerotrans . "'") {
            // Encabezado
            $objmedico->getObject($codos, $fila['idprof']);
            $diagnostico->getObject($fila['iddiag']);

            $os = '';
            if ($entidad->getUnifica_hist() == '1') {
                $obsocial->getObject($fila['codos']);
                $os = ' [' . $obsocial->getNombre() . ']';
            }

            echo 'Nro.Audit.: ' . $fila['nroauditoria'] . '  Fecha: ' . $u->getFechaDDMMAA($fila['fecha']) . $os . chr(13);
            echo 'Diagnostico: ' . $diagnostico->getDescrip() . '  Médico: ' . $fila['idprof'] . '-' . $objmedico->getNombre() . chr(13);
            // Detalle
            $autorizadas = '';
            $rechazadas = '';
            $nro = "'" . $fila['nroauditoria'] . "'";
            $resultadodet = $obj->getDeterminaciones($nro);
            while ($f = mysql_fetch_array($resultadodet)) {
                if ($f['estado'] == 'A') {
                    $autorizadas = $autorizadas . $f['codigo'] . '  ';
                } else {
                    $rechazadas = $rechazadas . $f['codigo'] . '  ';
                }
            }
            echo '   Autorizadas: ' . $autorizadas . chr(13);
            echo '   Rechazadas: ' . $rechazadas . chr(13);
            if ($fila['anulada'] == 'S') {
                echo '***  ORDEN ANULADA POR EL EFECTOR ***' . chr(13);
            }
            if (strlen($fila['obsauditor']) > 0) {
                echo '   Obs.Auditor: ' . $fila['obsauditor'] . chr(13);
            }
            echo '----------------------------------------------------------------' . chr(13);
            echo chr(13);
        }
    }
    ?>
</textarea>