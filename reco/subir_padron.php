<?php
session_start();
$codos = $_REQUEST['codos'];
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<html>
    <head></head>
    <body>
        <form enctype="multipart/form-data" action="subir_archivo.php" method="post">
            <table border="0px" width="500px" align="center">
            <tr>
            <td align="left">
            Ubicacion: <input type="file" name="archivito">
            </td>
            <td align="left" width="50px">
            <input type="submit" class="button gray small" value="Enviar">
            </td>
            <td align="left" width="50px">
            <input type="button" class="button gray small" value="Cancelar" onclick="CancelarEnvio()">
            </td>
            </tr>
            </table>
            <?
            echo '<input type="hidden" id="codos" name="codos" value=' . $codos . '>';
            ?>
        </form>
    </body>
</html>