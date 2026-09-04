<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");

$desde     = $_REQUEST['desde'];
$hasta     = $_REQUEST['hasta'];
$codos     = $_REQUEST['codos'];

$obj = new cObsocial;
$auditoria = new cAuditoria;

$resultado = $obj->getObrasSociales();

?>

<select name="listObsocial" id="listObsocial" width="80" style="width:300px" onchange="javascript: if(CambiarOS()) {desde.focus()}" onclick="javascript: if(CambiarOSS()) {desde.focus()}; return true;">
    <?php
    while ($fila = mysql_fetch_array($resultado)) {
        $obj->verificarRPC($fila['codos']);
        if (($obj->_reglaNegocio < 1 || $obj->_parametro1 == 'padrononly') && ($fila['aut_directa'] == 'N'))
        {
            if ($fila['codos'] == $codos) {
              echo '<option selected value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 40) . ' => '. $auditoria->countOrdenesPendientesObraSocial($fila['codos'], $desde, $hasta) . '</option>';
            } else {
              echo '<option value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 40) . ' => ' . $auditoria->countOrdenesPendientesObraSocial($fila['codos'], $desde, $hasta) . '</option>';    
            }
        }
    }
    ?>
</select>  