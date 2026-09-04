<?

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuarios.php");

$obj = new cEfector();
$uss = new CUsuarios();
$u = new cUtiles();

$json = '{items:[';

$res = $obj->getEfectores(0, 10000, "");
while ($f = mysql_fetch_array($res)) {
    $json = $json . '{' .
            "'usuario':'" . $f['idprof'] . "'" . ',' .
            "'pass':'" . $f['pass'] . "'" . ',' .
            "'nombre':'" . $f['nombre'] . "'" . ',' .
	    "'nrocuit':'" . $f['nrocuit'] . "'" . ',' .	
            "'email':'" . $f['email'] . "'" .
            '},';
}

$res = $uss->getUsuarios();
while ($f = mysql_fetch_array($res)) {
    $json = $json . '{' .
            "'usuario':'" . $f['usuario'] . "'" . ',' .
            "'pass':'" . $f['pass'] . "'" . ',' .
            "'nombre':'" . $f['observacion'] . "'" . ',' .
	    "'nrocuit':" . "'" . '-' . "'" . ',' .
            "'email':'" . 'admin@admin.com' . "'" .
            '},';
}

$json = $json . ']}';

echo $json;
?>
