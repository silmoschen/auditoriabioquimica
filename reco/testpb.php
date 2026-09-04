<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Tramos</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

           
            function Nuevo(){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBuscar = document.getElementById('contenido');
                divBuscar.innerHTML = '';
                divNuevo = document.getElementById('nuevo');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', 'http://www.paginasblancas.com.ar/es-ar/telefono/03482424757');
                divNuevo.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = '';
                        divBuscar.innerHTML = '<pre>s' + ajax.responseText + 's</pre>';
                    }                    
                }

                ajax.send(null)
            }

            

        </script>
        <link rel="stylesheet" type="text/css" href="css1.css">
    </head>

    <body>

        <div style="margin:auto;width:550px;text-align:center;">

            <?php
            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';

            ?>

            <div id="botones">
                <?
                

                echo '<table align="center"><tr>';
                echo '<td><a href="javascript://" onclick="Nuevo()">Nuevo</a></td>';
            
                $trans = rand(1, 100000);
                $link = '?p111989=' . $usuario . '&reinit=' . $trans;
                //echo '<td><a href="/newhtml.php' . $link . '">Salir</a></td>';
                echo '</tr></table>';
                echo "</FIELDSET>";
                ?>
            </div>

            <div id="nuevo" align="left">
            </div>

            <div id="buscar" align="center">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <table border="0px" width="500px">
                <tr>
                    <td width="10px"><b>Id.</b></td>
                    <td width="300px" align = "left"><b>Descripción del Tramo</b></td>
                    <td width="70px" align = "left"><b>Tope Práct.</b></td>
                    <td width="70px" align = "left"><b>Cant. Bonos</b></td>
                </tr>
            </table>
            <hr>

            <div id="contenido">
            </div>

        </div>

    </body>

    <script>
        Pagina(1, '', '');
        Init();
    </script>


</html>
