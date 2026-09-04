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
    <head><title>Equivalencia de Códigos NBU</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                document.getElementById("m1").focus();
                return true;
            }

            function CambiarOS() {
                ListarItems();
            }

            function verificar(event) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function Registrar(baja, m1, m2, m3, m4, m5, m6, m7) {
                var codos = document.getElementById('listObsocial').value;
                divLista = document.getElementById('Lista');

                var aleatorio = Math.random();

                ajax = objetoAjax();
                ajax.open('GET', './operaciones/actualizo_obsocial_notificacion.php?codos=' + codos + '&baja=' + baja +
                        '&m1=' + m1 + '&m2=' + m2 + '&m3=' + m3 + '&m4=' + m4 + '&m5=' + m5 + '&m6=' + m6 + '&m7=' + m7 + '&aleatorio=' + aleatorio);

                divLista.innerHTML = '<img src="anim.gif"> :: Registrando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null);

            }

            function ListarItems() {
                var codos = document.getElementById('listObsocial').value;
                if (codos.length > 0) {
                    //donde se mostrar los registros
                    var aleatorio = Math.random();

                    ajax = objetoAjax();
                    ajax.open('GET', './operaciones/obsocial_getNotificacion.php?codos=' + codos + '&aleatorio=' + aleatorio);
                    ajax.onreadystatechange = function () {
                        if (ajax.readyState == 4) {
                            //mostrar resultados en esta capa                            
                            var o = JSON.parse(ajax.responseText);

                            if (o == null) {
                                document.getElementById("baja").checked = false;
                                document.getElementById("m1").value = '';
                                document.getElementById("m2").value = '';
                                document.getElementById("m3").value = '';
                                document.getElementById("m4").value = '';
                                document.getElementById("m5").value = '';
                                document.getElementById("m6").value = '';
                                document.getElementById("m7").value = '';
                            } else {
                                document.getElementById("baja").checked = false;
                                if (o[0].baja == 1) document.getElementById("baja").checked = true;
                                document.getElementById("m1").value = o[0].m1;
                                document.getElementById("m2").value = o[0].m2;
                                document.getElementById("m3").value = o[0].m3;
                                document.getElementById("m4").value = o[0].m4;
                                document.getElementById("m5").value = o[0].m5;
                                document.getElementById("m6").value = o[0].m6;
                                document.getElementById("m7").value = o[0].m7;

                            }
                        }

                    }
                    ajax.send(null)
                }
            }


            function Borrar() {
                //donde se mostrará los registros

                var codos = document.getElementById('listObsocial').value;

                if (confirm('Seguro para Borrar Notificación ?')) {

                    var aleatorio = Math.random();
                    divBorra = document.getElementById('Lista');
                    ajax1 = objetoAjax();
                    
                    ajax1.open('GET', './operaciones/borro_obsocial_notificacion.php?codos=' + codos + '&aleatorio=' + aleatorio);
                    divBorra.innerHTML = '<img src="anim.gif"> :: Eliminando ...';
                    ajax1.onreadystatechange = function () {
                        if (ajax1.readyState == 4) {
                            //mostrar resultados en esta capa
                            divBorra.innerHTML = '';
                        }
                        ListarItems();
                    }
                    ajax1.send(null)


                }


            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css" />

    </head>

    <body onLoad="javascript: if (Init()) {
                codigo1.focus()
            }
            ;
            return true;">

        <form name="frmCodigosRestringidos" method="post" onSubmit="return false">

            <div style="margin:auto;width:550px;text-align:center;">

                <?php
                echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
                include('menu_admin.php');
                ?>

                <FIELDSET>
                    <LEGEND>Leyendas a Imprimir en Cupones</LEGEND>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td align = "right" width="85px">Obra Social:</td>
                            <td colspan="3" align = "left" width="200px">
                                <?
                                include('operaciones/lista_os_auditarordenes.php');
                                ?>
                                <input type="checkbox" id="baja" />Baja
                            </td>
                        </tr>

                    </table>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td width="85" align = "right">Leyenda 1:</td>
                            <td align = "left">
                                <input name="m1" id="m1" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (verificar(event)) {
                                            m2.focus()
                                        }
                                        ;
                                        return true" />                                                            
                            </td>
                        </tr>

                        <tr>
                            <td width="85" align = "right">Leyenda 2:</td>
                            <td align = "left">
                                <input name="m2" id="m2" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (verificar(event)) {
                                            m3.focus()
                                        }
                                        ;
                                        return true" />                                                            
                            </td>
                        </tr>

                        <tr>
                            <td width="85" align = "right">Leyenda 3:</td>
                            <td align = "left">
                                <input name="m3" id="m3" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (verificar(event)) {
                                            m4.focus()
                                        }
                                        ;
                                        return true" />                                                            
                            </td>
                        </tr>

                        <tr>
                            <td width="85" align = "right">Leyenda 4:</td>
                            <td align = "left">
                                <input name="m4" id="m4" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (verificar(event)) {
                                            m5.focus()
                                        }
                                        ;
                                        return true" />                                                            
                            </td>
                        </tr>

                        <tr>
                            <td width="85" align = "right">Leyenda 5:</td>
                            <td align = "left">
                                <input name="m5" id="m5" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (verificar(event)) {
                                            m6.focus()
                                        }
                                        ;
                                        return true" />                                                            
                            </td>
                        </tr>

                        <tr>
                            <td width="85" align = "right">Leyenda 6:</td>
                            <td align = "left">
                                <input name="m6" id="m6" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (verificar(event)) {
                                            m7.focus()
                                        }
                                        ;
                                        return true" />                                                            
                            </td>
                        </tr>

                        <tr>
                            <td width="85" align = "right">Leyenda 7:</td>
                            <td align = "left">
                                <input name="m7" id="m7" type="text" style="font-size: 10px;" maxlength="150" width="2" size="70" onKeyPress="javascript: if (verificar(event)) {
                                            m7.focus()
                                        }
                                        ;
                                        return true" />                                                            
                            </td>
                        </tr>


                    </table>

                    <table width="500px" border="0" align="center">

                        <tr>
                            <td align = "left">
                                <?
                                echo '<input type="button" name="registrar" class="button gray small" value="Registrar" onClick="Registrar(baja.checked, m1.value, m2.value, m3.value, m4.value, m5.value, m6.value, m7.value); return false" />';
                                echo '<input type="button" name="borrar" class="button gray small" value="Dar de Baja Notificación" onClick="Borrar(); return false" />';
                                ?>
                            </td>
                        </tr>
                    </table>

                </FIELDSET>

                <div id="Lista">
                </div>


            </div>
        </form>



    </body>

    <script>
        Init();
        ListarItems();
    </script>

</html>
