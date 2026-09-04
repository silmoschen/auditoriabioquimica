<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTipoControl.php");

//variables POST
$id=$_REQUEST['idcontrol'];

if (strlen(trim($id)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cTipoControl;
  if ($obj->borrar($id)==true){
	//echo "$id - Borrado Correctamente";

  }else{
	echo "Error al borrar";
  }
}

?>