<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEquivalenciaNBU.php");

sleep(2);

$codos   = $_REQUEST['codos'];
$codigo1 = $_REQUEST['codigo1'];
$codigo2 = $_REQUEST['codigo2'];

$codigo  = new cEquivalenciaNBU();
$codigo->crear($codos, $codigo1, $codigo2);


?>