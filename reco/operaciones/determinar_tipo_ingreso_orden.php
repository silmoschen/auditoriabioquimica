<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$codos = $_REQUEST['codos'];
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");

$obsocial = new cObsocial();
$obsocial->getObject($codos);
if ($obsocial) {    
    if ($obsocial->getIng_continuo() == 'S') {
      echo 'Inicializando ...';
    } else {
      echo 'Redireccionando ...';
    }
} else {
    echo 'NOTHING';
}

?>
