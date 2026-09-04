<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$codigo = $_REQUEST['codigo'];
if (strlen(trim($codigo)) > 0) {
    $obj = new cNBU();
    $r = $obj->getObject($codigo);
    if ($r != null) {        
        if ($obj->getInactivo() == 1) {
            echo '*** El Código ' . $codigo . ' ' . substr($obj->getDescrip(), 0, 25) . ' está Inactivo ***';
        } else {
            echo $obj->getDescrip();
        }
    } else {
        echo '*** Código de Determinación Inexistente ***';
    }
} else {
    echo '*** Código de Determinación Inexistente ***';
}
?>