<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cCodigosExcluidos.php");

$codigo1 = $_REQUEST['codigo1'];
$codigo2 = $_REQUEST['codigo2'];

$codigo  = new cCodigosExcluidos;
$codigo->crear($codigo1, $codigo2);

?>




