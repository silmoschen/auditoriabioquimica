<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNbuFederada.php");

//variables POST
$codigo  = $_REQUEST['codigo'];
$descrip = $_REQUEST['descrip'];
$nivel  = $_REQUEST['nivel'];
$tope_anual   = $_REQUEST['tope_anual'];

if (strlen(trim($codigo)) != 6) {
    echo 'Código Incorrecto';
}

if (strlen(trim($nivel)) == 0) {
    echo ' Nivel Incorrecta';
}

if (strlen(trim($tope_anual)) == 0) {
    echo ' El tope no puede ser nulo';
}

if (strlen(trim($codigo)) > 0 and strlen($nivel) > 0 and strlen($tope_anual) > 0)  {
  //creamos el objeto $objempleados
  //y usamos su metodo crear
  sleep(2);
  $obj=new cNbuFederada;
  if ($obj->actualizar($codigo, $descrip, $nivel, $tope_anual)==true){
    //echo "$descrip - Grabado Correctamente";
  }else{    
    echo "Error de grabacion " . $obj->getSQL();
  }  
}

?>