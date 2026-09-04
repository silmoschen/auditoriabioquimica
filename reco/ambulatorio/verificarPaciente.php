<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');

$afiliado = new cAfiliados();
$afiliado->getObject($_REQUEST['codos'], $_REQUEST['nrodoc']);
if ($afiliado) {
    printf($afiliado->getNrodoc() . '::' . $afiliado->getNombre());
} else {
    printf('ERROR');
}
?>
