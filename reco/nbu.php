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
        <title>Practicas NBU</title>

        <script src="../jscript/funcionajax.js"></script>         
        <script src="../jscript/funciones.js"></script>
        <script>

            function Pagina(nropagina, filtro, edbaja) {
                //donde se mostrar� los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('contenido');
                divBaja = document.getElementById('borrar');
                divBaja.innerHTML = '';

                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_nbu.php?pag=' + nropagina + '&filtro=' + filtro + '&edbaja=' + edbaja + '&aleatorio=' + aleatorio);
                divContenido.innerHTML = '<img src="anim.gif"> :: Cargando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }
                //como hacemos uso del metodo GET
                //colocamos null ya que enviamos
                //el valor por la url ?pag=nropagina

                ajax.send(null)
            }

            function Nuevo() {
                //donde se mostrará los registros
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divNuevo = document.getElementById('nuevo');
                ajax = objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/editar_nbu.php?aleatorio=' + aleatorio + '&modo=1');
                divNuevo.innerHTML = '<img src="anim.gif"> :: Cargando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
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

            function ValidarCodigo(e, xdato) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xdato.length != 6) {
                        alert('Código Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarDescrip(e, xdato) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Descripción Incorrecta ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarUnidad(e, xdato) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Unidad Incorrecta ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarTramo(e, xdato) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Tramo Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarNivel(e, xdato) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xdato == 1 || xdato == 3) {
                        return true;
                    } else {
                        alert('Nivel Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function Registrar(codigo, descrip, unidad, tramo, estado, modo, nivel) {
                if (codigo.length != 6) {
                    alert('Código Incorrecto ...!');
                    return false;
                }
                if (descrip.length == 0) {
                    alert('Descripción Incorrecta ...!');
                    return false;
                }
                if (unidad.length == 0) {
                    alert('Unidad Incorrecta ...!');
                    return false;
                }
                if (unidad.length == 0) {
                    alert('Tramo Incorrecto ...!');
                    return false;
                }

                if (nivel == 1 || nivel == 3) {
                } else {
                    alert('Nivel Incorrecto ...!');
                    return false;
                }

                var est = 0;
                if (estado == true) {
                    est = 1;
                }

                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divNuevo = document.getElementById('nuevo');
                divNuevo.innerHTML = '';
                divMensaje.innerHTML = '<img src="anim.gif"> :: Registrando ...';
                //valores de las cajas de texto
                //instanciamos el objetoAjax
                ajax = objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                if (modo == 1) {
                    ajax.open("GET", "./operaciones/registro_nbu.php?codigo=" + codigo + "&descrip=" + descrip + "&unidad=" + unidad + '&tramo=' + tramo + '&aleatorio=' + aleatorio + '&estado=' + est + '&nivel=' + nivel, true);
                }
                if (modo == 2) {
                    ajax.open("GET", "./operaciones/actualizo_nbu.php?codigo=" + codigo + "&descrip=" + descrip + "&unidad=" + unidad + '&tramo=' + tramo + '&aleatorio=' + aleatorio + '&estado=' + est + '&nivel=' + nivel, true);
                }
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;
                    }
                    //Pagina(1, descrip, 'N');
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function Editar() {
                Pagina(1, '', 'E');
            }

            function EditarRegistro(codigo) {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divNuevo = document.getElementById('nuevo');
                ajax.open('GET', './operaciones/editar_nbu.php?codigo=' + codigo + '&aleatorio=' + aleatorio + '&modo=2', true);
                divNuevo.innerHTML = '<img src="anim.gif"> :: Cargando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('descrip').focus();

                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function RecargarControl() {
                divNuevo = document.getElementById('nuevo');
                divNuevo.innerHTML = '';
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML = '';
            }

            function QuitarFiltro() {
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'N');
            }

            function Bajas() {
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'B');
            }

            function Borrar(codigo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraNbu.php?codigo=' + codigo + '&aleatorio=' + aleatorio, true);
                divBorrar.innerHTML = '<img src="anim.gif">';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                    }

                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send("codigo=" + codigo);
            }

            function ProcederBaja(codigo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_nbu.php?codigo=' + codigo + '&aleatorio=' + aleatorio, true);
                divBorrar.innerHTML = '<img src="anim.gif"> :: Borrando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText
                        if (divBorrar.innerHTML.length == 0) {
                            // Si la Baja fue OK, refrescamos la página
                            Pagina(1, '', 'N');
                        }
                    }
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send('codigo=' + codigo);
            }

            function CancelarBaja() {
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                QuitarFiltro();
            }

            function Buscar() {
                divBuscar = document.getElementById('buscar');
                ajax = objetoAjax();
                ajax.open('GET', './operaciones/buscar.php');
                divBuscar.innerHTML = '<img src="anim.gif">';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText
                    }
                    document.getElementById('buscarvalor').focus();
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(id) {
                var valor = document.getElementById('buscarvalor').value;
                Pagina(1, valor, 'T');
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
            }

            function BuscarValor(e, xbuscar) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    if (xbuscar.length >= 0) {
                        Pagina(1, xbuscar, 'T');
                        divBuscar = document.getElementById('buscar');
                        divBuscar.innerHTML = '';
                    }
                }
            }

            function CancelarBusqueda() {
                ajax = objetoAjax();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                ajax.send(null);
            }

            function Salir() {
                var aleatorio = Math.random();
                var host = location.protocol + '//' + location.hostname + '/usuarios.php?aleatorio=' + aleatorio;
                window.location.href = host;
            }

            function Unidades(codigo) {
                ajax = objetoAjax();
                var aleatorio = Math.random();
                divNuevo = document.getElementById('nuevo');
                ajax.open('GET', './operaciones/unidades_nbu.php?codigo=' + codigo + '&aleatorio=' + aleatorio + '&modo=2', true);
                divNuevo.innerHTML = '<img src="anim.gif"> :: Cargando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    document.getElementById('periodo').focus();
                    
                    RecargarUnidadesNBU(codigo);

                }
               
                ajax.send(null);
            }
            
             function CerrarUnidades() {
                Pagina(1, '', 'N');                
                divBuscar = document.getElementById('nuevo');
                divBuscar.innerHTML = '';               
            }

            function ValidarUnidad(e, xmonto) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return ValidarNumero(xmonto);
                }
            }

            function ValidarPeriodo(e, xperiodo) {
                e = (window.event) ? event : e;
                intKey = (e.keyCode) ? e.keyCode : e.charCode;
                if (intKey == 13) {
                    return VerificarPeriodo(xperiodo);
                }
            }
            
            function registrarUnidadNBU(codigo, periodo, unidad) {
                if (codigo.length != 6) {
                    alert('Código Incorrecto ...!');
                    return false;
                }
                if (unidad.length == 0) {
                    alert('Unidad Incorrecta ...!');
                    return false;
                }

                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                //divNuevo = document.getElementById('nuevo');
                //divNuevo.innerHTML = '';
                divMensaje.innerHTML = '<img src="anim.gif"> :: Registrando ...';
                //valores de las cajas de texto
                //instanciamos el objetoAjax
                ajax = objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php                
                ajax.open("GET", "./operaciones/registro_nbu_unidad.php?codigo=" + codigo + "&periodo=" + periodo + '&unidad=' + unidad + '&aleatorio=' + aleatorio);
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;
                        document.getElementById('periodo').value = '';
                        document.getElementById('unidad').value = '';
                    }
                    RecargarUnidadesNBU(codigo);
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }
            
            function RecargarUnidadesNBU(codigo){
                var aleatorio = Math.random();         

                //donde se mostrar los registros
                divContenido = document.getElementById('contenido');
                divContenido.innerHTML = '';

                var ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_unidades_nbu.php?codigo='+codigo+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText
                    }
                }

                ajax.send(null);
            }
            
            function BorrarUnidad(codigo, periodo) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_unidades_nbu.php?codigo=' + codigo + '&periodo=' + periodo + '&aleatorio=' + aleatorio, true);                
                divBorrar.innerHTML = '<img src="anim.gif"> :: Borrando ...';
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        //mostrar resultados en esta capa
                        divBorrar.innerHTML = ajax.responseText                        
                    }
                    RecargarUnidadesNBU(codigo);
                }

                ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send('codigo=' + codigo);
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
                echo "<LEGEND>Mantenimiento de Determinaciones NBU</LEGEND>";

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

            <div id="nuevo">
            </div>

            <div id="buscar">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>          

            <div id="contenido">
<?php include('operaciones/paginador_nbu.php') ?>
            </div>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>