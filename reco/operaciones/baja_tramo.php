<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTramos.php");

//variables POST
$codos = $_REQUEST['codos'];
$id = $_REQUEST['id'];

if (strlen(trim($id)) > 0 and strlen(trim($codos)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cTramos;
  if ($obj->borrar($codos, $id)==true){
	//echo "$id - Borrado Correctamente";

  }else{
      echo "Error al borrar - " . $obj->getSQL();
  }
}

?>