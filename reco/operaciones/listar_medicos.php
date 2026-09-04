<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cMedicos.php');

$codos = $_REQUEST['codos'];

$obj = new cMedicos;

$resultado = $obj->getListaMedicos($codos, '', 0, 10000000000);
?>

<select name="listMedicos" id="listMedicos" width="80" style="width:300px">
    <?php
    while ($fila = mysql_fetch_array($resultado)) {
        if ($fila['estado'] != 'S' && $fila['nombre'] != '')
            echo '<option value =' . '"' . $fila['idprof'] . '"' . '>' . substr($fila['nombre'], 0, 40) . '</option>';
    }
    ?>
</select>  