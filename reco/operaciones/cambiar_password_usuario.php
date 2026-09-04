<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$usuario = $_REQUEST['usuario'];
$trans   = $_REQUEST['trans'];
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cUtiles.php");
$u = new cUtiles;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head><title>Cambiar Password</title>

<script src="../jscript/funciones.js"></script>
<script src="../jscript/funcionajax.js"></script>
<script>

function Init() {
    document.getElementById('passactual').focus();
    return true;
}

function ValidarPassActual(e, xpass){
 e= (window.event)? event : e;
 intKey = (e.keyCode)? e.keyCode: e.charCode;
 if (intKey == 13) {
    ValidarPass(xpass);    
    return true;
 }
}

function ValidarPass(xpass){
	//donde se mostrar los registros	
    var aleatorio = Math.random();
    divError = document.getElementById('Error');

    var usuario = document.getElementById('usuario').value;

    ajax=objetoAjax();    
    ajax.open('GET', '/operaciones/validar_password_usuario.php?usuario='+usuario+'&pass='+xpass+'&aleatorio='+aleatorio);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//mostrar resultados en esta capa
			contenido = ajax.responseText;
            divError.innerHTML = contenido;

            var found = true;

            var cont  = contenido.substring(0, 10);
            var cont1 = contenido.substring(0, 3);
            if (cont.length > 0 & cont1 != '***') {  // En base al contenido del TAG se si el objeto existe o no
              found = false;
            }

		}

        if (found) {
            document.getElementById('nuevopass').disabled = true;
            document.getElementById('confirmapass').disabled = true;
        } else {
            document.getElementById('nuevopass').disabled = false;
            document.getElementById('confirmapass').disabled = false;
        }
	}
    
	ajax.send(null)
}

function ValidarNuevoPass(e, xpass){
 e= (window.event)? event : e;
 intKey = (e.keyCode)? e.keyCode: e.charCode;
 if (intKey == 13) {
    if (xpass.length == 0) {
        alert('La Contraseña es Incorrecta ...!');
        return false;
    }  else {
        return true;
    }
 }
}

function ValidarConfirmacion(e, xpass, xpass1){
 e= (window.event)? event : e;
 intKey = (e.keyCode)? e.keyCode: e.charCode;
 if (intKey == 13) {
    if (xpass.length == 0) {
        alert('La Contraseña es Incorrecta ...!');
        return false;
    }  else {
        if (xpass == xpass1) {
            return true
        } else {
            alert('Las Nuevas Contraseñas no son Iguales ...!');
            return false;
        }
    }
 }
}

function ModificarPass(xpass1, xpass2) {
	//donde se mostrar los registros

    if (xpass1 == xpass2) {
      if (confirm('¿ Seguro para Cambiar Contraseña ?' )) {
          MP(xpass1, xpass2);
          return true;
      }
   } else {
       alert('Los Password son Incorrectos ...!');
   }
}

function MP(xpass1, xpass2) {
   var aleatorio = Math.random();
   divError = document.getElementById('Error');

   var usuario = document.getElementById('usuario').value;

   divError.innerHTML = '<i>Cambiando Password ...</i>';

	ajax=objetoAjax();
    ajax.open('GET', '/operaciones/modificar_password_usuario.php?usuario='+usuario+'&pass='+xpass1+'&aleatorio='+aleatorio);
	ajax.onreadystatechange=function() {
	  if (ajax.readyState==4) {
	    //mostrar resultados en esta capa
        divError.innerHTML = ajax.responseText;
	  }
	}

	ajax.send(null);
}

</script>

<link rel="stylesheet" type="text/css" href="/css1.css">

</head>

<body onLoad="javascript: if (Init()) {passactual.focus()}; return true;">

<form name="frmModificaPassUs" method="post" onsubmit="return false">

<div style="margin:auto;width:350px;text-align:center;">

<table width="350" border="0">
<tr>
<?php
echo '<td width="54"><input name="usuario" type="hidden" id="usuario" value="' . $usuario . '" /></td>';
?>
<td width="320" align="left">
<?php echo '[ ' . $usuario . ' ]' ; ?></td>
<td width="34">
<?php
  $trans = rand(1, 100000);
  $link = '?p111989=' . $usuario . '&reinit=' . $trans;
  echo '<a href="/newhtml.php' . $link . '">Salir</a></td>';
?>
</td>
</tr>
</table>

<FIELDSET>
<LEGEND>Cambio de Contraseña</LEGEND>

<table width="300px" border="0" align="left">
<tr>
<td width="110px" align="right">Contraseña Actual:</td>
<td>
    <input name="passactual" id="passactual" type="password" style="font-size: 8px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarPassActual(event, passactual.value)) {nuevopass.focus()}; return true" />
</td>
<td width="100px" align="left">Enter Comprueba</td>
</tr>

<tr>
<td width="110px" align="right">Nueva Contraseña:</td>
<td>
  <input name="nuevopass" id="nuevopass" type="password" disabled="true" style="font-size: 8px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarNuevoPass(event, nuevopass.value)) {confirmapass.focus()}; return true" />
</td>
<td width="100px" align="left"></td>
</tr>

<tr>
<td width="110px" align="right">Confirmar Contraseña:</td>
<td>
    <input name="confirmapass" id="confirmapass" type="password" disabled="true" style="font-size: 8px;" maxlength="10" width="2" size="10" onkeypress="javascript: if(ValidarConfirmacion(event, confirmapass.value, nuevopass.value)) {registrar.focus()}; return true" />
</td>
<td width="100px" align="left"></td>
</tr>

<tr>
<td width="90px" align="right"></td>
<td>
    <input type="button" class="button gray small" name="registrar" value="registrar" onClick="ModificarPass(nuevopass.value, confirmapass.value); return false" />
</td>
<td width="120px" align="left">
  <div id="Error"></div>
</td>
</tr>

</table>

</FIELDSET>

<div id="Mensaje">
</div>

</div>
</form>

</body>
</html>