<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');

$obj = new cEfector;

$resultado = $obj->getProfesionales();

?>

<select name="listEfectores" id="listEfectores"  style="width:300px" onchange="javascript: if(CambiarEfector()) {listObsocial.focus()}" onkeypress="javascript: if(ControlEfector(event)) {listObsocial.focus()}; return true">
  <?php
  //echo '<option value =' . '"' . '0000000' . '"' . '>' . '< Todos los Efectores >' .'</option>';
  while($fila=mysql_fetch_array($resultado)){
      if ($fila['nombre'] != 'Administrador') {
        echo '<option value =' . '"' . $fila['idprof'] . '"' . '>' . $fila['nombre'] .'</option>';
      }
  }
  ?>
</select>  