<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$nbu = new cNBU();
$nbu->getObject($_REQUEST['codigo']);
if ($nbu) {
    printf($nbu->getCodigo() . '::' . $nbu->getDescrip());
} else {
    printf('ERROR');
}
?>
