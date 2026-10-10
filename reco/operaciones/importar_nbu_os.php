<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$nbu = new cNBU;
$nomenclador = $nbu->importarNbuObrasSociales();
$unidades = $nbu->importarNbuObrasSocialesUnidades();

if ($nomenclador == 'OK' && $unidades == 'OK') {
    echo 'OK';
} else {
    echo $nomenclador . "\n" . $unidades;
}

?>
