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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <head><title>Excluir Efectores de Obras Sociales</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>           

            function ControlOSS(){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divLista = document.getElementById('Lista');
        
                var ajax=objetoAjax();
                divLista.innerHTML= '<img src="anim.gif"> :: Cargando ...';
                ajax.open('GET', './operaciones/lista_os_validacion.php?&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }                   
                }

                ajax.send(null)
            }          

           function chequear(codos){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divLista = document.getElementById('Mensaje');

                var ajax=objetoAjax();
                divLista.innerHTML= '<img src="anim.gif"> :: Registrando ...';

                ajax.open('GET', './operaciones/excluir_obsocial_validacion.php?codos='+codos+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }                   
                }

                ajax.send(null)
            }                     

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body>

        <div style="margin:auto;width:550px;text-align:center;">

            <form name="frmConsulta" method="post" onsubmit="return false">

                <?php
                echo '<input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" />';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0">
                    <tr>
                        <td width="178px" align = "left">
                            Obras Sociales a Excluir Validación
                        </td>
                        <td width="322px" align = "left">
                            <hr>
                        </td>
                    </tr>

                </table>
               
                <div id="Mensaje" align = 'left'>
                </div>
                
                <div id="Lista" style="overflow-y: scroll; height:280px;" align = 'left'>
                </div>               

            </form>
            
            <script type="text/javascript">
                ControlOSS();
            </script>

        </div>

    </body>
</html>