<?php
session_start();

$usuario = $_SESSION['susuario'];
$pass = $_SESSION['spass'];
$nombre = $_SESSION['snombre'];

include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;

include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cEntidad.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cTipoPrestadores.php');
//variables POST

$tp = new cTipoPrestadores();
$obj = new cEntidad();
$obj->getObject();
$mnombre = $obj->getNombre();
$mdireccion = $obj->getDireccion();
$mtelefono = $obj->getTelefono();
$memail = $obj->getEmail();
$mparametro1 = $obj->getParametro1();
$mparametro2 = $obj->getParametro2();
$mparametro3 = $obj->getParametro3();
$mparametro4 = $obj->getParametro4();
$mcuit = $obj->getCuit();
$mtipo_entidad = $obj->getTipo_entidad();
$unifica_hist = $obj->getUnifica_hist();
$mdepartamento = $obj->getDepartamento();
$mobs1 = $obj->getObs1();

$opt = '';
if ($mparametro3 == '1') {
    $opt = 'checked = ' . '"' . 'checked' . '"';
}

$opt1 = '';
if ($unifica_hist == '1') {
    $opt1 = 'checked = ' . '"' . 'checked' . '"';
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="css1.css">

        <script src="../jscript/funcionajax.js"></script>
        <script>

            function Init() {
                document.frmeditar_entidad.nombre.focus();
                return true;
            }

            function ValidarNombre(e, nombre){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (nombre.length == 0) {
                        alert('Nombre de Entidad Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarDireccion(e, direccion){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (direccion.length == 0) {
                        alert('Dirección Incorrecta ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarDepartamento(e, departamento){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (departamento.length == 0) {
                        alert('Departamento Incorrecta ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarTelefono(e, telefono){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (telefono.length == 0) {
                        alert('Teléfono Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }


            function ValidarEmail(e, email){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (email.length == 0) {
                        alert('Email Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarParametro1(e, parametro1){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (parametro1.length == 0) {
                        alert('Parámetro Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarParametro1(e, parametro1){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (parametro1.length == 0) {
                        alert('Parámetro Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarParametro2(e, parametro2){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (parametro2.length == 0) {
                        alert('Parámetro Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarParametro3(e, parametro3){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarUnifica_hist(e, parametro3){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function ValidarParametro4(e, parametro4){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (parametro4.length == 0) {
                        return true;
                    }
                    if (parametro4.length != 6) {
                        alert('Código Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarCUIT(e, cuit){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (cuit.length != 13) {
                        alert('Nro. de C.U.I.T. Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarTipoPrestador(e){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    return true;
                }
            }

            function Registrar(nombre, direccion, telefono, email, parametro1, parametro2, parametro3, parametro4, cuit, tipo_entidad, unifica_hist, departamento, obs1){
                if (nombre.length == 0) {
                    alert('Nombre Incorrecto ...!');
                    return false;
                }
                if (direccion.length == 0) {
                    alert('Dirección Incorrecto ...!');
                    return false;
                }
                if (departamento.length == 0) {
                    alert('Departamento Incorrecto ...!');
                    return false;
                }
                if (telefono.length == 0) {
                    alert('Teléfono Incorrecta ...!');
                    return false;
                }
                if (email.length == 0) {
                    alert('Email Incorrecto ...!');
                    return false;
                }
                if (parametro1.length == 0) {
                    alert('Tamaño Código de Barras Incorrecto ...!');
                    return false;
                }
                if (parametro2.length == 0) {
                    alert('Fuente Código de Barras Incorrecto ...!');
                    return false;
                }
                if (cuit.length != 13) {
                    alert('Nro. de C.U.I.T. Incorrecto ...!');
                    return false;
                }

                if (document.getElementById('parametro3').checked) {
                    parametro3 = 'on';
                } else {
                    parametro3 = 'off';
                }

                //donde se mostrará lo resultados
                var aleatorio = Math.random();
                divMensaje = document.getElementById('mensaje');
                divMensaje.innerHTML= '<img src="anim.gif"> :: Registrando ...';
                //instanciamos el objetoAjax
                ajax=objetoAjax();
                //uso del medoto POST
                //archivo que realizará la operacion
                //registro.php
                ajax.open("GET", './operaciones/registro_entidad.php?nombre='+nombre+'&direccion='+direccion+'&telefono='+telefono+'&email='+email+ '&parametro1='+parametro1+'&parametro2='+parametro2+'&aleatorio='+aleatorio+'&parametro3='+parametro3+'&parametro4='+parametro4+'&cuit='+cuit+'&tipo_entidad='+tipo_entidad+'&unifica_hist='+unifica_hist+'&departamento='+departamento+'&obs1='+obs1, true);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        divMensaje.innerHTML = ajax.responseText;
                    }
                    document.frmeditar_entidad.Cerrar.focus();
                }

                ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                //enviando los valores
                ajax.send(null);
            }

            function Volver() {
                var aleatorio = Math.random();
                link = '?p111989=' + document.frmeditar_entidad.usuario.value + '&reinit=' + aleatorio;
                var host = location.protocol + '//' + location.hostname + '/newhtml.php' + link;
                window.location.href = host;
            }

        </script>

    <body onLoad="javascript: if (Init()) {nombre.focus()}; return true;">

        <div style="margin:auto;width:550px;text-align:center;">

            <?php
            echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
            include('menu_admin.php');
            ?>

            <form name="frmeditar_entidad" method="post" onsubmit="return false">
                <FIELDSET>
                    <LEGEND>Datos de la Entidad</LEGEND>

                    <table border="0px" align="left" width="500px">

                        <tr>
                            <td align="left"><div align="right">Nombre:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="nombre" type="text" maxlength="60" size="60" style="font-size: 10px;" value = ' . '"' . $mnombre . '"' . ' onkeypress="javascript: if(ValidarNombre(event, nombre.value)) {direccion.focus()}; return true" />';
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right">Dirección:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="direccion" type="text" maxlength="60" size="60" style="font-size: 10px;" value = ' . '"' . $mdireccion . '"' . ' onkeypress="javascript: if(ValidarDireccion(event, direccion.value)) {departamento.focus()}; return true" />';
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right">Departamento:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="departamento" type="text" maxlength="150" size="60" style="font-size: 10px;" value = ' . '"' . $mdepartamento . '"' . ' onkeypress="javascript: if(ValidarDepartamento(event, departamento.value)) {telefono.focus()}; return true" />';
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right">Teléfono:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="telefono" type="text" maxlength="60" size="60" style="font-size: 10px;" value = ' . '"' . $mtelefono . '"' . ' onkeypress="javascript: if(ValidarTelefono(event, telefono.value)) {email.focus()}; return true" />';
                                ?></td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right">Email:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="email" type="text" maxlength="60" size="60" style="font-size: 10px;" value = ' . '"' . $memail . '"' . ' onkeypress="javascript: if(ValidarEmail(event, email.value)) {parametro2.focus()}; return true" />';
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right"> Fuente Cód. Barras:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="parametro2" type="text" maxlength="40" size="35" style="font-size: 10px;" value = ' . '"' . $mparametro2 . '"' . ' onkeypress="javascript: if(ValidarParametro2(event, parametro2.value)) {cuit.focus()}; return true" />';
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right"> Nro. de C.U.I.T.:</div></td>
                            <td align="left"><?
                                echo '<input name="cuit" id="cuit" type="text" maxlength="13" size="15" style="font-size: 10px;" value = ' . '"' . $mcuit . '"' . ' onkeypress="javascript: if(ValidarCUIT(event, cuit.value)) {listTipoPrestadores.focus()}; return true" />';
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right">Tipo Prestador:</div></td>
                            <td align="left">
                                <?
                                $resultado = $tp->getListaTipoPrestadores();
                                ?>

                                <select name="listTipoPrestadores" id="listTipoPrestadores" style="width:300px" onkeypress="javascript: if(ValidarTipoPrestador(event)) {parametro1.focus()}; return true" />
                                <?php
                                while ($fila = mysql_fetch_array($resultado)) {
                                    if ($fila['id'] == $mtipo_entidad) {
                                        echo '<option selected value =' . '"' . $fila['id'] . '"' . '>' . substr($fila['descrip'], 0, 40) . '</option>';
                                    } else {
                                        echo '<option value =' . '"' . $fila['id'] . '"' . '>' . substr($fila['descrip'], 0, 40) . '</option>';
                                    }
                                }
                                ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right">Tamaño Cód. Barras:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="parametro1" type="text" maxlength="2" size="2" style="font-size: 10px;" value = ' . '"' . $mparametro1 . '"' . ' onkeypress="javascript: if(ValidarParametro1(event, parametro1.value)) {parametro3.focus()}; return true" />';
                                ?></td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right"></div></td>
                            <td align="left">
                                <?
                                echo '<input name="parametro3" id="parametro3" type="checkbox" style="font-size: 10px;"' . $opt . ' onkeypress="javascript: if(ValidarParametro3(event, parametro3.value)) {unifica_hist.focus()}; return true" />';
                                echo 'Mostrar Deter. Rechazadas al Imprimir Orden ?';
                                ?></td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right"></div></td>
                            <td align="left">
                                <?
                                echo '<input name="unifica_hist" id="unifica_hist" type="checkbox" style="font-size: 10px;"' . $opt1 . ' onkeypress="javascript: if(ValidarUnifica_hist(event, unifica_hist.value)) {parametro4.focus()}; return true" />';
                                echo 'Unificar Hist. Clínica Pacientes en Obras Sociales';
                                ?></td>
                        </tr>

                        <tr>
                            <td align="left"><div align="right">Incluir Cód. Recepción/Toma:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="parametro4" type="text" maxlength="6" size="6" style="font-size: 10px;" value = ' . '"' . $mparametro4 . '"' . ' onkeypress="javascript: if(ValidarParametro4(event, parametro4.value)) {obs1.focus()}; return true" />';
                                ?></td>
                        </tr>
                        
                        <tr>
                            <td align="left"><div align="right">Observación 1:</div></td>
                            <td align="left">
                                <?
                                echo '<input name="obs1" type="text" maxlength="255" size="65" style="font-size: 10px;" value = ' . '"' . $mobs1 . '"' . ' onkeypress="javascript: if(ValidarParametro4(event, parametro4.value)) {registrar.focus()}; return true" />';
                                ?></td>
                        </tr>

                        <tr>
                            <td align="right" WIDTH="20px">
                                <?
                                echo '<input type="button" class="button gray small" name="registrar" value="Registrar" onClick="Registrar(nombre.value, direccion.value, telefono.value, email.value, parametro1.value, parametro2.value, parametro3.value, parametro4.value, cuit.value, listTipoPrestadores.value, unifica_hist.value, departamento.value, obs1.value); return false" />';
                                ?>
                            </td>
                            <td align ="left"  WIDTH="20px">
                                <input type="button" class="button gray small" name="Cerrar" value="Cerrar" onclick="Volver(); return false" />
                            </td>
                        </tr>
                    </table>

                    <br />
                </FIELDSET>

            </form>

            <div id="mensaje"></div>

        </div>

        <script>
            Init();
        </script>

    </body>

</html>