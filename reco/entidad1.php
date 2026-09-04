<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

$query = "SELECT nombre FROM entidad";
$resultado=mysql_query($query);
while($fila=mysql_fetch_array($resultado)){
    echo '<b>' . $fila['nombre'] . '</b>';
}

?>
