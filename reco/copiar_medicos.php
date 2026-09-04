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
        <title>Copiar Médicos a otra Obra Social</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function Registrar(codigo1, codigo2, modo){
                //donde se mostrar los registros
                if (confirm('¿ Seguro para Copiar Médicos ?' )) {
                    if (codigo1 == '000000' || codigo2 == '000000') {
                        alert('Debe Seleccionar una Obra Social ...!');
                        return false;
                    }
                    if (codigo1 != codigo2) {
                        var aleatorio = Math.random();
                        divMensaje = document.getElementById('mensaje');
                        var ajax=objetoAjax();
                        ajax.open('GET', './operaciones/copiar_medicos_os.php?codigo1='+codigo1+'&codigo2='+codigo2+'&modo='+modo+'&aleatorio='+aleatorio);
                        divMensaje.innerHTML= '<img src="anim.gif"> :: Procesando ...';
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
            }

        </script>
        <link rel="stylesheet" type="text/css" href="css1.css" />
    </head>

    <body>

        <div style="margin:auto;width:550px;text-align:center;">

            <?php
            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
            include('menu_admin.php');
            ?>

            <FIELDSET>
                <LEGEND>Copiar Médicos a otra Obra Social</LEGEND>

                <table border='0px' width='500px'>
                    <tr>
                        <td width='80px' align='right'>Ob.Social:</td>
                        <td width='200px' align='left'>
                            <div id="obrassociales1">
                                <?php include('operaciones/list_os_padrones1.php') ?>
                            </div>
                        </td>
                        <td width="250px" align="left">(que tiene los médicos cargados)</td>
                    </tr>

                    <tr>
                        <td width='80px' align='right'>Ob.Social:</td>
                        <td width='200px' align='left'>
                            <div id="obrassociales2">
                                <?php include('operaciones/list_os_padrones2.php') ?>
                            </div>
                        </td>
                        <td width='250px' align="left">(a la que se los Desea Copiar)</td>
                    </tr>

                </table>

            </FIELDSET>

            <table border="0px" width="550px">
                <tr>
                    <td width="150"></td>
                    <td width="70" align="right">
                        <input type="button" name="registrar" value="Iniciar Proceso" class="button gray small" onClick="Registrar(listObsocial1.value, listObsocial2.value, 1); return false" />
                    </td>
                    <td align="left"><input type="button" name="Cerrar" value="Cerrar" class="button gray small" onclick="Salir2(); return false" /></td>
                </tr>
            </table>

            <div id="mensaje">
            </div>

        </div>

        <script>
            Init();
        </script>

    </body>
</html>
