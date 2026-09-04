<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];
if (isset($_GET['trans'])) {
    $trans = $_REQUEST['trans'];
}

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Medicos</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function ConsultarMedicos() {
                Pagina('', 1, '', '');
            }

            function Pagina(codos, nropagina, filtro, edbaja){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divBaja = document.getElementById('borrar');
                divBaja.innerHTML = '';
                divContenido = document.getElementById('contenido');
                var codos = document.getElementById('listObsocial').value;
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/paginador_medicos_cab.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio+'&codos='+codos);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null);
            }

            function Nuevo(){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divNuevo = document.getElementById('nuevo');
                var codos = document.getElementById('listObsocial').value;
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/editar_medico_cab.php?codos='+codos+'&aleatorio='+aleatorio+'&modo=1');
                divNuevo.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('nombremedico').focus();
                }

                ajax.send(null)
            }

            function ControlMedico(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarCapitas(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xdato);
                }
            }

            function ValidarEstado(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function Registrar(idprof, capitas, activo, modo){
                var codos = document.getElementById('listObsocial').value;
                if (idprof.length == 0) {
                    alert('Código Incorrecto ...!');
                    return false;
                }
                if (ValidarNumero(capitas)) {} else {
                    return false;
                }
    
                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                //instanciamos el objetoAjax
                ajax2=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php

                if (activo == 'on') {
                    var estado = 'S';
                } else {
                    var estado = 'N';
                }
                //alert('./operaciones/registro_medico_cab.php?codos='+codos+'&idprof='+idprof+'&capitas='+capitas+'&estado='+estado+'&aleatorio='+aleatorio);
                if (modo == 1) {
                    ajax2.open("GET", './operaciones/registro_medico_cab.php?codos='+codos+'&idprof='+idprof+'&capitas='+capitas+'&estado='+estado+'&aleatorio='+aleatorio, true);
                }
                if (modo == 2) {
                    ajax2.open("GET", './operaciones/actualizo_medico_cab.php?codos='+codos+'&idprof='+idprof+'&capitas='+capitas+'&estado='+estado+'&aleatorio='+aleatorio, true);
                }
                ajax2.onreadystatechange=function() {
                    if (ajax2.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax2.responseText;

                        //llamar a funcion para limpiar los inputs
                        //RecargarControl();
                        Pagina(codos, 1, idprof, 'N');
                        LimpiarCampos();
                    }

                }

                ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax2.send(null);
            }

            function Editar(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                var codos = document.getElementById('listObsocial').value;
                Pagina(codos, 1, '', 'E');
            }

            function EditarRegistro(codos, idprof) {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divEditar = document.getElementById('nuevo');
                ajax.open('GET', './operaciones/editar_medico_cab.php?codos='+codos+'&idprof='+idprof+'&aleatorio='+aleatorio+'&modo=2', true);
                divEditar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divEditar.innerHTML = ajax.responseText
                    }
                    document.getElementById('nombre').focus();
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function LimpiarCampos(){
                document.getElementById('capitas').value="";
                document.getElementById('estado').value="";
                document.getElementById('nombremedico').value="";
                document.getElementById('nombremedico').focus();
            }

            function RecargarMedico(){
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML = '';
                divRecargar = document.getElementById('nuevo');
                divRecargar.innerHTML = '';
            }

            function QuitarFiltro(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                var codos = document.getElementById('listObsocial').value;
                Pagina(codos, 1, '', 'N');
            }

            function Bajas(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                var codos = document.getElementById('listObsocial').value;
                Pagina(codos, 1, '', 'B');
            }

            function Borrar(codos, idprof) {
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraMedicoCab.php?codos='+codos+'&idprof='+idprof, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBaja(codos, id) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_medico_cab.php?codos='+codos+'&idprof='+id+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Eliminando Registro ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText;
                        if (divBorrar.innerHTML.length == 0) {
                            // Si la Baja fue OK, refrescamos la página
                            Pagina(codos, 1, '', 'N');
                        }
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function CancelarBaja() {
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                QuitarFiltro();
            }

            function Buscar() {
                divBuscar = document.getElementById('buscar');
                ajax=objetoAjax();
                ajax.open('GET', './operaciones/buscar.php');
                divBuscar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText
                    }
                    document.getElementById('buscarvalor').focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(id){
                var valor = document.getElementById('buscarvalor').value;
                var codos = document.getElementById('listObsocial').value;
                Pagina(codos, 1, valor, 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function BuscarValor(e, xbuscar) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xbuscar.length >= 0) {
                        var codos = document.getElementById('listObsocial').value;
                        Pagina(codos, 1, xbuscar, 'X');
                        divBuscar = document.getElementById('buscar');
                        divBuscar.innerHTML = '';
                    }
                }
            }

            function ValidarNombreMedico(e, nombre){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (nombre.length > 0) {
                        // Nos adelantamos porque sino el evento no lo toma
                        document.getElementById('listMedicos').disabled=false;
                        CargarMedicos(nombre);
                        return true;
                    } else {
                        alert('El Nombre debe Incluir al Menos un caracter  ...!');
                        return false;
                    }
                    return true;
                }
            }

            function CargarMedicos(xnombre) {
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divme  = document.getElementById('ListadoMedicos');
                ajaxme=objetoAjax();
                ajaxme.open("GET", "./operaciones/lista_medicos_ingordenes_medicoscab.php?codos=" + codos + "&nombre="+xnombre+"&aleatorio="+aleatorio);
   
                ajaxme.onreadystatechange=function() {
                    if (ajaxme.readyState==4) {
                        //mostrar resultados en esta capa
                        divme.innerHTML = ajaxme.responseText;
                        var n = document.getElementById('listMedicos').value;
                        if (n.length == 0) {
                            document.getElementById('listMedicos').disabled = true;
                            document.getElementById('capitas').disabled     = true;
                            document.getElementById('estado').disabled      = true;
                            document.getElementById('nombremedico').focus();
                        } else {
                            document.getElementById('listMedicos').disabled = false;
                            document.getElementById('capitas').disabled     = false;
                            document.getElementById('estado').disabled      = false;
                            document.getElementById('listMedicos').focus();
                        }
                    }
                }
                ajaxme.send(null);
            }

            function CancelarBusqueda(){
                ajax=objetoAjax();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                ajax.send(null);
            }

            function Salir()  {
                var aleatorio = Math.random();
                var host = location.protocol + '//' + location.hostname + '/usuarios.php?aleatorio=' + aleatorio;
                window.location.href = host;
            }

        </script>
        <link rel="stylesheet" type="text/css" href="css1.css">
    </head>

    <body>

        <div style="margin:auto;width:500px;text-align:center;">
            <?php include('menu_admin.php') ?>

            <div id="botones">
                <?
                echo "<FIELDSET>";
                echo "<LEGEND>Mantenimiento de Médicos de Cabecera</LEGEND>";

                echo '<table align="center"><tr>';
                echo '<td><a href="javascript://" onclick="Nuevo()">Nuevo</a></td>';
                echo '<td><a href="javascript://" onclick="Bajas()">Bajas</a></td>';
                echo '<td><a href="javascript://" onclick="Buscar()">Buscar</a></td>';
                echo '<td><a href="javascript://" onclick="QuitarFiltro()">Refrescar</a></td>';
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

            <table border='0px' width='500px'>
                <td bgcolor='#CCCCCC' width='100px' align='right'>Obra Social:</td>
                <td bgcolor='#CCCCCC' width='320px' align='left'>
                    <div id="obrassociales">
                        <?php include('operaciones/lista_osociales_medicos.php') ?>
                    </div>
                </td>
                <td bgcolor='#CCCCCC' width='80px'>
                    <a href="javascript://" onclick="ConsultarMedicos()">Consultar</a>
                </td>
            </table>

            <div id="contenido">
            </div>

            <script>
                ConsultarMedicos();
            </script>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>