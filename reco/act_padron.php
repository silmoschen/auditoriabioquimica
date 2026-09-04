<?php
session_start();
$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
$init = $_REQUEST['init'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Actualizar Padrones</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function ProcesarArch() {
                if (confirm('¿ Seguro Aactualizar Padron ?. Este Proceso Puede tardar Varios Minutos. NO LO INTERRUMPA !!!' )) {
                    Procesar();
                }
            }

            function SubirArchivo(){
                var aleatorio = Math.random();
                var divMensaje = document.getElementById('Mensaje');
                
                var codos  = document.getElementById('listObsocial').value;

                var ajax=objetoAjax();
                divMensaje.innerHTML = '<img src="anim.gif"> :: Procesando ...';
                ajax.open('GET', '/subir_padron.php?codos='+codos+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        divMensaje.innerHTML = ajax.responseText
                    }                    
                }
                ajax.send(null);
            }

            function Procesar(){
                var aleatorio = Math.random();
                var divMensaje = document.getElementById('Mensaje');

                var codos  = document.getElementById('listObsocial').value;

                var ajax=objetoAjax();
                divMensaje.innerHTML = '<img src="anim.gif"> :: Procesando ...';
                ajax.open('GET', '/operaciones/procesar_actualizacion_padron.php?codos='+codos+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        divMensaje.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null);
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css" />

    </head>

    <body>

        <form name="frmActualizarPadrones" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="140px" align="left">Actualización de Padrones</td>
                        <td width="360px"><hr></td>
                    </tr>
                </table>

                <table width="426" border="0" align="center">
                    <tr>
                        <td width="50" align="right">O.Social:</td>
                        <td width="200px">
                            <?
                            include('operaciones/lista_obrassociales.php');
                            ?>
                        </td>
                        <td width="100px"></td>
                        <td width="100px"></td>
                    </tr>

                    <tr>

                        <?
                            
                            if ($init == "1") {
                                echo '<td width="50px"></td>';
                                echo '<td width="200px" align="left">';
                                echo '<a href="javascript://" onclick="SubirArchivo()">Subir el Archivo del Padron</a>';
                                echo '</td>';
                                echo '<td width="70px">';
                                echo '</td>';
                                echo '<td width="100px"></td>';
                                echo '</tr>';
                            }

                            if ($init != "1") {
                                echo '<td width="50px"></td>';
                                echo '<td width="200px" align="left">';
                                echo '<a href="javascript://" onclick="ProcesarArch()">Actualizar el Padron</a>';                                
                                echo '</td>';
                                echo '<td width="70px">';
                                echo '</td>';
                                echo '<td width="100px"></td>';
                                echo '</tr>';
                            }                          
                        ?>
                    </tr>


                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>

                <div id="Selarchivo" align="left">
                </div>

                <div id="Mensaje" align="center">
                </div>

            </div>
        </form>

    </body>

    <script>
        Init();
    </script>

</html>
