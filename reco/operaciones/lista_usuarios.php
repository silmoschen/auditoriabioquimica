<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cLogs.php");

$obj = new cLogs;

$resultado = $obj->getUsuarios();

?>

<select name="listUsuarios" id="listUsuarios" width="80" style="width:300px" onchange="javascript: if(ConsultarLogs()) {desde.focus()}" onclick="javascript: if(ConsultarLogs()) {desde.focus()}; return true;">
  <?php
  echo '<option value =' . '"' . '0' . '"' . '>'. '< Todos los Usuarios >' .'</option>';
  while($fila=mysql_fetch_array($resultado)){
      echo '<option value =' . '"' . $fila['usuario'] . '"' . '>'. $fila['usuario'] .'</option>';
  }
  ?>
