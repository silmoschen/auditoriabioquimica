<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$archivo = 'URL.txt';

$c = 0;

$linea_nombre = 165;
$inicio_nombre = 17;
$fin_nombre = 22;

$ar = fopen($archivo, 'r');

while (!feof($ar)) {
    $linea1 = fgets($ar);

    $c++;

    if ($c == $linea_nombre) {
        echo substr(trim($linea1), $inicio_nombre, strlen(trim($linea1)) - $fin_nombre);
    }
}
?>