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
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Padron de Afiliados a Obras Sociales</title>

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function ConsultarAfiliados(){
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divCarga = document.getElementById('UICarga');
                divCarga.innerHTML = '';
                codos=document.getElementById('listObsocial').value;
                PagAfiliado(1, '', codos, '');
            }

            function Botones(){
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
                ajax.open('GET', './operaciones/lista_afiliados.php?pag='+nropagina+'&filtro='+filtro+'&edbaja='+edbaja+'&codos='+codos+'&aleatorio='+aleatorio+'&tipofiltro='+tipofiltro);
                divContenido.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divContenido.innerHTML = ajax.responseText;
                        Botones();
                    }
                }
                ajax.send(null)
            }

            function Nuevo(){
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                codos=document.getElementById('listObsocial').value;
                divNuevo = document.getElementById('UICarga');
                ajax=objetoAjax();
                //uso del medoto GET
                //indicamos el archivo que realizar� el proceso de paginar
                //junto con un valor que representa el nro de pagina
                ajax.open('GET', './operaciones/editar_afiliado.php?codos='+codos+'&aleatorio='+aleatorio+'&modo=1');
                divNuevo.innerHTML= '<img src="anim.gif"> :: Nuevo ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divNuevo.innerHTML = ajax.responseText;
                    }
                    document.getElementById('nrodoc').focus();
                }
                ajax.send(null)
            }

            function ValidarNrodoc(e, xnrodoc) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xnrodoc.length == 0) {
                        alert('Número de Documento Incorrecto ...!')
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarNombre(e, xnombre) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xnombre.length == 0) {
                        alert('Nombre Incorrecto ...!')
                        return false;
                    } else {
                        return true;
                    }
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

            function ValidarObservacion(e, xobs) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xobs.length >= 0) {
                        return true;
                    }
                }
            }

            function ValidarFechaNac(e, xfecha) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xfecha.length >= 0) {
                        return true;
                    }
                }
            }

            function ValidarBeneficio(e, xbeneficio) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xbeneficio.length >= 0) {
                        return true;
                    }
                }
            }

            function ValidarParentesco(e, xparentesco) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xparentesco.length >= 0) {
                        return true;
                    }
                }
            }

            function ValidarSexo(e, xsexo) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xsexo.length == 0) {
                        return true;
                    } else {
                        if (xsexo == 'F' || xsexo == 'M') {
                            return true;
                        } else {
                            alert('Las Opciones son F ó M ...!');
                            return false;
                        }
                    }
                }

            }

            function ValidarTipodoc(e) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }
            
            function ValidarEnter(e) {
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function RegistrarAfiliado(codos, nrodoc, nombre, observac, fechanac, direccion, id_beneficio, id_parentesco, sexo, tipo_doc, inactivo, modo, xretiva){
                if (codos.length == 0) {
                    alert('Obra Social Incorrecta ...!');
                    return false;
                }
                if (nrodoc.length == 0) {
                    alert('Número de Documento Incorrecto ...!');
                    return false;
                }
                if (nombre.length == 0) {
                    alert('Nombre Incorrecto ...!');
                    return false;
                }
                if (sexo.length > 0) {
                    if (sexo == 'F' || sexo == 'M') {
                    } else {
                        alert('Las Opciones son F ó M ...!');
                        return false;
                    }
                }

                var xinactivo = '';
                if (inactivo) {
                    xinactivo = 'S';
                }

                var codos = document.getElementById('listObsocial').value;
                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                divNuevo = document.getElementById('UICarga');
                divNuevo.innerHTML= '';
                //valores de las cajas de texto
  
                //instanciamos el objetoAjax
                ajax1=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                var depto = 'S';
                if (modo == 1) {
                    ajax1.open("GET", "./operaciones/registro_afiliado.php?codos="+codos+"&nrodoc="+nrodoc+"&nombre="+nombre+"&observacion="+observac+"&fechanac="+fechanac+'&aleatorio='+aleatorio+'&depto='+depto+'&direccion='+direccion+'&id_beneficio='+id_beneficio+'&id_parentesco='+id_parentesco+'&sexo='+sexo+'&tipo_doc='+tipo_doc+'&retiva='+xretiva);
                }
                if (modo == 2) {
                    ajax1.open("GET", "./operaciones/actualizo_afiliado.php?codos="+codos+"&nrodoc="+nrodoc+"&nombre="+nombre+"&observacion="+observac+"&fechanac="+fechanac+'&aleatorio='+aleatorio+'&depto='+depto+'&direccion='+direccion+'&id_beneficio='+id_beneficio+'&id_parentesco='+id_parentesco+'&sexo='+sexo+'&tipo_doc='+tipo_doc+'&inactivo='+xinactivo+'&retiva='+xretiva);
                }
                ajax1.onreadystatechange=function() {
                    if (ajax1.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax1.responseText;
                    }
                    document.getElementById('tipofiltro').value = '2';
                    PagAfiliado(1, nrodoc, codos, 'T');
                }

                ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax1.send(null);
            }

            function LimpiarCampos(){
                document.getElementById('nrodoc').value="";
                document.getElementById('nombre').value="";
                document.getElementById('observac').value="";
                document.getElementById('fechanac').value="";
                document.getElementById('direccion').value="";
                document.getElementById('id_beneficio').value="";
                document.getElementById('id_parentesco').value="";
                document.getElementById('sexo').value="";
                document.getElementById('nrodoc').focus();
            }

            function Recargar(){
                codos=document.getElementById('listObsocial').value;
                divRecargar = document.getElementById('UICarga');
                divRecargar.innerHTML = '';
            }

            function BuscarPorNombre() {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divCarga = document.getElementById('UICarga');
                divCarga.innerHTML = '';
                divBoton = document.getElementById('botones');
                divBoton.innerHTML = '';
                divConn = document.getElementById('contenido');
                divConn.innerHTML = '';
                divBorra = document.getElementById('borrar');
                divBorra.innerHTML = '';

                document.getElementById('tipofiltro').value = '1';
    
                var ajax = objetoAjax();
                ajax.open('GET', './operaciones/buscar.php?aleatorio='+aleatorio);
                divBuscar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText;
                    }
                    document.getElementById('buscarvalor').focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function BuscarPorDocumento() {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divCarga = document.getElementById('UICarga');
                divCarga.innerHTML = '';
                divBoton = document.getElementById('botones');
                divBoton.innerHTML = '';
                divConn = document.getElementById('contenido');
                divConn.innerHTML = '';
                divBorra = document.getElementById('borrar');
                divBorra.innerHTML = '';

                document.getElementById('tipofiltro').value = '2';

                var ajax=objetoAjax();
                ajax.open('GET', './operaciones/buscar.php?aleatorio='+aleatorio);
                divBuscar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText;
            
                    }
                    document.getElementById('buscarvalor').focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function BuscarPorNroBeneficio() {
                var aleatorio = Math.random();
                divBuscar = document.getElementById('buscar');
                divCarga = document.getElementById('UICarga');
                divCarga.innerHTML = '';
                divBoton = document.getElementById('botones');
                divBoton.innerHTML = '';
                divConn = document.getElementById('contenido');
                divConn.innerHTML = '';
                divBorra = document.getElementById('borrar');
                divBorra.innerHTML = '';

                document.getElementById('tipofiltro').value = '3';

                var ajax=objetoAjax();
                ajax.open('GET', './operaciones/buscar.php?aleatorio='+aleatorio);
                divBuscar.innerHTML= '<img src="anim.gif"> :: Procesando ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divBuscar.innerHTML = ajax.responseText;

                    }
                    document.getElementById('buscarvalor').focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
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
                ajax=objetoAjax();
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                ajax.send(null);
            }

            function Bajas(){
                codos=document.getElementById('listObsocial').value;
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                PagAfiliado(1, '', codos, 'B');
            }

            function Borrar(nrodoc) {
                var aleatorio = Math.random();
                codos=document.getElementById('listObsocial').value;
                ajax=objetoAjax();
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/BorraAfiliado.php?codos='+codos+'&nrodoc='+nrodoc+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif">  :: Procesando';
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

            function ProcederBaja(codos, nrodoc) {
                var aleatorio = Math.random();
                divConn = document.getElementById('contenido');
                divConn.innerHTML = '';
                divBorrar = document.getElementById('borrar');
                ajax.open('GET', './operaciones/baja_afiliado.php?codos='+codos+'&nrodoc='+nrodoc+'&aleatorio='+aleatorio, true);
                divBorrar.innerHTML= '<img src="anim.gif"> :: Eliminando ...';
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

            function CancelarBaja() {
                divBorrar = document.getElementById('borrar');
                divBorrar.innerHTML = '';
                QuitarFiltro();
            }

            function Editar(){
                var codos = document.getElementById('listObsocial').value;
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                PagAfiliado(1, '', codos, 'E');
            }

            function EditarRegistro(nrodoc) {
                var aleatorio = Math.random();
                codos=document.getElementById('listObsocial').value;
                divBuscar = document.getElementById('buscar');
                divBuscar.innerHTML = '';
                divEditar = document.getElementById('UICarga');
                ajax.open('GET', './operaciones/editar_afiliado.php?codos='+codos+'&nrodoc='+nrodoc+'&aleatorio='+aleatorio+'&modo=2', true);
                divEditar.innerHTML= '<img src="anim.gif"> :: Editando Registro ...';
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divEditar.innerHTML = ajax.responseText
                    }
                    document.getElementById('listTipoDoc').focus();
                }
                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
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
            <?php include('menu_admin.php'); ?>

            <FIELDSET>
                <LEGEND>Mantenimiento Afiliados a Obras Sociales</LEGEND>

                <table width="500px" border="0px">
                    <tr>
                        <td width="100" align = "right">Obra Social:</td>
                        <td width="300" align = "left">
                            <?php include('operaciones/lista_obrassociales_padron.php') ?>
                        </td>
                        <td width="130" align = "left">
                            <?php
                            $trans = rand(1, 100000);
                            $link = '?p111989=' . $usuario . '&reinit=' . $trans;
                            //echo '<a href="/newhtml.php' . $link . '">Salir</a>';
                            ?>
                        </td>
                    </tr>
                </table>

                <table width="500px" border="0px">
                    <?php
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td width="100px" align="right">Buscar por:</td>';
                            echo '<td width="60px" align="center">';
                            echo '<a href="javascript://" onclick="BuscarPorNombre()">Nombre</a>';
                            echo '</td>';
                            echo '<td width="60px">';
                            echo '<a href="javascript://" onclick="BuscarPorDocumento()">Documento</a>';
                            echo '</td>';
                            echo '<td width="80px">';
                            echo '<a href="javascript://" onclick="BuscarPorNroBeneficio()">Nro. Benefio</a>';
                            echo '</td>';
                            echo '<td width="80px" align="right">';
                            echo '<a href="javascript://" onclick="Nuevo()">Altas</a>';
                            echo '</td>';
                            echo '<td width="200px"></td>';
                            echo '</tr>';
                    ?>
                </table>

            </FIELDSET>

            <div id="buscar" align="center">
            </div>

            <div id="botones" align="center">
            </div>

            <div id="UICarga" align="center">
            </div>

            <div id="mensaje" align="center">
            </div>

            <div id="borrar" align="center">
            </div>

            <div id="contenido" align="center">
            </div>

            <input type="hidden" name="tipofiltro" id="tipofiltro" value="" />

        </div>

    </body>

    <script>
        document.getElementById('listObsocial').focus();
    </script>

</html>