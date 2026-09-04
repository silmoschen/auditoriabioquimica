<?php

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocialRestCodigos.php");

$codos = $_REQUEST['codos'];

$obj = new cObsocialRestCodigos;

$resultado = $obj->getObjects($codos);



$i = 0;
while ($fila = mysql_fetch_array($resultado)) {

    if ($i == 0) {
        echo '<table>';
        echo '<hr/><table>';
        echo '<tr><td><b>Restricciones en el Ingreso de Codigos</b></td></tr>';
        echo '</table>';

        echo '<table>';
        echo '<tr><td><b>Código</b></td><td><b>Contempla Código</b></td><td><b>Restricción</b></td></tr>';
        
        $i = 1;
    }

    if ($i == 1) echo '<tr><td width="20%">' . $fila['codigo1'] . '</td><td width="20%">' . $fila['codigo2'] . '</td><td width="50%">' . $fila['observacion'] . '</td></tr>';
}


echo '</table>';


/*
  $resultado = $obj->getObjectJSON($codos);

  echo $resultado;
 * 
 */
?>
