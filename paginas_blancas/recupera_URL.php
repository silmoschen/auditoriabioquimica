<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$area = $_REQUEST['ddn'];                //'03482';
$prefijo = $_REQUEST['prefijo'];         //'42';
$sufijo = $_REQUEST['telefono'];         //'4757';

$archivo = 'URL.txt';
$ar = fopen($archivo, 'w');

$url = "http://www.paginasblancas.com.ar/BuscarTelefonica.action?tipoBusqueda=radio2&apellido=&telefono.area=$area&telefono.prefijo=$prefijo&telefono.sufijo=$sufijo&domicilio.calle=&domicilio.altura=&provinciasId=0&localidad.descripcion=&x=16&y=20";

$url = "http://www.paginasblancas.com.ar/es-ar/telefono/03482424757";

echo $url . '<hr/>';

$url_content = '';
$file = @fopen($url, 'r');
if ($file) {
    while (!feof($file)) {
        $url_content .= @ fgets($file, 4096);
    }
    fclose($file);
}


fwrite($ar, $url_content);

fclose($ar);

echo $url_content;

?>
