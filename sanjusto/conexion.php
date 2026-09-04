<?php
//Configuracion de la conexion a base de datos
$bd_host = "localhost"; 
$bd_usuario = "root"; 
$bd_password = ""; 
$bd_base = "auditoria_sanjusto"; 

$bd_usuario = "sanjustodb"; 
$bd_password = "centroBQ09"; 
$bd_base = "sanjustodb_centrobq";

$con = mysql_connect($bd_host, $bd_usuario, $bd_password); 
mysql_select_db($bd_base, $con);

?>