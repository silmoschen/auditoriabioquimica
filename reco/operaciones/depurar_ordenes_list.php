<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set("max_execution_time",1000);

$codos   = $_REQUEST['codos'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoriaDep.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');

$dep = new cAuditoriaDep;
$obsocial = new cObsocial;
$utiles = new cUtiles;

$resultado = $dep->getList($codos);

?>

<div style="margin:auto;width:500px;text-align:center;">

<?php
    $b = true;
    while($fila=mysql_fetch_array($resultado)){
       if ($b) {
         echo '<h3>Historial de Depuraciones</h3>';    
         echo '<table>';
         echo '<tr>';
         echo '<td width="100px"><b>Anteriores a</b></td>';
         echo '<td align="left" width="250px"><b>Obra Social</b></td>';
         echo '<td><b>Fecha/Hora Op.</b></td>';
         echo '</tr>';
         echo '</table>';
         echo '<hr>';
         echo '<table>';
         $b = false;
        }
        $obsocial->getObject($codos);
        echo '<tr>';
        echo '<td width="100px">'. $utiles->getFechaDDMMAAAA($fila['desde']) .'</td>';        
        echo '<td align="left" width="250px">'.$obsocial->getNombre().'</td>';
        echo '<td align="left">'.$fila['fecha_hora'].'</td>';
        
        echo '</tr>';
    }
?>

</div>

