<?php
session_start();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Padron de Afiliados a Obras Sociales</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function CambiarOS() {
                document.getElementById('contenido').innerHTML = '';
                return false;
            }

            function PagAfiliado(nropagina, filtro, codos, edbaja, falter){
                var aleatorio = Math.random();
                //donde se mostrar los registros
                divContenido = document.getElementById('contenido');
                var tipofiltro = document.getElementById('tipofiltro').value;
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/lista_afiliados_cons.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&codos='+codos+'&aleatorio='+aleatorio+'&tipofiltro='+tipofiltro);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText;
                    }
                }
                ajax.send(null)
            }

            function Buscar(tipofiltro) {
                var aleatorio = Math.random();
                var divBuscar = document.getElementById('buscar');

                document.getElementById('contenido').innerHTML = '';
                document.getElementById('tipofiltro').value = tipofiltro;

                var ajax = objetoAjax();
                ajax.open('GET', './operaciones/buscar.php?aleatorio=' + aleatorio);
                divBuscar.innerHTML = '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        divBuscar.innerHTML = ajax.responseText;
                        document.getElementById('buscarvalor').focus();
                    }
                }
                ajax.send(null);
            }

            function ProcederBuscar(){
                var codos=document.getElementById('listObsocial').value;
                var valor=document.getElementById('buscarvalor').value;
                if (valor.length > 4) {
                    PagAfiliado(1, valor, codos, 'T');
                    divBuscar = document.getElementById('buscar');
                    divBuscar.innerHTML = '';
                } else {
                    alert('Se deben Ingresar al Menos 5 (cinco) caractéres ...!');
                }
            }

            function BuscarValor(e, xbuscar) {
                var valor=document.getElementById('buscarvalor').value;
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (valor.length >= 0) {
                        codos=document.getElementById('listObsocial').value;
                        PagAfiliado(1, valor, codos, 'T');
                        divBuscar = document.getElementById('buscar');
                        divBuscar.innerHTML = '';
                    }
                }
            }

            function CancelarBusqueda(){
                document.getElementById('buscar').innerHTML = '';
            }

        </script>

        <link rel="stylesheet" type="text/css" href="css1.css">

    </head>

    <body>

        <div style="margin:auto;width:550px;text-align:center;">
            <?php include('menu_usuario.php'); ?>

            <FIELDSET>
                <LEGEND>Mantenimiento Afiliados a Obras Sociales</LEGEND>

                <table width="500px" border="0px">
                    <tr>
                        <td width="100" align = "right">Obra Social:</td>
                        <td width="300" align = "left">
                            <?php include('operaciones/lista_obrassociales_padron.php') ?>
                        </td>
                        <td width="130" align = "left"></td>
                    </tr>
                </table>

                <table width="500px" border="0px">
                    <tr>
                        <td width="100px" align="right">Buscar por:</td>
                        <td width="60px" align="center">
                            <a href="javascript://" onclick="Buscar('1')">Nombre</a>
                        </td>
                        <td width="60px">
                            <a href="javascript://" onclick="Buscar('2')">Documento</a>
                        </td>
                        <td width="80px">
                            <a href="javascript://" onclick="Buscar('3')">Nro. Benefio</a>
                        </td>
                        <td width="200px"></td>
                    </tr>
                </table>

            </FIELDSET>

            <div id="buscar" align="center">
            </div>

            <div id="contenido" align="left">
            </div>

            <input type="hidden" name="tipofiltro" id="tipofiltro" value="" />

        </div>

    </body>

    <script>
        document.getElementById('listObsocial').onchange = CambiarOS;
        document.getElementById('listObsocial').focus();
    </script>

</html>