<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_REQUEST['codos'];
$descrip = $_REQUEST['descrip'];

$c = new cObSocial();

if (strlen($codos) > 0 and strlen($descrip) > 0) {
  $c->registrarLeyenda($codos, $descrip);
}

sleep(2);

?>
