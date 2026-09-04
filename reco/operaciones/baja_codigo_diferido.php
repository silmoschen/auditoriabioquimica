<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNbuCodigosDif.php');

$codos = $_REQUEST['codos'];
$codigo = $_REQUEST['codigo'];

$c = new cNbuCodigosDif();

if (strlen($codos) > 0 and strlen($codigo) > 0) {
  $c->borrar($codos, $codigo);
}

sleep(2);

?>
