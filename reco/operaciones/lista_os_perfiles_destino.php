<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");

$obj = new cObsocial;

$resultado = $obj->getObrasSociales();

?>

<select name="listObsocial" id="listObsocialDestino" width="80" style="width:300px" onchange="javascript: if(ChequearOS()) {listaos.focus()}; return true;">
<option value = "000000">< Seleccione una Obra Social ></option>;
  <?php
  while($fila=mysql_fetch_array($resultado)){
      echo '<option value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 40) .'</option>';
  }
  ?>
</select>  