<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");

//variables POST
$codigo = $_REQUEST['codigo'];
$periodo = $_REQUEST['periodo'];

sleep(2);
$obj = new cNBU;
$obj->borrarUnidadesNBU($codigo, $periodo);

?>