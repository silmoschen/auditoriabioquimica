<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$usuario = $_REQUEST['usuario'];
$pass    = $_REQUEST['pass'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");

$obj = new cEfector();

if ($obj->verificarUsuario($usuario, $pass)) {
    echo 'Contraseña OK';
} else {
    echo '*** Cont. Incorrecta ***';
}

?>
