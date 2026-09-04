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


echo $us;
ConectarFTP1($hostftp, $us, $ps);
SubirArchivo($host . '/operaciones/cab_auditoria.txt', 'cab_auditoria.txt');
SubirArchivo($host . '/operaciones/det_auditoria.txt', 'det_auditoria.txt');
SubirArchivo($host . '/operaciones/pac_auditoria.txt', 'pac_auditoria.txt');

?>
