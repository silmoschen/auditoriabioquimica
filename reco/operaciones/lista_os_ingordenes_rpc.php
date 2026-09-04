<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$obj = new cObsocial;

$resultado = $obj->getObrasSociales();

?>

<select name="listObsocialrpc" id="listObsocialrpc" width="80" style="width:234px">
  <?php
  while($fila=mysql_fetch_array($resultado)){
      $r = 'N';
      if ($obj->verificarRPC($fila['codos'])) $r = 'S';
      echo '<option value =' . '"' . $fila['codos'] . '"' . '>'. $r .'</option>';
  }
  ?>
</select>  