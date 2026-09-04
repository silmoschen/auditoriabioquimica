<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cNBU.php");

$auditoria   = new cAuditoria;
$utiles      = new cUtiles;
$nbu         = new cNBU;

$autorizadas = $_REQUEST['autorizadas'];
$codos = $_REQUEST['codos'];

// separamos los códigos autorizados
$c = ''; $j = 0; $unidades = 0;
for ($i=0;$i<500;$i++) {
  $c = substr($autorizadas, $j, 6);
  $j = $j + 6;    
  if ($c == 0) {break;}
  
  $nbu->getObject($c);
  $u = $nbu->getUnidadObsocial($codos);  // 20/07/2026
  
  $unidades = $unidades + $u;
}

echo $unidades;

?>