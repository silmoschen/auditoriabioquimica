<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include('FTP_FUNC.php');

$host    = $_REQUEST[host];
$hostftp = $_REQUEST[hostftp];
$us      = $_REQUEST[us];
$ps      = $_REQUEST[ps];



ConectarFTP1($hostftp, $us, $ps);
DescargarArchivo('cab_auditoria_mod.txt', 'cab_auditoria_mod.txt');
DescargarArchivo('det_auditoria_mod.txt', 'det_auditoria_mod.txt');

?>
