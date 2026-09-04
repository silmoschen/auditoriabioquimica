<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');

$codos = $_REQUEST['codos'];
$idcontrol = $_REQUEST['idcontrol'];
$estado = $_REQUEST['estado'];

$obj = new cModelos;

$obj->autorizacionDiferida($codos, $idcontrol, $estado);

sleep(2);

?>
