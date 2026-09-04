<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$usuario = $_REQUEST['usuario'];
$pass    = $_REQUEST['pass'];
$trans   = $_REQUEST['trans'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

$obj = new cEfector();

$obj->cambiarpass($usuario, $pass);

echo '<b>Hecho !</b>';

sleep(2);

?>
