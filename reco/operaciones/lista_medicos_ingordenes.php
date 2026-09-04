<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codos  = $_REQUEST['codos'];
$nombre = $_REQUEST['nombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

// Verificamos si la obra social no utiliza el padrón de otra
$eq = new cEquivalenciaPadrones();
$found = $eq->getObject($codos);
if ($found) {
    // Si existe un código equivalente, modificamos la Obra Social
    $codos = $eq->getCodigo2();
}

$obj = new cMedicos;

$resultado = $obj->getMedicos($codos, $nombre);

?>

<select name="listMedicos" id="listMedicos" style="width:235px" onkeypress="javascript: if(ControlMedico(event)) { return true; }">

<?
while($fila=mysql_fetch_array($resultado)){
    echo '<option value =' . '"' . $fila['idprof'] . '"' . '>' . $fila['nombre'] .'</option>';
}

?>

</select>
