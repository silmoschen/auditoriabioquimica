<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTransaccionesCoseguroIapos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAuditoria.php');

$nroauditoria = $_REQUEST['nrotrans'];

echo '<input type = "hidden" id="nroauditcos" value = "' . $nroauditoria . '"/>';

$auditoria = new cAuditoria();
$c = new cTransaccionesCoseguroIapos();

$auditoria->getObject($nroauditoria);

// Sin auditoria o aprobacion directa, no se puede aplicar bonos

if ($auditoria->diferida == 'S' and $auditoria->auditada != 'S') {
    echo '<h1>No se Puede Procesar una Orden Pendiente de Auditoria</h1>';
    echo '<input type="button" name="btnCancelaCoseguro" value="Regresar" class="button gray small" onClick="cancelarCoseguro()">';
    echo '<br/><br/>';
    return;
}

// Si está lo anulamos
$c->getUltimaTx($nroauditoria);

if ($c->nroauditoria != '' && $c->tarea != 'COSEGURO-ANULA') {
    $ta = 1;
    if ($c->transaccion == 'BONOBILL') $ta = 2;
    ?>
    <FIELDSET>
        <LEGEND>Anular Coseguro</LEGEND>
        <?
        echo '<table>';
        echo '<tr>';
        echo '<td width="65%">';
        echo 'La Transacción ya fué generada el : ' . $c->fecha . '.';
        echo '</td>';
        echo '<td>';        
        echo '<input type="button" name="btnAnulaCoseguro" value="Anular" class="button gray small" onClick="anularCoseguro(' . $ta . ', nroauditcos.value)">';
        ?>
        <input type="button" name="btnCancelaCoseguro" value="Cancelar" class="button gray small" onClick="cancelarCoseguro()">    
        <?
        echo '</td>';
        echo '</tr>';
        echo '</table>';

        echo '</FIELDSET>';

        return;
    }
    ?>

    <!-- Si no está, lo generamos -->

    <FIELDSET>
        <LEGEND>Gestionar Coseguro</LEGEND>

        <table>
            <tr>
                <td>
                    <select name="listCos" id="listCos" width="50" style="width:120px" onchange="javascript: selectCos(this.value);">                        
                        <option value = '2'>Billetera Santa Fe</option>
                        <option value = '1'>Bono Impreso</option>;
                    </select>
                </td>

                <td width="10%">
                    Nro. de Transacción:                    
                </td>
                
                <td width="30%">                    
                    <input type="text" id="nroTx" style="width:140px" />
                </td>

                <td width="30%">                    
                    <input type="button" name="btnAplicaCoseguro" value="Aplicar" class="button gray small" onClick="aplicarCoseguro(listCos.value, nroauditcos.value)">
                    <input type="button" name="btnCancelaCoseguro" value="Cancelar" class="button gray small" onClick="cancelarCoseguro()">
                </td>

            </tr>
        </table>

    </FIELDSET>