<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$codos = $_REQUEST['codos'];

$obsocial = new cObSocial();          // Obras Sociales

$obj = $obsocial->getObSocialReglaJSON($codos);

echo $obj;    

?>
