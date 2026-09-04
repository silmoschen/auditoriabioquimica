<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');

$efector = new cEfector();
$efector->getObject($_REQUEST['codigo']);
if ($efector) {
    printf($efector->getCodigo() . '::' . $efector->getNombre());
} else {
    printf('ERROR');
}
?>
