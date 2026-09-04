<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTipoControl.php");

//variables POST
$descrip=$_REQUEST['descrip'];

if (strlen(trim($_REQUEST[descrip])) > 0) {  
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new cTipoControl;
  if ($obj->crear($descrip)==true){
	//echo "$descrip - Grabado Correctamente";        
        
  }else{
	echo "Error de grabacion";
  }
}

?>