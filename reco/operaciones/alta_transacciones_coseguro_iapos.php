<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTransaccionesCoseguroIapos.php');

$nroauditoria = $_REQUEST['nroauditoria'];
$transaccion = $_REQUEST['transaccion'];
$tarea = $_REQUEST['tarea'];
$fecha = $_REQUEST['fecha'];
$transiapos = $_REQUEST['transiapos'];
$mensaje = $_REQUEST['mensaje'];
$ref1 = $_REQUEST['ref1'];
$ref2 = $_REQUEST['ref2'];
$ref3 = $_REQUEST['ref3'];
$ref4 = $_REQUEST['ref4'];

$c = new cTransaccionesCoseguroIapos();

if (strlen($nroauditoria) > 0 and strlen($transaccion) > 0 and strlen($tarea) > 0) {
  $c->crear($nroauditoria, $transaccion, $tarea, $fecha, $transiapos, $mensaje, $ref1, $ref2, $ref3, $ref4);
}

?>
