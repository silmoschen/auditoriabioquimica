<?php

session_start();

$a = $_REQUEST['ssusuario'];
$u = $_REQUEST['mmtx'];

$_SESSION["nivel"] = '';
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEfector.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuarios.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cLogs.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/CUsuariosAuditoresTramos.php");

$obj = new cEfector;
$us = new CUsuarios;
$log = new cLogs();
$auditores = new CUsuariosAuditoresTramos();

$loginOK = false;

$_SESSION["__obsocial"] = null;

if ($_SESSION['susuario'] != $a && $_SESSION['susuario'] > '') {
    echo '<font color="#FF0000">Se ha Producido un Error al Iniciar la Sessión.</font><br>';
    echo 'Error :: La sessión ' . $_SESSION['susuario'] . ' está actualmente activa';
} else {

    if ($obj->verificarUsuario($a, $u)) {
        $loginOK = true;
        $_SESSION["susuarios"] = $obj->getNombre();
    } else {
        $loginOK = false;
    }
    
    $auditor = false;

    if ($loginOK == false) {
        if ($us->verificarUsuario($a, $u)) {
            $_SESSION["susuario"] = $a;
            $_SESSION["nivel"] = $us->nivel;
            $_SESSION["efector"] = $us->getEfector();
            $loginOK = true;
            if ($us->getEfector() != '') $auditor = true;
        } else {
            $loginOK = false;
        }
    }

    if ($loginOK) {
        echo 'Login OK';
        $_SESSION["susuario"] = $a;
        $_SESSION["spass"] = $u;
        if ($us->getObsocial() != null)
            $_SESSION["__obsocial"] = $us->getObsocial();
        $log->crear($a);
    } else {
        echo 'Login Incorrecto';
        $_SESSION["susuario"] = '';
        $_SESSION["spass"] = '';
    }

    if ($auditor) {
        // Usuarios auditoria

        $fecha = date("d/m/Y");

        $_SESSION["auditor1"] = '';
        $_SESSION["auditor2"] = '';
        $_SESSION["nauditor1"] = '';
        $_SESSION["nauditor2"] = '';

        if ($auditores->getAuditores($fecha)) {

            // Verificamos el auditor
            //echo $auditores->getAuditor1() . '  ' . $us->getEfector() . ' ---- ' . $auditores->getAuditor2() . '  ' . $us->getEfector();
            $autoriza = 'N';

            if ($us->getEfector() == $auditores->getAuditor1()) {
                $autoriza = 'S';
            }
            if ($us->getEfector() == $auditores->getAuditor2()) {
                $autoriza = 'S';
            }

            if ($autoriza == 'N') {
                echo ' - Auditor No Autorizado';
                $loginOK = false;
                $_SESSION["susuario"] = '';
                $_SESSION["spass"] = '';
                return;
            }

            $_SESSION["auditor1"] = $auditores->getAuditor1();
            $_SESSION["auditor2"] = $auditores->getAuditor2();
            $_SESSION["nauditor1"] = $auditores->getNAuditor1();
            $_SESSION["nauditor2"] = $auditores->getNAuditor2();
        }
    }
}

?>