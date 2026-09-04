<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cArancelesNBU.php");

//variables POST
$codos   = $_REQUEST['codos'];
$periodo = $_REQUEST['periodo'];

if (strlen(trim($codos)) > 0 and strlen(trim($periodo)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cArancelNBU;
  if ($obj->borrar($codos, $periodo)==true){
	//echo "$id - Borrado Correctamente";

  }else{
      echo "Error al borrar " . $obj->getSQL();
  }
}

?>