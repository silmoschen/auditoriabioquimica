<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosRestringidos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$codos  = $_REQUEST['codos'];

$obj = new cCodigosRestringidos;
$nom = new cNBU;

$resultado = $obj->getCodigos($codos);

?>

<div style="margin:auto;width:500px;text-align:center;">

<?php
    $b = true;
    while($fila=mysql_fetch_array($resultado)){
       if ($b) {         
         echo '<table>';
         echo '<tr>';
         echo '<td width="50"><b>Codigo</b></td>';
         echo '<td align="left" width="300px"><b>Determinacion</b></td>';         
         echo '<td><b>Baja</b></td>';
         echo '</tr>';
         $b = false;
         echo '</table>';
         echo '<hr>';
         echo '<table>';
        }
        echo '<tr>';
        echo '<td width="50">'.$fila['codigo'].'</td>';
        $nom->getObject($fila['codigo']);
        echo '<td align="left"  width="300">'.$nom->getDescrip().'</td>';        
        $borra = "BajaCodigo("."'".$fila['codos']."'".", "."'".$fila['codigo']."'".")";
        echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
        echo '</tr>';
    }
?>

</div>