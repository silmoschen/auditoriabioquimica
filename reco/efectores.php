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
$trans = $_REQUEST['trans'];
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Efectores</title>

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
                ajax.open('GET', './operaciones/paginador_efector.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&aleatorio='+aleatorio);
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
                ajax.open('GET', './operaciones/editar_efector.php?aleatorio='+aleatorio+'&modo=1');
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
                    if (xcodigo.length != 6) {
                        alert('Codigo Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarNombre(e, xnombre) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xnombre.length == 0) {
                        alert('Nombre Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarUsuario(e, xusuario) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xusuario.length == 0) {
                        alert('Usuario Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarPass(e, xpass) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xpass.length == 0) {
                        alert('Password Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarDireccion(e, xdir) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xdir.length >= 0) {
                        return true;
                    }
                }
            }

            function ValidarEmail(e, xemail) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarCuit(e, xcuit) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xcuit.length != 13) {
                        alert('Número de C.U.I.T. Incorrecto ...!');
                        return false;
                    }
                    return true;
                }
            }

            function ValidarEspecialidad(e) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarTipoPrestador(e) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarFechamat(e, fecha) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return ValidarFecha(fecha);
                }
            }

            function ValidarMatriculanac(e, m) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }
            
            function ValidarMatricula1(e, m) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }
            
            function ValidarParametro2(e, m) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }
            function ValidarParametro3(e, m) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {                   
                    return true;
                }
            }

            function Registrar(codigo, nombre, usuario, pass, direccion, nrocuit, email, especialidad, id_tipoprestador, fechamat, matricula_nac, modo, matricula1, parametro2, parametro3, nivel2){
                if (codigo.length == 0) {
                    alert('Código de Efector Incorrecto ...!');
                    return false;
                }
                if (nombre.length == 0) {
                    alert('Nombre Incorrecto ...!');
                    return false;
                }
                if (usuario.length == 0) {
                    alert('Usuario Incorrecto ...!');
                    return false;
                }
                if (pass.length == 0) {
                    alert('Password Incorrecto ...!');
                    return false;
                }
                if (ValidarFecha(fechamat)) {} else { return false; }

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
                    ajax1.open("GET", "./operaciones/registro_efector.php?codigo="+codigo+"&nombre="+nombre+"&usuario="+usuario+"&pass="+pass+'&aleatorio='+aleatorio+'&direccion='+direccion+'&nrocuit='+nrocuit+'&email='+email+'&id_especialidad='+especialidad+'&id_tipoprestador='+id_tipoprestador+'&fechamat='+fechamat+'&matricula_nac='+matricula_nac+'&matricula1='+matricula1+'&parametro2='+parametro2+'&parametro3='+parametro3+'&nivel2='+nivel2,true);
                }
                if (modo == 2) {
                    ajax1.open("GET", "./operaciones/actualizo_efector.php?codigo="+codigo+"&nombre="+nombre+"&usuario="+usuario+"&pass="+pass+'&aleatorio='+aleatorio+'&direccion='+direccion+'&nrocuit='+nrocuit+'&email='+email+'&id_especialidad='+especialidad+'&id_tipoprestador='+id_tipoprestador+'&fechamat='+fechamat+'&matricula_nac='+matricula_nac+'&matricula1='+matricula1+'&parametro2='+parametro2+'&parametro3='+parametro3+'&nivel2='+nivel2,true);
                }
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax1.responseText;
                    }
                    //llamar a funcion para limpiar los inputs
                    Pagina(1, nombre, 'N');
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
                ajax.open('GET', './operaciones/editar_efector.php?codigo='+codigo+'&aleatorio='+aleatorio+'&modo=2', true);
                divEditar.innerHTML= '<img src="anim.gif"> :: Editando ...';
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
                document.getElementById('codigo').value="";
                document.getElementById('nombre').value="";
                document.getElementById('usuario').value="";
                document.getElementById('pass').value="";
                document.getElementById('fechamat').value="";
                document.getElementById('matricula_nac').value="";
                document.getElementById('codigo').focus();
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
                ajax.open('GET', './operaciones/BorraEfector.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
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
                ajax.open('GET', './operaciones/baja_efector.php?codigo='+codigo+'&aleatorio='+aleatorio, true);
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
            
            function bajaEfector(estado){
                var aleatorio = Math.random();
                var codigo = document.getElementById('codigo').value;
                var baja = 'N';
                if (estado) baja = 'S';
                
                ajax.open('GET', './operaciones/inhabilitar_efector.php?codigo='+codigo+'&aleatorio='+aleatorio+'&baja='+baja, true);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                    }
                    document.getElementById('nombre').focus();
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
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

            <div id="nuevo" align="center">
            </div>

            <div id="buscar">
            </div>

            <div id="mensaje">
            </div>

            <div id="borrar">
            </div>

            <table border="0px">
                <tr>
                    <td width="30px"><b>Codigo</b></td>
                    <td width="500px" align = "left"><b>Nombre</b></td>

                </tr>
            </table>

            <div id="contenido">
                <?php include('operaciones/paginador_efector.php') ?>
            </div>

        </div>

    </body>

    <script>
        Init();
    </script>

</html>