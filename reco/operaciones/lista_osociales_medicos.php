<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaPadrones.php');

$obj = new cObsocial;
$eq = new cEquivalenciaPadrones();

$resultado = $obj->getObrasSociales();

?>

<select name="listObsocial" id="listObsocial" width="80" style="width:318px" onchange="javascript: if(ConsultarMedicos()) {listObsocial.focus()}; return true">
  <?php
  while($fila=mysql_fetch_array($resultado)){
      // Solo tomamos aquellos códigos que no contemplan otro padrón
      $eq->getObject($fila['codos']);
      if ($eq->getCodigo1() != $fila['codos']) {
        echo '<option value =' . '"' . $fila['codos'] . '"' . '>'.substr($fila['nombre'], 0, 40) .'</option>';
      }
  }
  ?>
</select>  