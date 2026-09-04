<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codos  = $_REQUEST['codos'];
$nrodoc = $_REQUEST['nrodoc'];


include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cAfiliados.php');

$obj = new cAfiliados;
$obj->getObject($codos, $nrodoc);

echo $obj->getNombre();

?>