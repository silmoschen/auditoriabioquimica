<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

$query = "SELECT * FROM entidad";
$resultado=mysql_query($query);
while($fila=mysql_fetch_array($resultado)){
    echo '<h2>' . $fila['nombre'] . '</h2>';
    echo '<b>' . $fila['direccion'] . '</b><br>';
    echo '<b>Tel.: ' . $fila['telefono'] . '</b><br>';
    echo 'Email:' . $fila['email'];
}

?>
