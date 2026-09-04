<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaCodigosNBU.php");

//variables POST
$codos   = $_REQUEST['codos'];
$codigo1 = $_REQUEST['codigo1'];

if (strlen(trim($codos)) > 0 and strlen(trim($codigo1)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  $obj=new cEquivalenciaCodigosNBU;
  if ($obj->borrar($codos, $codigo1)==true){
  }else{
	echo "Error al borrar";
  }
  sleep(2);
}

?>