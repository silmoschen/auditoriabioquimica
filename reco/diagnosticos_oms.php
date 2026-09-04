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
        <title>Diagnosticos OMS</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function Pagina(nropagina, filtro, edbaja){
                //donde se mostrar los registros
                var aleatorio = Math.random();
                divContenido = document.getElementById('contenido');
                divBaja = document.getElementById('borrar');
                divBaja.innerHTML = '';
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/paginador_diagnosticos_oms.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
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
                ajax.open('GET', './operaciones/editar_diagnostico_oms.php?aleatorio='+aleatorio+'&modo='+1);
                divNuevo.innerHTML= '<img src="anim.gif"> :: Nuevo ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText
                    }
                    if (verificarIE()) {
                        document.getElementById('oms_cod').focus();
                    } else {
                        document.frmeditar_diagnosticos_oms.oms_cod.focus();
                    }
                }

                ajax.send(null)
            }

            function ValidarOms_Cod(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Código Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarClave(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Clave Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarOrden(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarIndice(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarCodRap(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdato.length == 0) {
                        alert('Código Rápido Incorrecto ...!');
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
                        alert('Descripción Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarOms(e, xdato) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    var s = xdato.toUpperCase();
                    if (s == 'S' || s == 'N') {
                        return true;
                    } else {
                        alert('Código Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function Registrar(oms_cod, clave, orden, indice, codrap, descrip, oms, modo){
                if (oms_cod.length == 0) {
                    alert('Código Incorrecto ...!');
                    return false;
                }
                if (clave.length == 0) {
                    alert('Clave Incorrecta ...!');
                    return false;
                }
                if (descrip.length == 0) {
                    alert('Descripción Incorrecta ...!');
                    return false;
                }

                var s = oms.toUpperCase();
                if (s == 'S' || s == 'N') {
                } else {
                    alert('Código Incorrecto ...!');
                    return false;
                }

                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando';
                divNuevo = document.getElementById('nuevo');
                divNuevo.innerHTML = '';
                //instanciamos el objetoAjax
                ajax=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                if (modo == 1) {
                    ajax.open("GET", "./operaciones/registro_diagnostico_oms.php?oms_cod="+oms_cod+"&clave="+clave+"&orden="+orden+"&indice="+indice+"&codrap="+codrap+"&descrip="+descrip+"&oms="+oms+"&aleatorio="+aleatorio,true);
                }
                if (modo == 2) {
                    ajax.open("GET", "./operaciones/actualizo_diagnosticos_oms.php?oms_cod="+oms_cod+"&clave="+clave+"&orden="+orden+"&indice="+indice+"&codrap="+codrap+"&descrip="+descrip+"&oms="+oms+"&aleatorio="+aleatorio,true);
                }
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;

                        //llamar a funcion para limpiar los inputs
                        Pagina(1, descrip, 'N');
                        LimpiarCampos();
                    }
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function Editar(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                Pagina(1, '', 'E');
            }

            function EditarRegistro(oms_cod) {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divEditar = document.getElementById('nuevo');
                ajax.open('GET', './operaciones/editar_diagnostico_oms.php?oms_cod='+oms_cod+'&aleatorio='+aleatorio+'&modo=2', true);
                divEditar.innerHTML= '<img src="anim.gif"> :: Editando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divEditar.innerHTML = ajax.responseText
                    }
                    if (verificarIE()) {
                        document.getElementById('clave').focus();
                    } else {
                        document.frmeditar_diagnosticos_oms.clave.focus();
                    }

                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function LimpiarCampos(){
                if (verificarIE()) {
                    document.getElementById('oms_cod').value="";
                    document.getElementById('clave').value="";
                    document.getElementById('orden').value="";
                    document.getElementById('indice').value="";
                    document.getElementById('codrap').value="";
                    document.getElementById('descrip').value="";
                    document.getElementById('oms').value="";
                } else {
                    document.frmeditar_diagnosticos_oms.oms_cod.value="";
                    document.frmeditar_diagnosticos_oms.clave.value="";
                    document.frmeditar_diagnosticos_oms.orden.value="";
                    document.frmeditar_diagnosticos_oms.indice.value="";
                    document.frmeditar_diagnosticos_oms.codrap.value="";
                    document.frmeditar_diagnosticos_oms.descrip.value="";
                    document.frmeditar_diagnosticos_oms.oms.value="";
                    document.frmeditar_diagnosticos_oms.oms_cod.focus();
                }
            }

            function RecargarDiagnosticoOMS(){
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                divRecargar = document.getElementById('nuevo');
                divRecargar.innerHTML = '';
                divBorrar = document.getElementById('mensaje');
                divBorrar.innerHTML = '';
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

            function Borrar(oms_cod) {
                var aleatorio = Math.random();
                oms_cod1 = oms_cod.toString();
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraDiagnosticos_oms.php?oms_cod='+oms_cod1+'&aleatorio='+aleatorio, true);
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

            function ProcederBaja(oms_cod) {
                var aleatorio = Math.random();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_diagnosticos_oms.php?oms_cod='+oms_cod+'&aleatorio='+aleatorio, true);
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
                    document.getElementById('buscarvalor').focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function ProcederBuscar(){
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
                echo "<LEGEND>Mantenimiento de Diagnósticos</LEGEND>";

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

            <div id="buscar">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <table border="0px">
                <tr>
                    <td width="35px"><b>Codigo</b></td>
                    <td width="40px"><b>Clave</b></td>
                    <td width="25px"><b>Orden</b></td>
                    <td width="27px"><b>In.</b></td>
                    <td width="360px" align = "left"><b>Descripción</b></td>
                    <td></td>
                </tr>
            </table>

            <div id="contenido">
                <?php include('operaciones/paginador_diagnosticos_oms.php') ?>
            </div>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>