<?

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosRestringidos.php');

$ccodrest = new cCodigosRestringidos();

// Verificamos que no este restringido
$__cod = $ccodrest->getCodigoRestringido($codos, $codigo);
if ($__cod) {
    echo '*** El Código ' . $codigo . ' esta Restringido ***';
    return;
}

$codigo = $_REQUEST['codigo'];
if (strlen(trim($codigo)) > 0) {
    $obj = new cNBU();
    $r = $obj->getObject($codigo);
    if ($r != null) {
        echo $obj->getDescrip();
    } else {
        echo '*** Código de Determinación Inexistente ***';
    }
}
?>