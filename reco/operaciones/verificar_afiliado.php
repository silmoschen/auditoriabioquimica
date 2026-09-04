<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");

//variables POST
$codos       = $_REQUEST['codos'];
$nrodoc      = $_REQUEST['nrodoc'];

if (strlen(trim($_REQUEST[codos])) > 0 and strlen(trim($_REQUEST[nrodoc])) > 0) {
  //creamos el objeto $objempleados
  //y usamos su m�todo crear  
  $obj=new cAfiliados;
  $obj->getObject($codos, $nrodoc);
  if (strlen($obj->getCodigo()) > 0) {
      echo $obj->getNombre();
  }else{
      echo 'El Documento ' . $nrodoc . ' es Inexistente';
  }
} else {
    echo "Afiliado Inexistente ...!";
}

?>