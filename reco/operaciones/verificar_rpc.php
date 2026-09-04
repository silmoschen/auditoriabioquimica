<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_REQUEST['codos'];

$obsocial = new cObSocial();          // Obras Sociales

$s = false;
if ($obsocial->verificarRPC($codos))
    $s = true;

if ($obsocial->_parametro1 == 'padrononly' || $obsocial->_parametro2 == 'padrononly' || $obsocial->_parametro9 == 'padrononly') {
    printf('M');
    exit;
}

// 08/03/2023 - Para los casos de TOKEN
if ($s) {
    if ($obsocial->_parametro8 == 'token') {
        printf('ST');
        exit;
    }
}

if ($s)
    printf('S');
else
    printf('N');
?>
