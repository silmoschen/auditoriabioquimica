<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time",1000);

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cWsRespuestas.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$obj = new cWsRespuestas;
$utiles = new cUtiles;

$resultado = $obj->getHistorial();

?>

<div style="margin:auto;width:500px;text-align:center;">

<?php
    $b = true;
    while($fila=mysql_fetch_array($resultado)){
       if ($b) {
         echo '<h3>Historial de Depuraciones</h3>';    
         echo '<table>';
         echo '<tr>';
         echo '<td width="100px"><b>Desde</b></td>';
         echo '<td align="left" width="250px"><b>Hasta</b></td>';
         echo '<td><b>Fecha/Hora Op.</b></td>';
         echo '</tr>';
         echo '</table>';
         echo '<hr>';
         echo '<table>';
         $b = false;
        }
        
        echo '<tr>';
        echo '<td width="100px">'. $utiles->getFechaDDMMAAAA($fila['desde']) .'</td>';        
        echo '<td align="left" width="250px">'. $utiles->getFechaDDMMAAAA($fila['hasta']) .'</td>';
        echo '<td align="left">'.$fila['fecha_hora'].'</td>';
        
        echo '</tr>';
    }
?>

</div>

