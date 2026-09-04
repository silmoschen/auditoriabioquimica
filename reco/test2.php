<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$n = '800006098829903100000852';
$ndoc = $n;
if (substr($n, 0, 7) == '8000060') $ndoc = substr ($n, 7, 11);
echo $ndoc;
?>

