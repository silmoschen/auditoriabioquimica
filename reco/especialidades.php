<?php
session_start();
//session_register('susuario');
//session_register('spass');
//session_register('snombre');
//session_register('sdireccion');
//session_register('stelefono');
//session_register('semail');

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
        <title>Tabla de Especialidades</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function Pagina(nropagina, filtro, edbaja){
                //donde se mostrar los registros
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML = '';
                divBaja = document.getElementById('borrar');
                divBaja.innerHTML = '';
                var aleatorio = Math.random();
                divContenido = document.getElementById('contenido');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                divContenido.innerHTML= '<img src="anim.gif"> :: Cargando ...';
                ajax.open('GET', './operaciones/paginador_especialidades.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                ajax.send('pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja)
            }

            function Nuevo(){
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divNuevo = document.getElementById('nuevo');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/editar_especialidad.php?aleatorio='+aleatorio+'&modo=1');
                divNuevo.innerHTML= '<img src="anim.gif"> :: Nuevo ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('codigo').focus();
                }

                ajax.send(null)
            }

            function ValidarCodigo(e, xcodigo) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xcodigo.length == 0) {
                        alert('Codigo Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarDescrip(e, xdescrip) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdescrip.length == 0) {
                        alert('Descripción Incorrecta ...!');
                        return false;
                    }
                    return true;
                }
            }

            function Registrar(codigo, descrip,  modo){
                if (codigo.length == 0) {
                    alert('Código de Especialidad Incorrecto ...!');
                    return false;
                }
                if (descrip.length == 0) {
                    alert('Descripción de Práctica Incorrecta ...!');
                    return false;
                }

                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                divNuevo = document.getElementById('nuevo');
                divNuevo.innerHTML= '';
                var ajax1=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                if (modo == 1) {
                    ajax1.open("GET", "./operaciones/registro_especialidad.php?codigo="+codigo+"&descrip="+descrip+'&modo=1'+'&aleatorio='+aleatorio,true);
                }
                if (modo == 2) {
                    ajax1.open("GET", "./operaciones/registro_especialidad.php?codigo="+codigo+"&descrip="+descrip+'&modo=2'+'&aleatorio='+aleatorio,true);
                }
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax1.responseText;
                        Pagina(1, descrip, 'N');
                    }
                }

                ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax1.send(null);
            }

            function Editar(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'E');
            }

            function EditarRegistro(codigo) {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divEditar = document.getElementById('nuevo');
                ajax.open('GET', './operaciones/editar_especialidad.php?id='+codigo+'&aleatorio='+aleatorio+'&modo=2', true);
                divEditar.innerHTML= '<img src="anim.gif"> :: Editando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divEditar.innerHTML = ajax.responseText
                    }
                    document.getElementById('descrip').focus();
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function LimpiarCampos(){
                document.getElementById('codigo').value="";
                document.getElementById('descrip').value="";
            }

            function RecargarControl(){
                divRecargar = document.getElementById('nuevo');
                divRecargar.innerHTML = '';
            }

            function QuitarFiltro(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'N');
            }

            function Bajas(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'B');
            }

            function Borrar(codigo) {
                var aleatorio = Math.random();
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraEspecialidad.php?id='+codigo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif">';
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

            function ProcederBaja(codigo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_especialidad.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Borrando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        if (divBorrar.innerHTML.length == 0) {
                            // Si la Baja fue OK, refrescamos la página
                            Pagina(1, '', 'N');
                        }
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send('codigo='+codigo);
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
                divBuscar.innerHTML= '<img src="anim.gif"> :: Buscando ...';
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
                valor=document.getElementById('buscarvalor').value;
                Pagina(1, valor, 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function BuscarValor(e, xbuscar) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    ProcederBuscar(1);
                }
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

        <div style="margin:auto;width:550px;text-align:center;">
            <?php include('menu_admin.php') ?>

            <div id="botones">
                <?php
                echo "<FIELDSET>";
                echo "<LEGEND>Mantenimiento de Datos de Efectores/Profesionales</LEGEND>";


                echo '<table align = "center"><tr>';
                echo '<td><a href="javascript://" onclick="Nuevo()">Nuevo</a></td>';
                echo '<td><a href="javascript://" onclick="Bajas()">Bajas</a></td>';
                echo '<td><a href="javascript://" onclick="Editar()">Editar</a></td>';
                echo '<td><a href="javascript://" onclick="Buscar()">Buscar</a></td>';
                echo '<td><a href="javascript://" onclick="QuitarFiltro()">Refrescar</a></td>';
                $trans = rand(1, 100000);
                $link = '?p111989=' . $usuario . '&reinit=' . $trans;
                //echo '<td><a href="/newhtml.php' . $link . '">Salir</a></td>';

                echo '</tr></table>';
                echo "</FIELDSET>";
                ?>
            </div>

            <div id="nuevo">
            </div>

            <div id="buscar">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <table border="0px">
                <tr>
                    <td width="30px"><b>Cód.</b></td>
                    <td width="500px" align = "left"><b>Especialidad</b></td>

                </tr>
            </table>

            <div id="contenido">
                <?php include('operaciones/paginador_especialidades.php') ?>
            </div>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>