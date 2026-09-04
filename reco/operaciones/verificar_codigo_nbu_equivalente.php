<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$codigo = $_REQUEST['codigo'];
if (strlen(trim($codigo)) > 0) {  
  $obj = new cNBU();
  $r = $obj->getObjectEquivalente($codigo);
  if ($r != null) {
    echo $obj->getDescrip();
  } else {
    echo '*** Código de Determinación Inexistente ***';
  }
} else {
  echo '*** Código de Determinación Inexistente ***';
}

?>
