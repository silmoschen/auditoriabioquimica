<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cModelos.php");

//variables POST
$codos  = $_REQUEST['codos'];
$id     = $_REQUEST['idcontrol'];
$codigo = $_REQUEST['codigo'];

if (strlen(trim($codos)) > 0 and strlen(trim($id)) > 0 and strlen(trim($codigo)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cModelos;
  if ($obj->borrar($codos, $id, $codigo)==true){
	//echo "$id - Borrado Correctamente";

  }else{
	echo "Error al borrar";
  }
}

?>