<?php
session_start();

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cNBU.php');

$nbu = new cNBU;
$resultado = $nbu->getObrasSociales();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>NBU Obra Social</title>
        <script src="../jscript/funcionajax.js"></script>
        <script>
            function CambiarOS() {
                var codos = document.getElementById('listObsocial').value;
                var aleatorio = Math.random();
                var divLista = document.getElementById('practicas');
                var ajax = objetoAjax();

                ajax.open('GET', './operaciones/lista_practicas_nbu_os.php?codos=' + codos + '&aleatorio=' + aleatorio);
                divLista.innerHTML = '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        divLista.innerHTML = ajax.responseText;
                    }
                }
                ajax.send(null);
            }
        </script>
        <link rel="stylesheet" type="text/css" href="css1.css">
    </head>
    <body>
        <div style="margin:auto;width:550px;text-align:center;">
            <?php include('menu_admin.php'); ?>

            <table width="550px" border="0" align="center">
                <tr>
                    <td width="150px" align="left">NBU Obra Social</td>
                    <td width="400px"><hr></td>
                </tr>
            </table>

            <table width="550px" border="0" align="center">
                <tr>
                    <td width="100px" align="right">Obra Social:</td>
                    <td align="left">
                        <select name="listObsocial" id="listObsocial" style="width:300px" onchange="CambiarOS()">
                            <?php
                            while ($fila = mysql_fetch_array($resultado)) {
                                echo '<option value="' . $fila['codos'] . '">' . $fila['nombre'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <div id="practicas" align="left"></div>
        </div>
        <script>
            CambiarOS();
        </script>
    </body>
</html>
