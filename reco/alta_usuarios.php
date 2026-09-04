<?php
session_start();
//session_register('susuario');
//session_register('spass');
//session_register('snombre');
//session_register('sdireccion');
//session_register('stelefono');
//session_register('semail');

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
if (isset($_GET['trans'])) {
    $trans = $_REQUEST['trans'];
}
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Nuevos Usuarios</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script>

            function Init() {
                document.getElementById('us').focus();
                return true;
            }

            function ValidarUsuario(xusuario){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xusuario.length == 0) {
                        alert('Usuario Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarPass(xpass){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xpass.length == 0) {
                        alert('Contraseña Incorrecta ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarObservacion(xobs){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function Cerrar() {
            }

            function registrarUsuario(xus, xpas, xobs, xnivel, xauditor) {
                var aleatorio = Math.random();
   
                var divMensaje = document.getElementById('Mensaje');

                divMensaje.innerHTML = '<i>Registrando Usuario ...</i>';

                ajax=objetoAjax();
                ajax.open('GET', './operaciones/registro_usuario.php?usuario='+xus+'&pass='+xpas+'&observacion='+xobs+'&nivel='+xnivel+'&aleatorio='+aleatorio+'&auditor='+xauditor);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;
                    }
                    ListarUsuarios();
                }

                ajax.send(null);
            }

            function ListarUsuarios() {
                var aleatorio = Math.random();

                var divListar = document.getElementById('ListaUsuarios');

                ajax1=objetoAjax();
                ajax1.open('GET', './operaciones/listar_usuarios.php?&aleatorio='+aleatorio);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divListar.innerHTML = ajax1.responseText;
                    }
                }

                ajax1.send(null);
            }

            function Borrar(id) {
                if (confirm('¿ Seguro Eliminar Usuario ' + id + ' ?' )) {
                    var aleatorio = Math.random();

                    var divMensaje = document.getElementById('Mensaje');

                    divMensaje.innerHTML = '<i>Borrando ...</i>';

                    ajax=objetoAjax();
                    ajax.open('GET', './operaciones/borro_usuario.php?id='+id+'&aleatorio='+aleatorio);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divMensaje.innerHTML = ajax.responseText;
                        }
                        ListarUsuarios();
                    }

                    ajax.send(null);
                }
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {us.focus()}; return true;">

        <div style="margin:auto;width:550px;text-align:center;">
            <?php
            include('menu_admin.php');
            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
            ?>

            <form name="frm_nuevousuario" method="post" onsubmit="return false">

                <div style="margin:auto;width:300px;text-align:center;">

                    <FIELDSET>
                        <LEGEND>Nuevo Usuario</LEGEND>

                        <table width="300px" border="0" align="center">
                            <tr>
                                <td align="right" width="50px">Usuario:</td>
                                <td align="left" width="350px">
                                    <input name="us" id="us" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarUsuario(event, usuario.value)) {pass.focus()}; return true" />
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="50px">Contraseña:</td>
                                <td align="left" width="350px">
                                    <input name="pass" id="pass" type="text" style="font-size: 10px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarPass(event, pass.value)) {observacion.focus()}; return true" />
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="50px">Observación:</td>
                                <td align="left" width="350px">
                                    <input name="observacion" id="observacion" type="text" style="font-size: 10px;" maxlength="80" width="2" size="40" onkeypress="javascript: if(ValidarObservacion(event, observacion.value)) {btnRegistrar.focus()}; return true" />
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="50px">Efector (*):</td>
                                <td align="left">
                                    <div id="_auditor3">
                                        <?php include('operaciones/lista_usuarios_3.php') ?>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td width="110px" align="right">
                                    <input type="button" name="btnRegistrar" value="Registrar" class="button gray small" onclick="registrarUsuario(us.value, pass.value, observacion.value, 1, auditor3.value); return false" />
                                </td>
                                <td align = "left"><input type="button" name="btnCerrar" class="button gray small" value="Cerrar" onclick="Cerrar(); return false" /></td>
                            </tr>

                        </table>
                        
                        (*) Solo para Usuarios Auditores

                    </FIELDSET>

                </div>
            </form>

            <div id="Mensaje" align="center"></div>

            <div id="ListaUsuarios" align="center">
                <?php include('operaciones/listar_usuarios.php') ?>
            </div>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>