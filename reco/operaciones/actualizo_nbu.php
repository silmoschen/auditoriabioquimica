<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");

//variables POST
$codigo  = $_REQUEST['codigo'];
$descrip = $_REQUEST['descrip'];
$unidad  = $_REQUEST['unidad'];
$tramo   = $_REQUEST['tramo'];
$estado  = $_REQUEST['estado'];
$nivel   = $_REQUEST['nivel'];

if (strlen(trim($codigo)) != 6) {
    echo 'Código Incorrecto';
}
if (strlen(trim($descrip)) == 0) {
    echo ' Descripción Incorrecta';
}

if (strlen(trim($unidad)) == 0) {
    echo ' Unidad Incorrecta';
}

if (strlen(trim($tramo)) == 0) {
    echo ' El tramo no puede ser 0';
}

if (strlen(trim($codigo)) > 0 and strlen($descrip) > 0 and strlen($unidad) > 0 and strlen($tramo) > 0)  {
  //creamos el objeto $objempleados
  //y usamos su metodo crear
  sleep(2);
  $obj=new cNBU;
  if ($obj->actualizar($codigo, $descrip, $unidad, $tramo, $estado, $nivel)==true){
    //echo "$descrip - Grabado Correctamente";
  }else{    
    echo "Error de grabacion " . $obj->getSQL();
  }  
}

?>