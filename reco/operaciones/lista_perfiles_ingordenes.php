<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codos = $_REQUEST['codos'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

// Verificamos si la obra social no utiliza el padrón de otra
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codos = $eq->getCodigo2();
}

$obj = new cModelos;

$resultado = $obj->getModelos($codos);
?>
<select name="listPerfil" id="listPerfil" style="width:235px" onkeypress="javascript: if(verificarPerfil(event)) {fepedido.focus()}; return true">
<?
while ($fila = mysql_fetch_array($resultado)) {
    if ($fila['inhabilitado'] != 'S') {
        echo '<option value =' . '"' . $fila['oms_cod'] . '"' . '>' . substr($fila['descrip'], 0, 40) . '</option>';
    }
}
?>

</select>