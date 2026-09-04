<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');

$obsocial = new cObSocial();
$resultado = $obsocial->getObrasSociales();
while ($fila = mysql_fetch_array($resultado)) {
    $mc = 0;
    if ($fila['medicos_cab'] == 1) {
        $mc = $fila['medicos_cab'];
    }
    printf($fila['codos'] . '::' . $mc . '::' . $fila['alta_paciente'] . '::' . $fila['nombre'] . " \r\n");
}
?>
