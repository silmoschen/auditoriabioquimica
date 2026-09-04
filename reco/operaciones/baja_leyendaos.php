<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
$obsocial = new cObSocial();

//variables POST
$codos   = $_REQUEST['codos'];

if (strlen(trim($codos)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  if ($obsocial->borrarLeyenda($codos)==true){
  }else{
      echo "Error al borrar " . $obj->getSQL();
  }
}

?>