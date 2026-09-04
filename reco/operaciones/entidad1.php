<?php
include_once('__routes.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

echo '<input type="hidden" id="ruta" value=' . $ruta . '/>';

if (!isset($_SESSION['susuario']) or $_SESSION["susuario"] == '' ) {
  echo '<h1>Se ha Producido un Error al Intentar Ingresar al Sitio</h1>';
  ?>
  <script type="text/javascript">;
    function redireccionar(){
        var aleatorio = Math.random();
        var host = location.protocol + '//' + location.hostname  + '/'+ document.getElementById('ruta').value + 'login.php?aleatorio='+aleatorio;
        window.location.href = host;
    }
    setTimeout ("redireccionar()", 1000); //tiempo expresado en milisegundos
  </script>
  <?php
  exit;
}

?>

<table width="550px" align="center">
<tr>

<?php

if (strlen($_SESSION["susuario"]) > 0) {
    $uss = "";
    if ($_SESSION["susuarios"] != "") {
        $uss = "   [ " .  $_SESSION["susuarios"] . " ]";
    }
    echo "<td width='300px' bgcolor='#FFCC66' align='left'><b>" . $_SESSION["snombre"] . "</b>" . $uss . "</td>";
    echo "<td width='80px' bgcolor='#FFCC66' align='right'>us: " . $_SESSION["susuario"] . "</td>";
    $t = rand(1, 999999);
    echo "<td width='80px' bgcolor='#FFCC66' align='left'><a href='cerrar_session.php?aleatorio=" . $t . "'>cerrar sesión</a></td>";
} else {
    echo "<td width='50px' bgcolor='#FFCC66' align='left'></td>";
}

?>

</tr>
</table>

