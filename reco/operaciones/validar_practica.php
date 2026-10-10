<?
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$codigo = $_REQUEST['codigo'];
if (strlen(trim($codigo)) > 0) {
    $obj = new cNBU();
    $r = $obj->getObject($codigo);
    if ($r != null) {
        echo ltrim($obj->getDescrip());
    } else {
        echo '*** Práctica Inexistente ***';
    }
} else {
    echo '*** Práctica Inexistente ***';
}
?>
