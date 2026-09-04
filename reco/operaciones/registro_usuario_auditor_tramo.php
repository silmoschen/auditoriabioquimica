<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuariosAuditoresTramos.php");

//variables POST
$desde    = $_REQUEST['desde'];
$hasta    = $_REQUEST['hasta'];
$auditor1 = $_REQUEST['auditor1'];
$auditor2 = $_REQUEST['auditor2'];

$l = true;

if (strlen(trim($desde)) < 1) {
    echo 'Error Desde ';
    $l = false;
}

if (strlen(trim($hasta)) < 1) {
    echo ' Error Hasta ';
    $l = false;
}

if (strlen(trim($desde)) > 0 and strlen(trim($hasta)) > 0 and $l) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear
  sleep(2);
  $obj=new CUsuariosAuditoresTramos();
  if ($obj->crear($desde, $hasta, $auditor1, $auditor2)==true){
        
  }else{
      echo "Error de grabacion ";
  }
}

?>