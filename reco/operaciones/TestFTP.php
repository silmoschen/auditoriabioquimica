<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('FTP_FUNC.php');

ConectarFTP();
SubirArchivo('121069.txt', '121069.txt');
echo 'realizado';
?>
