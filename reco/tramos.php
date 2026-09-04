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

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Tramos</title>

        <script src="../jscript/funcionajax.js"></script>
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
                ajax.open('GET', './operaciones/paginador_tramos.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio+'&codos='+codos);
                divContenido.innerHTML= '<img src="anim.gif">';
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
                ajax.open('GET', './operaciones/editar_tramo.php?aleatorio='+aleatorio+'&modo=1');
                divNuevo.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    if (verificarIE()) {
                        document.getElementById('codigo').focus();
                    } else {
                        document.frmeditar_tramos.codigo.focus();
                    }
                }

                ajax.send(null)
            }

            function ValidarId(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Id. tramo Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarDescrip(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Descripción Incorrecta ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarTope(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Tope Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarTramo(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Cantidad de Bonos Incorrecta ...!');
                        return false;
                    }
                    return true;
                }
            }

            function Registrar(id, descrip, tope, cantbonos, modo){
                var codos = document.getElementById('listObsocial').value;
                if (codos.length == 0) {
                    alert('Código Incorrecto ...!');
                    return false;
                }
                if (id.length == 0) {
                    alert('Id. Incorrecto ...!');
                    return false;
                }
                if (descrip.length == 0) {
                    alert('Descripción Incorrecta ...!');
                    return false;
                }
                if (tope.length == 0) {
                    alert('Tope Incorrecto ...!');
                    return false;
                }
                if (cantbonos.length == 0) {
                    alert('Cantidad de Bonos Incorrecta ...!');
                    return false;
                }

                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                //instanciamos el objetoAjax
                ajax1=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                if (modo == 1) {
                    ajax1.open("GET", './operaciones/registro_tramos.php?id='+id+'&descrip='+descrip+'&tope='+tope+'&cantbonos='+cantbonos+'&aleatorio='+aleatorio+'&codos='+codos, true);
                }
                if (modo == 2) {
                    ajax1.open("GET", './operaciones/actualizo_tramos.php?id='+id+'&descrip='+descrip+'&tope='+tope+'&cantbonos='+cantbonos+'&aleatorio='+aleatorio+'&codos='+codos, true);
                }
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax1.responseText;
                        Pagina(1, descrip, 'N');
                        LimpiarCampos();
                    }
                }

                //enviando los valores
                ajax1.send(null);
            }

            function Editar(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'E');
            }

            function EditarRegistro(id) {
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divEditar = document.getElementById('nuevo');
                ajax.open('GET', './operaciones/editar_tramo.php?id='+id+'&aleatorio='+aleatorio+'&modo=2&codos='+codos, true);
                divEditar.innerHTML= '<img src="anim.gif">';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divEditar.innerHTML = ajax.responseText
                    }
                    if (verificarIE()) {
                        document.getElementById('descrip').focus();
                    } else {
                        document.frmeditar_tramos.descrip.focus();
                    }
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function LimpiarCampos(){
                document.getElementById('codigo').value="";
                document.getElementById('descrip').value="";
                document.getElementById('tope').value="";
                document.getElementById('cantbonos').value="";
            }

            function RecargarTramo(){
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML = '';
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

            function Borrar(id) {                
                var codos = document.getElementById('listObsocial').value;
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraTramo.php?id='+id+'&codos='+codos, true);
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

            function ProcederBaja(id) {
                var aleatorio = Math.random();
                var codos = document.getElementById('listObsocial').value;
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_tramo.php?id='+id+'&aleatorio='+aleatorio+'&codos='+codos, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Eliminando, espere un momento ... ';
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
                divBuscar.innerHTML= '<img src="anim.gif">';
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
                if (verificarIE()) {
                    valor=document.getElementById('buscarvalor').value;
                } else {
                    valor=document.frmbuscar.buscarvalor.value;
                }
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

            <?php
            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
            include('menu_admin.php');
            ?>

            <div id="botones">
                <?
                echo "<FIELDSET>";
                echo "<LEGEND>Mantenimiento de Tramos</LEGEND>";

                echo '<table align="center" width="500px">';
                echo '<tr>';
                echo '<td width="50px" align="right">Obra Social:</td>';
                echo '<td width="200px" align = "left">';
                include('operaciones/lista_os_auditarordenes.php');
                echo '</td>';
                echo '</tr>';

                echo '</table>';

                echo '<table align="center"><tr>';
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

            <div id="nuevo" align="left">
            </div>

            <div id="buscar" align="center">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <table border="0px" width="500px">
                <tr>
                    <td width="10px"><b>Id.</b></td>
                    <td width="300px" align = "left"><b>Descripción del Tramo</b></td>
                    <td width="70px" align = "left"><b>Tope Práct.</b></td>
                    <td width="70px" align = "left"><b>Cant. Bonos</b></td>
                </tr>
            </table>
            <hr>

            <div id="contenido">
            </div>

        </div>

    </body>

    <script>
        Pagina(1, '', '');
        Init();
    </script>


</html>