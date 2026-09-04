<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$idprof = $_REQUEST['idprof'];
$codos = $_REQUEST['codos'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfectoresExcluidos.php');

$efexcluido = new cEfectoresExcluidos;

$efexcluido->procesar($codos, $idprof);

?>
