<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuariosAuditoresTramos.php");

//variables POST
$id = $_REQUEST['id'];

if (strlen(trim($id)) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new CUsuariosAuditoresTramos();
  if ($obj->borrar($id)==true){        
  }else{
      echo "Error de eliminación ";
  }
}

?>