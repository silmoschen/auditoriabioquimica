<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
$log = new cWsRespuestas();

$desde = $_REQUEST['desde'];
$hasta = $_REQUEST['hasta'];

$i = $log->depurar($desde, $hasta);

echo "Trabajo Realizado ...! " . $i . " Items Procesados.";

?>
