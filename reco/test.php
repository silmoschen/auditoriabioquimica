<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$u = new cUtiles();

$s = '123-00';

echo $s . '    ' . str_replace('-', '', $s);

$o = new cObsocial();
$o->verificarRPC('121057');
echo '<hr/>' . $o->getParametro3();


$nombre = "DELL'ELCE";
$n = str_replace("'"," ", $nombre);        
echo $n;
 * 
 */

$c = '7180171001136';
$s = str_replace("800006", "", $c);

echo $c . ' ---  ' . $s;


?>
