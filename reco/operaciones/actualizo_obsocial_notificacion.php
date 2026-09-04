<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObsocialNotificaciones.php');

$codos = $_REQUEST['codos'];
$baja = $_REQUEST['baja'];
$m1 = $_REQUEST['m1'];
$m2 = $_REQUEST['m2'];
$m3 = $_REQUEST['m3'];
$m4 = $_REQUEST['m4'];
$m5 = $_REQUEST['m5'];
$m6 = $_REQUEST['m6'];
$m7 = $_REQUEST['m7'];


$c = new cObsocialNotificaciones();

if (strlen($codos) > 0 and strlen($baja) > 0) {
  $c->crear($codos, $baja, $m1, $m2, $m3, $m4, $m5, $m6, $m7);
}

sleep(2);

?>
