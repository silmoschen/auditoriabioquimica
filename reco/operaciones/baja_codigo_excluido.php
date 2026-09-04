<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cCodigosExcluidos.php");

//variables POST
$codigo1 = $_REQUEST['codigo1'];
$codigo2 = $_REQUEST['codigo2'];

if (strlen(trim($codigo1)) > 0 and strlen(trim($codigo2)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  $obj=new cCodigosExcluidos;
  if ($obj->borrar($codigo1, $codigo2)==true){

  }else{
	echo "Error al borrar";
  }
  sleep(2);
}

?>