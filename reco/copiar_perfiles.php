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
    <head><title>Copiar Perfiles de Diagnósticos</title>

        <script src="../jscript/funciones.js"></script>
        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/showhidediv.js"></script>
        <script>

            function Init() {
                document.frmConsulta.listObsocial.focus();
                return true;
            }

            function CambiarOS(){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divLista = document.getElementById('Lista');
                var codos = document.getElementById('listObsocial').value;
        
                var ajax=objetoAjax();
                divLista.innerHTML= '<img src="anim.gif"> :: Cargando ...';
                if (codos != '000000') {
                    ajax.open('GET', './operaciones/paginador_perfiles.php?codos='+codos+'&aleatorio='+aleatorio);
                } else {
                    divLista.innerHTML = '';
                }
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                    if (codos != '000000') {
                        MostrarBotones();
                    }
                }

                ajax.send(null)
            }

            function MostrarBotones(){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divBotones = document.getElementById('Botones');

                ajax1=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax1.open('GET', './operaciones/botones_perfiles.php?aleatorio='+aleatorio);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divBotones.innerHTML = ajax1.responseText
                    }
                }

                ajax1.send(null)
            }

            function Pagina(nropagina, filtro, edbaja, xtipo_consulta){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                var codos     = document.getElementById('listObsocial').value;

                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', 'paginador_perfiles.php?codos='+codos+'&pag='+nropagina+'&aleatorio='+aleatorio);
                divLista.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divLista.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function ImprimirSeleccion(){
                //donde se mostrar los registros
                showdiv("botoncopiado");
                showdiv("osdestino");
                showdiv("listaos");

                var aleatorio = Math.random();
                divLabel = document.getElementById('osdestino');
                divOS    = document.getElementById('listaos');
    
                ajax5=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                divLabel.innerHTML =  'A Obra Social:';
                ajax5.open('GET', './operaciones/lista_os_perfiles_destino.php?aleatorio='+aleatorio);
                ajax5.onreadystatechange=function() {
                    if (ajax5.readyState==4) {
                        //mostrar resultados en esta capa
                        divOS.innerHTML = ajax5.responseText
                    }
                }

                ajax5.send(null)
            }

            function ChequearOS() {
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divBoton = document.getElementById('botoncopiado');
                var de = document.getElementById('listObsocial').value;
                var a = document.getElementById('listObsocialDestino').value;
                if (de == a) {
                    alert('No se Puede Copiar una Definición Sobre si Misma  ...!');
                    divBoton.innerHTML = '';
                } else {
                    ajax=objetoAjax();
                    //uso del medoto GET
                    //indicamos el archivo que realizar� el proceso de paginar
                    //junto con un valor que representa el nro de pagina
                    ajax.open('GET', './operaciones/boton_copiar_perfil.php?aleatorio='+aleatorio);
                    ajax.onreadystatechange=function() {
                        if (ajax.readyState==4) {
                            //mostrar resultados en esta capa
                            divBoton.innerHTML = ajax.responseText
                        }
                    }
                }

                ajax.send(null)
            }

            function checkAll() {
                var field = document.frmConsulta.list;
                document.frmConsulta.btnImprime.disabled=false;
                for (i = 0; i < field.length; i++)
                    field[i].checked = true ;
            }

            function uncheckAll() {
                var field = document.frmConsulta.list;
                document.frmConsulta.btnImprime.disabled=true;
                for (i = 0; i < field.length; i++)
                    field[i].checked = false ;
            }

            function CopiarPerfiles() {
                var field = document.frmConsulta.list;
                var de = document.getElementById('listObsocial').value;
                var a = document.getElementById('listObsocialDestino').value;
                var divProceso = document.getElementById('Proceso');

                if (confirm('¿ Seguro para Copiar Definiciones de Obra Social ' + de + ' a ' + a + '. Este Proceso Sobreescribirá Datos Previos que pudieran existir !')) {

                    var lista = '';

                    for (i = 0; i < field.length; i++) {
                        if (field[i].checked) {
                            if (field[i].value != undefined) {
                                var c = i+1;
                                lista = lista + '&orden' + c.toString() + '=' + field[i].value;
                            }
                        }
                    }

                    var aleatorio = Math.random();
                    var theURL = './operaciones/procesar_copia_perfiles.php?de='+de+'&a='+a+'&aleatorio='+aleatorio+lista;
   
                    divProceso.innerHTML = '<img src="anim.gif"> :: Copiando Perfiles ...';

                    ajax3=objetoAjax();
                    //uso del medoto GET
                    //indicamos el archivo que realizar� el proceso de paginar
                    //junto con un valor que representa el nro de pagina
                    ajax3.open('GET', theURL);
                    ajax3.onreadystatechange=function() {
                        if (ajax3.readyState==4) {
                            //mostrar resultados en esta capa
                            divProceso.innerHTML = ajax3.responseText
                        }
                        uncheckAll();
                        hidediv("botoncopiado");
                        hidediv("osdestino");
                        hidediv("listaos");
                        document.getElementById('listObsocial').focus();
                    }

                    ajax3.send(null)

                } else {
                    uncheckAll();
                    hidediv("botoncopiado");
                    hidediv("osdestino");
                    hidediv("listaos");
                    document.getElementById('listObsocial').focus();
                }
                var lista = document.getElementById('Lista');
                lista.innerHTML = '';
                var bt = document.getElementById('Botones');
                bt.innerHTML = '';
            }

            function chequear() {
                var field = document.frmConsulta.list;
                document.frmConsulta.btnImprime.disabled=true;
                for (i = 0; i < field.length; i++) {
                    if (field[i].checked) {
                        document.frmConsulta.btnImprime.disabled=false;
                    }
                }
            }

        </script>

        <link rel="stylesheet" type="text/css" href="/css1.css">

    </head>

    <body onLoad="javascript: if (Init()) {desde.focus()}; return true;">

        <div style="margin:auto;width:550px;text-align:center;">

            <form name="frmConsulta" method="post" onsubmit="return false">

                <?php
                echo '<input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" />';
                include('menu_admin.php');
                ?>

                <table width="550px" border="0">
                    <tr>
                        <td width="178px" align = "left">
                            Copiar Perfiles de Diagnósticos
                        </td>
                        <td width="322px" align = "left">
                            <hr>
                        </td>
                    </tr>

                </table>

                <table width="550px" border="0">

                    <tr>
                        <td width="70px" align = "right">
                            De Obra Social:
                        </td>
                        <td width="330px" align = "left">
                            <?
                            include('operaciones/lista_os_perfiles.php');
                            ?>
                        </td>

                    </tr>

                </table>

                <tr>
                    <td width="550px"><hr></td>
                </tr>

                <div id="Botones" align="left">
                </div>

                <div id="Proceso" align="left">
                </div>

                <div id="Destino" align="center">
                </div>

                <div id="Lista" align = 'left'>
                </div>

                <div id="mensaje" align = 'left'>
                </div>

            </form>

        </div>

    </body>
</html>