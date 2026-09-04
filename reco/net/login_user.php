<?

$a = $_REQUEST['user'];
$u = $_REQUEST['pass'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuarios.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cLogs.php");

$obj=new cEfector;
$us=new CUsuarios;
$log = new cLogs();

$loginOK = false;

if ($_SESSION['susuario'] !=  $a && $_SESSION['susuario'] > '') {
    echo '<font color="#FF0000">Se ha Producido un Error al Iniciar la Sessión.</font><br>';
    echo 'Error :: La sessión ' . $_SESSION['susuario'] . ' está actualmente activa';
} else {

    if ($obj->verificarUsuario($a, $u)) {
        $loginOK = true;
        echo "1::" . $obj->getNombre();
    } else {
        $loginOK = false;
    }

    if ($loginOK == false) {
        if ($us->verificarUsuario($a, $u)) {
            echo "2::" . $a;
            $loginOK = true;
        } else {
            $loginOK = false;
        }
    }

    if ($loginOK == false) {
        echo 'Login Incorrecto';
    } else {
        $log->crear($a);
    }
}

?>
