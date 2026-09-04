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
    <head><title>Consulta Historias Clinicas</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function CambiarOS() {
                var codos = document.getElementById('listObsocial').value;

                var aleatorio = Math.random();
                divLista = document.getElementById('divprof');
                divMsg = document.getElementById('Lista');
                var ajaxinf = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajaxinf.open('GET', './operaciones/listar_medicos.php?codos=' + codos + '&aleatorio=' + aleatorio);
                divMsg.innerHTML = '<img src="anim.gif">  :: Cargando Médicos, espere un momento ...';
                ajaxinf.onreadystatechange = function () {
                    if (ajaxinf.readyState == 4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajaxinf.responseText;
                        divMsg.innerHTML = '';
                    }
                }
                ajaxinf.send(null)
            }            

            function Consultar() {
                var idprof = document.getElementById('listMedicos').value;
                var codos = document.getElementById('listObsocial').value;

                if (idprof != '' && codos != '') {
                    //donde se mostrar los registros                    
                    var aleatorio = Math.random();
                    divLista = document.getElementById('Lista');
                    var ajaxinf = objetoAjax();
                    //uso del medoto GET
                    //indicamos el archivo que realizar� el proceso de paginar
                    //junto con un valor que representa el nro de pagina
                    ajaxinf.open('GET', './operaciones/historiaclinica_paciente_prof.php?codos=' + codos + '&idprof=' + idprof + '&aleatorio=' + aleatorio);
                    divLista.innerHTML = '<img src="anim.gif">  :: Procesando, espere un momento ...';
                    ajaxinf.onreadystatechange = function () {
                        if (ajaxinf.readyState == 4) {
                            //mostrar resultados en esta capa
                            divLista.innerHTML = ajaxinf.responseText
                        }
                    }
                    ajaxinf.send(null)
                } 
            }

            function Imprimir() {
                var ficha = document.getElementById('Lista');
                var ventimp = window.open(' ', 'popimpr');
                ventimp.document.write(ficha.innerHTML);
                ventimp.document.close();
                ventimp.print();
                ventimp.close();
            }

            function Nueva() {
                divLista = document.getElementById('Lista');
                divNombre = document.getElementById('nombre');
                divLista.innerHTML = '';
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body>

        <form name="frmConsultahc" method="post" onsubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">
                <?php
                $red = 0;
                if ($usuario == 'fesalud') {
                    include('menu_fesalud.php');
                    $red = 1;
                }
                if ($usuario == 'iapos') {
                    include('menu_iapos.php');
                    $red = 1;
                }

                if ($red == 0)
                    include('menu_admin.php');
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                ?>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="180px" align="left">Prácticas por Profesional</td>
                        <td width="320px"><hr></td>
                    </tr>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="80" align = "right">
                            Ob.Social:</td>
                        <td colspan="3" align = "left">
                            <?
                            if ($usuario == 'administ') {
                                include('operaciones/lista_os_auditarordenes.php');
                            } else {
                                include('operaciones/lista_obrassociales_session.php');
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="80" align = "right">
                            Profesional:</td>
                        <td colspan="3" align = "left">
                            <div id="divprof"></div>
                        </td>
                    </tr>

                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>
                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="150px" align="left">
                            <input type="button" name="consultarhc" id="consultarhc" value="Procesar Consulta" class="button gray small" onclick="Consultar();
                                    return false" />
                        </td>
                        <td width="150px" align="left">
                            <input type="button" name="imprimirhc" id="imporimirhc" value="Imprimir" class="button gray small" hidden="true" onclick="Imprimir(); return false" />
                        </td>
                        <td width="150px" align="left">
                            <input type="button" name="nuevahc" id="nuevahc" value="Nueva Consulta" class="button gray small" onclick="Nueva(); return false" />
                        </td>
                    </tr>

                </table>

                <div id="BotonesImpresion" align="center">
                </div>

                <div id="Lista" align="left">
                </div>

            </div>
        </form>        

    </body>

    <script>
        CambiarOS();
    </script>

</html>
