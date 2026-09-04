<script>   
   var aleatorio = Math.random();
   var host = 'http://www.shmsoft.com.ar';
   window.location.href = host;
</script> 

<?
session_start();
$_SESSION['name'] = "auditoria";
$_SESSION['susuarios'] = "";
$_SESSION['susuario'] = "";
$_SESSION['spass'] = "";
$_SESSION['snombre'] = "";
$_SESSION['sdireccion'] = "";
$_SESSION['stelefono'] = "";
$_SESSION['semail'] = "";
$_SESSION['nivel'] = "";
$_SESSION['parametro1'] = "0";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Auditoría de Obras Sociales</title>
        <link rel="stylesheet" type="text/css" href="css1.css" />
        <script src="../jscript/funcionajax.js"></script>
        <script>
            function Init() {
                document.frm_login_usuario.usuario.focus();
                return true;
            }

            function ValidarUsuario(e, xusuario){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xusuario.length == 0) {
                        alert('Usuario Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function ValidarPass(e, xpass){
                e= (window.event)? event : e;
                intKey = (e.keyCode)? e.keyCode: e.charCode;
                if (intKey == 13) {
                    if (xpass.length == 0) {
                        alert('Password Incorrecto ...!');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function VerificarLogin(usuario, pass){
                if (usuario.length == 0) {
                    alert('Usuario Incorrecto ...!');
                    return false;
                }
                if (pass.length == 0) {
                    alert('Password Incorrecto ...!');
                    return false;
                }

                cargar = false;

                divMensaje = document.getElementById('Mensaje');
                ajax=objetoAjax();
                var aleatorio = Math.random();
                ajax.open('GET', '../operaciones/verificar_login_usuario.php?ssusuario=' + usuario + '&mmtx=' + pass+'&aleatorio='+aleatorio);
                ajax.onreadystatechange=function() {
                    if (ajax.readyState==4) {
                        //mostrar resultados en esta capa
                        var contenido = ajax.responseText;
                        divMensaje.innerHTML = contenido;
                        if (contenido.indexOf('Login OK') > -1) {
                            cargar = true;
                        }
                    }

                    if (cargar) {
                        CargarModulo(usuario);
                    } else {
                        document.getElementById('usuario').focus();
                    }
                }
                ajax.send(null);
            }

            function CargarModulo(usuario) {
                var aleatorio = Math.random();
                var host = location.protocol + '//' + location.hostname + '/newhtml_menu.php?aleatorio='+aleatorio+'&p111989='+usuario;
                window.location.href = host;
            }

        </script>

    </head>

    <body>

        <div style="margin:auto;width:300px;text-align:center;">

            <form name="frm_login_usuario" method="post" onsubmit="return false">

                <?php include('operaciones/entidad.php') ?>
                <hr>

                <table width="278" border="0">
                    <tr>
                        <td colspan="2"><div align="center"><b>shmSOFT Sistema Auditoría Bioquímica On Line</b></div></td>
                    </tr>
                    <tr>
                        <td width="91"><div align="right">Usuario:</div></td>
                        <td width="177">
                            <div align="left">
                                <input name="usuario" id="usuario" type="text" style="font-size: 10px;" maxlength="10" width="15" size="12" onkeypress="javascript: if(ValidarUsuario(event, usuario.value)) {pass.focus()}; return true">
                            </div></td>
                    </tr>
                    <tr>
                        <td><div align="right">Contraseña:</div></td>
                        <td>
                            <div align="left">
                                <input name="pass" id="pass" type="password" style="font-size: 10px;" maxlength="10" width="15" size="12"  onkeypress="javascript: if(ValidarPass(event, pass.value)) {Ingresar.focus()}; return true">
                            </div></td>
                    </tr>
                    <tr>
                        <td width="91"><div align="right"></div></td>
                        <td width="177">

                            <div align="left">
                                <input type="button" value="Ingresar" name="Ingresar" class="button gray small" onClick="VerificarLogin(usuario.value, pass.value); return false" />
                            </div></td>
                    </tr>
                </table>

                <hr>

                <div id="CargaDatos" align="center">
                </div>

                <div id="Mensaje" align="center">
                </div>

                <script>
                    document.getElementById('usuario').focus();
                </script>

            </form>

        </div>

    </body>

</html>
