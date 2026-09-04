<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cObSocial.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/CUsuarios.php');

$codos = $_REQUEST['codos'];

$obj = new cObsocial;
$uss = new CUsuarios();

$uss1 = $uss->findUsuario($_SESSION["susuario"]);

//$resultado = $obj->getObrasSociales();

$resultado = $obj->getListObrasSocialesUsuarios($uss->getObsocial());

?>
<select name="listObsocial" id="listObsocial" style="width:250px">
    <?php
    while ($fila = mysql_fetch_array($resultado)) {
        echo '<option value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 35) . '</option>';
        /*
        if ($_SESSION["__obsocial"] == null) {
            if ($codos == $fila['codos']) {
                echo '<option selected value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 35) . '</option>';
            } else {
                echo '<option value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 35) . '</option>';
            }
        }

        if ($_SESSION["__obsocial"] != null) {
            if ($_SESSION["__obsocial"] == $fila['codos']) {
                echo '<option selected value =' . '"' . $fila['codos'] . '"' . '>' . substr($fila['nombre'], 0, 35) . '</option>';
            } 
        }
         * 
         */
    }
    ?>
</select>  