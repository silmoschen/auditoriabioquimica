<?php
//Configuracion de la conexion a base de datos
$bd_host = "localhost"; 
$bd_usuario = "root"; 
$bd_password = ""; 
$bd_base = "sanmartin_auditoria"; 

$bd_usuario = "sanmartin"; 
$bd_password = "Jakarta89"; 
$bd_base = "sanmartin_auditoria";

$con = mysql_connect($bd_host, $bd_usuario, $bd_password); 
mysql_select_db($bd_base, $con);

?>