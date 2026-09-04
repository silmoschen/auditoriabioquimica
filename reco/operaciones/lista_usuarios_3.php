<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

$obj = new cEfector;

$resultado = $obj->getEfectores(0, 10000, '');

?>

<select name="auditor3" id="auditor2" width="80" style="width:300px">
  <?php  
  echo '<option value =' . '"' . '0' . '"' . '>'. '< Sin Usuario Asociado  >' .'</option>';
  while($fila=mysql_fetch_array($resultado)){
      echo '<option value =' . '"' . $fila['idprof'] . '"' . '>'. $fila['nombre'] . ' - Cód.: ' . $fila['idprof'] . '</option>';
  }
  ?>