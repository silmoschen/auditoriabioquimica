<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicosCab.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$codos  = $_REQUEST['codos'];
$nombre = $_REQUEST['nombre'];

// Verificamos si la obra social no utiliza el padrón de otra
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codos = $eq->getCodigo2();
}

$obj = new cMedicosCab;

$resultado = $obj->getMedicos($codos, $nombre);

?>

<select name="listMedicosCabecera" id="listMedicosCabecera" style="width:346px" onchange="javascript: MedicoCabeceraChange()" onkeypress="javascript: if(ControlMedicoCabecera(event, listMedicosCabecera.value)) {RegistrarOrdenAuditoria.focus()}; return true">

<?
while($fila=mysql_fetch_array($resultado)){
    echo '<option value =' . '"' . $fila['idprof'] . '"' . '>' . $fila['nombre'] .'</option>';
}

?>

</select>
