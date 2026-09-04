<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$host = "localhost";
$username = 'root';
$passw = 'VcOuPCuAdm5r';
$dbdatos = 'x020vm10_auditrqta';
$con = mysql_connect($host, $username, $passw);
//$con = mysql_connect($host, $user, $passw) or die ('Ocurrió un error al conectarse al servidor mysql');
mysql_select_db($dbdatos, $con);

?>
