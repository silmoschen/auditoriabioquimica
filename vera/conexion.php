<?php
//Configuracion de la conexion a base de datos
$host = "localhost";
$username = 'root';
$passw = 'VcOuPCuAdm5r';
$dbdatos = 'x020vm10_auditvera';
$con = mysql_connect($host, $username, $passw);
//$con = mysql_connect($host, $user, $passw) or die ('Ocurrió un error al conectarse al servidor mysql');
mysql_select_db($dbdatos, $con);

?>