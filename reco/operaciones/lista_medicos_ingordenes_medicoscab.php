<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codos  = $_REQUEST['codos'];
$nombre = $_REQUEST['nombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');

$obj = new cMedicos;

$resultado = $obj->getMedicos($codos, $nombre);

?>

<select name="listMedicos" id="listMedicos" style="width:230px" disabled="true" onkeypress="javascript: if(ControlMedico(event)) {capitas.focus()}; return true">

<?
while($fila=mysql_fetch_array($resultado)){
    echo '<option value =' . '"' . $fila['idprof'] . '"' . '>' . $fila['nombre'] .'</option>';
}

?>

</select>
