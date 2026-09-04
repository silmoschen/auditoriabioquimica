<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

//variables POST
$codigo=$_REQUEST['codigo'];
$baja=$_REQUEST['baja'];

$obj=new cEfector;
$obj->bajaEfector($codigo, $baja);

?>