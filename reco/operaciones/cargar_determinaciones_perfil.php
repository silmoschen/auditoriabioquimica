<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cModelos.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cItemsAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAuditoria.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cAfiliados.php");


$auditoria   = new cAuditoria;
$utiles      = new cUtiles;
$practicas   = new cItemsAuditoria;   // todas la practicas
$autorizadas = new cItemsAuditoria;   // practicas autorizadas
$rechazadas  = new cItemsAuditoria;   // practicas rechazadas
$afiliado    = new cAfiliados;        // Afiliados
$autoriza    = true;                  // flag que determina si autoriza o no

$afiliado = new cAfiliados;

$id       = $_REQUEST['idperfil'];
$codos    = $_REQUEST['codos'];
$nrodoc   = $_REQUEST['nrodoc'];
$nrotrans = $_REQUEST['nrotrans'];

$afiliado->getObject($codos, $nrodoc);

if ($afiliado->getNombre() == '*** Inexistente ***') {
    echo 'El Paciente No Existe en el Padrón - Auditoría Rechazada ...!';
} else {


if ($id <> 0) {
  $obj = new cModelos;
  $resultado = $obj->getModelo($id);

    while($fila=mysql_fetch_array($resultado)){
        $practicas->AgregarItems($fila['codigo']);  // Agregamos a Prácticas totales

        // Comparar los rangos y ver si se autoriza o rechaza
        $r = $practicas->getAuditoriaMasReciente($codos, $nrodoc, $fila['codigo']);
        $frecuencia = $obj->getFrecuenciaEnDias($id, $fila['codigo']);  // recuperamos la frencuencia en dias
        $rechaza = false;
        
        if ($r <> null) {
          while($f=mysql_fetch_array($r)){
            // recuperamos la fecha del ultimo analisis
            //$fecha = $utiles->getFechaDDMMAA($auditoria->getFechaDeterminacion($f['nro']));
            if (strlen($f['ultimafecha']) == 8) {
                $fecha = $utiles->getFechaDDMMAA($f['ultimafecha']);
                //echo $f['ultimafecha'] . ' ' . $utiles->getFechaAAAAMMDD($utiles->getFechaActual());
                if ($f['ultimafecha'] == $utiles->getFechaAAAAMMDD($utiles->getFechaActual())) {
                    $rechaza = true;
                }                
            } else {
                $fecha = $utiles->getFechaActual();                
            }
            $intervalo = $utiles->restaFechas($fecha, $utiles->getFechaActual());
            // Si alguna practica esta fuera del intervalo, la rechaza
            //echo 'Frecuencia: ' . $frecuencia . ' Intervalo:  ' . $intervalo . ' Ultima Fecha: ' . $fecha;
            
            if ($frecuencia <= $intervalo or $rechaza) {
               $autoriza = false;
               break;
            }
          }
        }
    }
    
    echo '<table>';
    echo '<tr>';
    echo '<td align="left"><b>Prácticas Autorizadas</b></td>';
    echo '<td align="left"><b>Prácticas Rechazadas</b></td>';
    echo '</tr>';

    $practicas->ListarItems($autoriza, $nrotrans);

    echo '</table>';

    }
}

?>
