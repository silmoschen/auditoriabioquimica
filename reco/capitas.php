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
        <title>Tramos</title>

        <script src="../jscript/funcionajax.js"></script>
        <script src="../jscript/funciones.js"></script>
        <script>

            function Pagina(nropagina, filtro, edbaja){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('contenido');
                var codos = document.getElementById('listObsocial').value;
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_capitas.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio+'&codos='+codos);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                //como hacemos uso del metodo GET
                //colocamos null ya que enviamos
                //el valor por la url ?pag=nropagina

                ajax.send(null);
            }

            function CambiarOS() {
                Pagina(1, '', '');
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
                ajax.open('GET', './operaciones/nueva_capita.php?aleatorio='+aleatorio+'&modo=1');
                divNuevo.innerHTML= '<img src="anim.gif"> :: Nuevo ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('periodo').focus();
                }

                ajax.send(null)
            }

            function ValidarPeriodo(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return VerificarPeriodo(xdato);
                }
            }

            function ValidarCapita(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Monto Cpatita Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarCapita2(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xdato);
                }
            }
            
            function ValidarCapita3(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xdato);
                }
            }

            function Registrar(periodo, capita, capita2, capita3){
                var codos = document.getElementById('listObsocial').value;
                if (codos.length == 0) {
                    alert('Código Incorrecto ...!');
                    return false;
                }
                if (periodo.length == 0) {
                    alert('Período Incorrecto ...!');
                    return false;
                }
                if (capita.length == 0) {
                    alert('Monto Cápita Incorrecto ...!');
                    return false;
                }
  
                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                //instanciamos el objetoAjax
                ajax1=objetoAjax();
                //uso del medoto POST
                ajax1.open("GET", './operaciones/registro_capita.php?codos='+codos+'&periodo='+periodo+'&capita='+capita+'&capita2='+capita2+'&capita3='+capita3+'&aleatorio='+aleatorio, true);
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax1.responseText;
                    }
                    Pagina(1, '', 'N');
                    LimpiarCampos();
                }

                //enviando los valores
                ajax1.send(null);
            }

            function Editar(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'E');
            }

            function LimpiarCampos(){
                document.getElementById('periodo').value="";
                document.getElementById('capita').value="";
                document.getElementById('capita2').value="";
                document.getElementById('capita3').value="";
                document.getElementById('periodo').focus();
            }

            function RecargarCapita(){                
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML = '';
                divRecargar = document.getElementById('nuevo');
                divRecargar.innerHTML = '';
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                
                Pagina(1, '', 'E');
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

            function Borrar(codos, periodo) {                
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraCapita.php?codos='+codos+'&periodo='+periodo, true);
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

            function ProcederBaja(codos, periodo) {                
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_capita.php?codos='+codos+'&periodo='+periodo, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Borrando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        Pagina(1, '', 'N');
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
                    if (xbuscar.length >= 0) {
                        Pagina(1, xbuscar, 'T');
                        divBuscar = document.getElementById('buscar');
                        divBuscar.innerHTML = '';
                    }
                }
            }

            function Salir()  {
                var aleatorio = Math.random();
                var host = location.protocol + '//' + location.hostname + '/usuarios.php?aleatorio=' + aleatorio;
                window.location.href = host;
            }

        </script>
        <link rel="stylesheet" type="text/css" href="css1.css" />
    </head>

    <body>

        <div style="margin:auto;width:550px;text-align:center;">

            <?php include('menu_admin.php') ?>

            <div id="botones">
                <?
                echo "<FIELDSET>";
                echo "<LEGEND>Definición de Cápitas en Obras Sociales</LEGEND>";

                echo '<table align="center" width="500px">';
                echo '<tr>';
                echo '<td width="50px" align="right">Obra Social:</td>';
                echo '<td width="200px" align = "left">';
                include('operaciones/lista_os_auditarordenes.php');
                echo '</td>';
                echo '</tr>';

                echo '</table>';

                echo '<table align="center"><tr>';
                echo '<td><a href="javascript://" onclick="Nuevo()">Dar de Alta Nueva Cápita</a></td>';
                echo '<td><a href="javascript://" onclick="QuitarFiltro()">Refrescar</a></td>';
                $trans = rand(1, 100000);
                $link = '?p111989=' . $usuario . '&reinit=' . $trans;

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

            <div id="contenido">
            </div>

        </div>

    </body>

    <script>
        Pagina(1, '', '');
        Init();
    </script>

</html>