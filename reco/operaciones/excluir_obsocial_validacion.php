<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codos = $_REQUEST['codos'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$obsocial = new cObSocial();

$obsocial->procesarOsExcluida($codos);

?>
