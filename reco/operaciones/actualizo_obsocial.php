<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");

session_start();

if ($_SESSION["susuario"] != 'administ') {
    exit;
}

//variables POST
$codigo = $_REQUEST['codigo'];
$nombre = $_REQUEST['nombre'];
$factnbu = $_REQUEST['factnbu'];
$bonos = $_REQUEST['bonos'];
$medicos_cab = $_REQUEST['medicos_cab'];
$soportemag = $_REQUEST['soportemag'];
$u = $_REQUEST['us'];
$p = $_REQUEST['pa'];
$derivacion = $_REQUEST['derivacion'];
$inc_leyenda = $_REQUEST['inc_leyenda'];
$leyenda = $_REQUEST['leyenda'];
$ing_continuo = $_REQUEST['ing_continuo'];
$incluye_ab = $_REQUEST['incluye_ab'];
$aut_directa = $_REQUEST['aut_directa'];
$coseguro = $_REQUEST['coseguro'];
$coseguro_mf = $_REQUEST['coseguro_mf'];
$alta_paciente = $_REQUEST['alta_paciente'];
$nivel3 = $_REQUEST['nivel3'];
$tope_anual = $_REQUEST['tope_anual'];
$practicas_rechazadas = $_REQUEST['practicas_rechazadas'];
$orden_completa = $_REQUEST['orden_completa'];
$inactiva = $_REQUEST['inactiva'];
$gen_nroautorizacion = $_REQUEST['gen_nroautorizacion'];
$nivel2 = $_REQUEST['nivel2'];

if (strlen(trim($codigo)) != 6) {
    echo 'El Código de la OS es Incorrecto  ';
}

if (strlen(trim($nombre)) == 0) {
    echo 'El Nombre de la OS es Incorrecto  ';
}

$l = false;
if ($factnbu == 'S' or $factnbu == 'N' or $factnbu == 's' or $factnbu == 'n') {
    if ($factnbu == 's') {
        $factnbu == 'S';
    }
    if ($factnbu == 'n') {
        $factnbu == 'N';
    }
    $l = true;
} else {
    echo 'Fact. NBU puede ser N ó S';
}

$l = false;
if ($soportemag == 'S' or $soportemag == 'N' or $soportemag == 's' or $soportemag == 'n') {
    if ($soportemag == 's') {
        $soportemag == 'S';
    }
    if ($soportemag == 'n') {
        $soportemag == 'N';
    }
    $l = true;
} else {
    echo 'Exportar Datos puede ser N ó S';
}

$l = false;
if ($derivacion == 'S' or $derivacion == 'N' or $derivacion == 's' or $derivacion == 'n') {
    if ($derivacion == 's') {
        $derivacion == 'S';
    }
    if ($derivacion == 'n') {
        $derivacion == 'N';
    }
    $l = true;
} else {
    echo 'Las Opciones de Derivacion ser N ó S';
}

if (strlen(trim($codigo)) == 6 and strlen(trim($nombre)) > 0 and $l) {
    //creamos el objeto $objempleados
    //y usamos su m�todo crear
    $obj = new cObsocial;
    if ($obj->actualizar($codigo, $nombre, strtoupper($factnbu), $bonos, $medicos_cab, strtoupper($soportemag), $u, $p, $derivacion, $inc_leyenda, $leyenda, $ing_continuo, $incluye_ab, $aut_directa, $coseguro, $alta_paciente, $coseguro_mf, $nivel3, $tope_anual, $practicas_rechazadas, $orden_completa, $inactiva, $gen_nroautorizacion, $nivel2) == true) {
        
    } else {
        echo "Error de grabacion  - " . $obj->getSQL();
    }
    sleep(2);
}
?>