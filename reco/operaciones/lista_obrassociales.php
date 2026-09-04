<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_REQUEST['codos'];

$obj = new cObsocial;

$resultado = $obj->getObrasSociales();

?>

<select name="listObsocial" id="listObsocial" onchange="cambiarOS()" style="width:250px">
<?php
  while($fila=mysql_fetch_array($resultado)){
      if ($codos == $fila['codos']) {
        echo '<option selected value =' . '"' . $fila['codos'] . '"' . '>'.substr($fila['nombre'], 0, 35) .'</option>';
      } else {
        echo '<option value =' . '"' . $fila['codos'] . '"' . '>'.substr($fila['nombre'], 0, 35) .'</option>';
      }
  }
  ?>
</select>  