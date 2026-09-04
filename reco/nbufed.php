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
        <title>Limite Practicas NBU</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function Pagina(nropagina, filtro, edbaja){
                //donde se mostrar� los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('contenido');
                divMensaje = document.getElementById('mensaje');
                divBaja = document.getElementById('borrar');
                divBaja.innerHTML = '';
                divMensaje.innerHTML= '';

                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_nbufederada.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif"> :: Cargando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                //como hacemos uso del metodo GET
                //colocamos null ya que enviamos
                //el valor por la url ?pag=nropagina

                ajax.send(null)
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
                ajax.open('GET', './operaciones/editar_nbufederada.php?aleatorio='+aleatorio+'&modo=1');
                divNuevo.innerHTML= '<img src="anim.gif"> :: Cargando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('codigo').focus();

                }
                //como hacemos uso del metodo GET
                //colocamos null ya que enviamos
                //el valor por la url ?pag=nropagina

                ajax.send(null)
            }
            
            function cargarDeterminacion(codigo){
                ajax=objetoAjax();
                divEdit = document.getElementById('det');
                ajax.open('GET', './operaciones/verificar_codigo_nbu.php?codigo='+codigo);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divEdit.innerHTML = ajax.responseText
                    }
                }
                ajax.send(null)
            }

            function ValidarCodigo(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length != 6) {
                        alert('Código Incorrecto ...!');
                        return false;
                    }
                    cargarDeterminacion(xdato);
                    return true;
                }
            }           

            function ValidarTopeAnual(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Tramo Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarNivel(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato >= 0) {
                        return true;
                    } else {
                        alert('Nivel Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function Registrar(codigo, nivel, tope_anual, modo){
                if (codigo.length != 6) {
                    alert('Código Incorrecto ...!');
                    return false;
                }               
                if (nivel.length == 0) {
                    alert('Nivel Incorrecta ...!');
                    return false;
                }
                if (tope_anual >= 0) {} else {
                    alert('Tope Anual Incorrecto ...!');
                    return false;
                }                
                
                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divNuevo = document.getElementById('nuevo');
                divNuevo.innerHTML = '';
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                //valores de las cajas de texto
                //instanciamos el objetoAjax
                ajax=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                if (modo == 1) {
                    ajax.open("GET", "./operaciones/registro_nbufederada.php?codigo="+codigo+"&nivel="+nivel+'&tope_anual='+tope_anual+'&aleatorio='+aleatorio, true);
                }
                if (modo == 2) {
                    ajax.open("GET", "./operaciones/actualizo_nbufederada.php?codigo="+codigo+"&nivel="+nivel+'&tope_anual='+tope_anual+'&aleatorio='+aleatorio, true);
                }
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;                        
                    }
                    Pagina(1, '', 'N');                    
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function Editar(){
                Pagina(1, '', 'E');
            }

            function EditarRegistro(codigo) {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divNuevo = document.getElementById('nuevo');
                ajax.open('GET', './operaciones/editar_nbufederada.php?codigo='+codigo+'&aleatorio='+aleatorio+'&modo=2', true);
                divNuevo.innerHTML= '<img src="anim.gif"> :: Cargando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('nivel').focus();

                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function RecargarControl(){
                divNuevo = document.getElementById('nuevo');
                divNuevo.innerHTML = '';
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML = '';
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
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraNbufederada.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }

                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send("codigo="+codigo);
            }
            
            function ProcederBaja(codigo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_nbufederada.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
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
                divBuscar.innerHTML= '<img src="anim.gif">';
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
                Pagina(1, valor, 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function BuscarValor(e, xbuscar) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xbuscar.length >= 0) {
                        Pagina(1, xbuscar, 'T');
                        divBuscar = document.getElementById('buscar');
                        divBuscar.innerHTML = '';
                    }
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
                <?
                echo "<FIELDSET>";
                echo "<LEGEND>Mantenimiento Topes de Prácticas en Ordenes</LEGEND>";

                echo '<table align="center"><tr>';
                echo '<td><a href="javascript://" onclick="Nuevo()">Nuevo</a></td>';
                echo '<td><a href="javascript://" onclick="Bajas()">Bajas</a></td>';
                echo '<td><a href="javascript://" onclick="Editar()">Editar</a></td>';
                echo '<td><a href="javascript://" onclick="Buscar()">Buscar</a></td>';
                echo '<td><a href="javascript://" onclick="QuitarFiltro()">Refrescar</a></td>';
                $trans = rand(1, 100000);
                $link = '?p111989=' . $usuario . '&reinit=' . $trans;
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
                    <td width="40px"><b>Codigo</b></td>
                    <td width="600px" align = "left"><b>Determinacion</b></td>
                    <td width="50px" align = "left"><b>Nivel</b></td>
                    <td width="100px" align = "left"><b>Tope Anual</b></td>
                </tr>
            </table>

            <div id="contenido">
                <?php include('operaciones/paginador_nbufederada.php') ?>
            </div>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>