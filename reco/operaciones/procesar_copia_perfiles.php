<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cModelos.php');

$modelo = new cModelos;

$codos_de = $_REQUEST['de'];
$codos_a  = $_REQUEST['a'];

 for($i = 1; $i <= 1000; $i += 1) {
     $r = 'orden' . $i;
     
     $req = $_REQUEST[$r];  // obtenemos el id correspondiente

     if (strlen($req) > 0) {
        $modelo->copiar($codos_de, $codos_a, $req);          
     }
 }

 echo 'Trabajo Realizado :: ' . $i . ' Items Copiados.';

 sleep(2);

?>
