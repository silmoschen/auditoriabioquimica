<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEquivalenciaNBU.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$codos  = $_REQUEST['codos'];
$codigo = $_REQUEST['codigo'];

$obj = new cEquivalenciaNBU;
$nom = new cNBU;

$resultado = $obj->getCodigos($codos, $codigo);

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
         echo '<td width="50"><b>Cod.Eq.</b></td>';         
         echo '<td><b>Baja</b></td>';
         echo '</tr>';
         $b = false;
         echo '</table>';
         echo '<hr>';
         echo '<table>';
        }
        echo '<tr>';
        echo '<td width="50">'.$fila['codigo1'].'</td>';
        $nom->getObject($fila['codigo1']);
        echo '<td align="left"  width="300">'.$nom->getDescrip().'</td>';
        echo '<td width="50">'.$fila['codigo2'].'</td>';      
        $borra = "BajaCodigo("."'".$fila['codos']."'".", "."'".$fila['codigo1']."'".")";
        echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
        echo '</tr>';
    }
?>

</div>