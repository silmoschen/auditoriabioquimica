<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

$_SESSION['susuario'] = $_REQUEST['usuario'];
$_SESSION['spass'] = $_REQUEST['pass'];
$_SESSION['snombre'] = $_REQUEST['nombre'];
$_REQUEST['trans'] = $_REQUEST['trans'];

$url = "ingresoordenesusuario.php";

header('Location: ' . $url);

?>
