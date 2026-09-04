<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cTipoDoc.php");

//variables POST
$codigo=$_REQUEST['codigo'];

if (strlen(trim($codigo)) > 0) {
  // Chequeamos integridad
  $afiliado=new cAfiliados;
  if ($afiliado->verificarTipoDoc($codigo)) {
      echo "<font color='#FF0000'>*** El Tipo de Documento " . $codigo . " tiene Afiliados Afectados - Baja Denegada ***</font>";
      sleep(2);
  } else {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    sleep(2);
    $obj=new cTipoDoc;
    if ($obj->borrar($codigo)==true){
    }else{
      echo "Error al borrar " . $obj->getSQL();
    }
  }
}

?>