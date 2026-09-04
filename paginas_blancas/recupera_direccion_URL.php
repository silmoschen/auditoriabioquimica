<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$archivo = 'URL.txt';

$c = 0;

$linea_nombre = 171;
$inicio_nombre = 0;
$fin_nombre = 400;

$ar = fopen($archivo, 'r');

while (!feof($ar)) {
    $linea1 = fgets($ar);

    $c++;

    if ($c == $linea_nombre) {
        echo substr(trim($linea1), $inicio_nombre, $fin_nombre);
    }
}
?>
