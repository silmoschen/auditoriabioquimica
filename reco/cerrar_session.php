<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
session_destroy(); 

include_once('__routes.php');

echo '<input type="hidden" id="ruta" value=' . $ruta . '/>';
?>

<script>   
   var aleatorio = Math.random();
   var host = location.protocol + '//' + location.hostname + '/' + document.getElementById('ruta').value + 'login.php?aleatorio='+aleatorio;
   window.location.href = host;
</script>    