<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$nro = '21104220190826050242395903251';
$rnro = rand(1, 999999999);
$h = getdate();
$hora = $h[hours]. $h[minutes] . $h[seconds];

echo $hora . '<br/>';

if ($rnro < 0) $rnro = $rnro * (-1);

echo $nro . '<br/>';

echo substr($nro, 0, 14) . STR_PAD($rnro, 9, '0', STR_PAD_LEFT) . $hora . '<br/>';

//echo rand(100000000000000, 999999999999999);

//$random = ((rand()*(0.002/getrandmax()))-0.001);
        
//echo $random;
?>

