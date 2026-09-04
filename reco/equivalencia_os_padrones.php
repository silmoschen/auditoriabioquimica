<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Unificar Padrones de Afiliados en Obras Sociales</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function Registrar(codigo1, codigo2, modo){
                //donde se mostrar los registros
                if (codigo1 == '000000' || codigo2 == '000000') {
                    alert('Debe Seleccionar una Obra Social ...!');
                    return false;
                }
                if (codigo1 != codigo2) {
                    var aleatorio = Math.random();
                    divMensaje = document.getElementById('mensaje');
                    var ajax=objetoAjax();
                    ajax.open('GET', './operaciones/registro_codigo_os_padron.php?codigo1='+codigo1+'&codigo2='+codigo2+'&modo='+modo+'&aleatorio='+aleatorio);
                    divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divMensaje.innerHTML = ajax.responseText
                        }
                        Listar();
                    }
                    ajax.send(null);
                } else {
                    alert('Las Obras Sociales deben ser Diferentes ...!');
                }
            }

            function Listar(){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divLista = document.getElementById('lista');
                var ajax=objetoAjax();
                ajax.open('GET', './operaciones/lista_codigo_os_padron.php?aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null);
            }

            function Borrar(codigo) {
                var aleatorio = Math.random();
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraObsocial.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }

                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBaja(codigo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/baja_equivalencia_padron.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Borrando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        if (divBorrar.innerHTML.length == 0) {
                            // Si la Baja fue OK, refrescamos la página
                            Listar();
                        }
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send('codigo='+codigo);
            }

            function CancelarBaja() {
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                Listar();
            }

            function Salir()  {
                var aleatorio = Math.random();
                var host = location.protocol + '//' + location.hostname + '/usuarios.php?aleatorio=' + aleatorio;
                window.location.href = host;
            }

        </script>
        <link rel="stylesheet" type="text/css" href="css1.css">
    </head>

    <body>

        <div style="margin:auto;width:550px;text-align:center;">

            <?php
            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
            include('menu_admin.php');
            ?>

            <FIELDSET>
                <LEGEND>Unificar Padrón de Afiliados en Obras Sociales</LEGEND>

                <table border='0px' width='500px'>
                    <tr>
                        <td width='80px' align='right'>Ob.Social:</td>
                        <td width='200px' align='left'>
                            <div id="obrassociales1">
                                <?php include('operaciones/list_os_padrones1.php') ?>
                            </div>
                        </td>
                        <td width="250px" align="left">(que utilizará el padrón)</td>
                    </tr>

                    <tr>
                        <td width='80px' align='right'>Ob.Social:</td>
                        <td width='200px' align='left'>
                            <div id="obrassociales2">
                                <?php include('operaciones/list_os_padrones2.php') ?>
                            </div>
                        </td>
                        <td width='250px' align="left">(que tiene definido el padrón)</td>
                    </tr>

                </table>

            </FIELDSET>

            <table border="0px" width="550px">
                <tr>
                    <td width="150"></td>
                    <td width="70" align="right">
                        <input type="button" name="registrar" value="Registrar" class="button gray small" onClick="Registrar(listObsocial1.value, listObsocial2.value, 1); return false" />
                    </td>
                    <td align="left"><input type="button" name="Cerrar" value="Cerrar" class="button gray small" onclick="Salir2(); return false" /></td>
                </tr>
            </table>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <div id="lista">
            </div>

        </div>

        <script>
            Listar();
            Init();
        </script>

    </body>
</html>
