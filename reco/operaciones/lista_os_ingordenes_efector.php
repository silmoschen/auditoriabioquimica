<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfectoresExcluidos.php');

$obj = new cObsocial;
$ef = new cEfectoresExcluidos();

$resultado = $obj->getObrasSociales();

$idprof = $_REQUEST['idprof'];
?>

<select name="listObsocial" id="listObsocial" width="150" style="width:330px" onchange ="javascript: if (ControlOSS()) {
            nrodoc.focus()
        }" onkeypress ="javascript: if (ControlOS(event)) {
                    nrodoc.focus()
                }
                ;
                return true">
    <?php
    while ($fila = mysql_fetch_array($resultado)) {
        if ($ef->getObject($fila['codos'], $idprof) == 0)
            if ($fila['inactiva'] == 0)
                echo '<option value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 80) . '</option>';
    }
    ?>
</select>  