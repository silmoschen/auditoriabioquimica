<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function TildesHtml($cadena) 
{ 
    return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ"),
                                     array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;",
                                                "&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","N"), $cadena);     
}

echo TildesHtml("NUÑEZ");
?>
