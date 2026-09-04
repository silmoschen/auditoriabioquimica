<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuarios.php");

$obj = new cUsuarios;

$resultado = $obj->getUsuarios();

?>

<select name="auditor1" id="auditor1" width="80" style="width:300px">
  <?php  
  while($fila=mysql_fetch_array($resultado)){
      echo '<option value =' . '"' . $fila['ID'] . '"' . '>'. $fila['usuario'] .'</option>';
  }
  ?>
