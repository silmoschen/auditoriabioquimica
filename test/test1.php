<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$c = '661035';
$diferida = 'N';

 if (substr($c, 0, 3) != '660')
                            $diferida = 'S';

echo $diferida;

?>

