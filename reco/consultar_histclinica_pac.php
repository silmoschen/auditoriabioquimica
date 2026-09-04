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

            function Init() {
                document.getElementById('nrodoc').focus();
                document.getElementById('consultarhc').disabled = false;
                return true;
            }

            function ValidarNrodoc(e, codos, nrodoc) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (nrodoc.length > 0) {
                        CargarDocumento(codos, nrodoc);
                    } else {
                        alert('El Número de Documento es Incorrecto ...!')
                    }
                    return true;
                }
            }

            function CambiarOS() {
                document.getElementById('nrodoc').focus();
            }

            function CargarDocumento(xcodos, xnrodoc) {
                var aleatorio = Math.random();
                divCD = document.getElementById('nombre');
                divLista = document.getElementById('Lista');
                divLista.innerHTML = '';
                ajaxp0 = objetoAjax();
                ajaxp0.open('GET', './operaciones/buscar_paciente.php?codos=' + xcodos + '&nrodoc=' + xnrodoc + '&aleatorio=' + aleatorio, true);
                ajaxp0.onreadystatechange = function () {
                    if (ajaxp0.readyState == 4) {
                        divCD.innerHTML = ajaxp0.responseText
                    }

                    if (ajaxp0.responseText > '') {
                        if (ajaxp0.responseText == 'Paciente Inexistente') {
                            document.getElementById('consultarhc').disabled = true;
                        } else {
                            document.getElementById('consultarhc').disabled = false;
                            document.getElementById('consultarhc').focus();
                        }
                    }
                }

                ajaxp0.send(null);
            }


            function Consultar() {
                var nrodoc = document.getElementById('nrodoc').value;
                var codos = document.getElementById('listObsocial').value;

                if (nrodoc != '' && codos != '') {
                    //donde se mostrar los registros
                    CargarDocumento(codos, nrodoc);
                    var aleatorio = Math.random();
                    divLista = document.getElementById('Lista');
                    var ajaxinf = objetoAjax();
                    //uso del medoto GET
                    //indicamos el archivo que realizar� el proceso de paginar
                    //junto con un valor que representa el nro de pagina
                    ajaxinf.open('GET', './operaciones/historiaclinica_paciente_ext.php?codos=' + codos + '&nrodoc=' + nrodoc + '&aleatorio=' + aleatorio);
                    divLista.innerHTML = '<img src="anim.gif">  :: Procesando, espere un momento ...';
                    ajaxinf.onreadystatechange = function () {
                        if (ajaxinf.readyState == 4) {
                            //mostrar resultados en esta capa
                            divLista.innerHTML = ajaxinf.responseText
                        }
                    }
                    ajaxinf.send(null)
                } else {
                    alert('El Número de Documento es Incorrecto ...!')
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
                divNombre.innerHTML = '';
                document.getElementById('nrodoc').value = '';
                document.getElementById('nrodoc').focus();
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {
                nrodoc.focus()
            }
            ;
            return true;">

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
                        <td width="180px" align="left">Consultar Análisis de Pacientes</td>
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
                        <td width="70" align = "right">Nro.Doc.:</td>
                        <td width="75" align = "left">
                            <input name="nrodoc" id="nrodoc" type="text" style="font-size: 10px;" maxlength="30" width="2" size="20" onkeypress="javascript: if (ValidarNrodoc(event, listObsocial.value, nrodoc.value)) {
                                        nrodoc.focus()
                                    }
                                    ;
                                    return true" />
                        </td>

                        <td width="305" align = "left"><div id="nombre"></div></td>
                </table>

                <table width="550px" border="0" align="center">
                    <tr>
                        <td><hr></td>
                    </tr>
                </table>
                <table width="550px" border="0" align="center">
                    <tr>
                        <td width="150px" align="left">
                            <input type="button" name="consultarhc" id="consultarhc" value="Procesar Consulta" class="button gray small" disabled="true" onclick="Consultar();
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
        Init();
    </script>

</html>
