<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

/*
include_once('__routes.php');
include_once("classes/cEntidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEntidad.php');
$obj = new cEntidad();
$obj->getObject(1);
*/
$_SESSION['susuario'] = $_REQUEST['usuario'];
$_SESSION['spass'] = $_REQUEST['pass'];
$_SESSION['snombre'] = 'CBLNSF'; //$obj->getNombre();
$_SESSION['susuarios'] = '';
$_REQUEST['trans'] = $_REQUEST['trans'];
$_SESSION["nivel"] = '1';

$url = "newhtml_menu.php?p111989=" . $_SESSION['susuario'];

//$url = 'login.php';

header('Location: ' . $url);


?>
