<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cCodigosExcluidos.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$codigo = $_REQUEST['codigo'];

$obj = new cCodigosExcluidos;
$nom = new cNBU;

$resultado = $obj->getCodigos($codigo);

?>

<div style="margin:auto;width:500px;text-align:center;">

<?php
    $b = true;
    while($fila=mysql_fetch_array($resultado)){
       if ($b) {         
         echo '<table>';
         echo '<tr>';
         echo '<td width="50px"><b>Codigo</b></td>';
         echo '<td align="left" width="380px"><b>Determinacion</b></td>';
         echo '<td><b>Baja</b></td>';
         echo '</tr>';
         echo '</table>';
         echo '<hr>';
         echo '<table>';
         $b = false;
        }
        echo '<tr>';
        echo '<td width="50px">'.$fila['codigo2'].'</td>';
        $nom->getObject($fila['codigo2']);
        echo '<td align="left" width="380px">'.$nom->getDescrip().'</td>';
        $borra = "BajaCodigo("."'".$fila['codigo1']."'".", "."'".$fila['codigo2']."'".")";
        echo '<td><a href="javascript://" onclick="' . $borra . '">Borrar</a></td>';
        echo '</tr>';
    }

if ($b == false) {
  echo '</table><hr>';
  $seleccion = "CerrarListaDefinicion()";
  echo '<td width="2px"><a href="javascript://" onclick="' . $seleccion . '">Finalizar Consulta</a></td>';
}
?>

</div>