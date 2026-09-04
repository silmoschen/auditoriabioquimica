<?php
session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><title>Opciones Sistema Auditoría On Line</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="css1.css" />
    </head>
    <body>
        <div style="margin:auto;width:550px;text-align:center; ">

            <?php
              if ($_SESSION["susuario"] == 'iapos') {
                $_SESSION["permiso_auditar"] = 'N';
                include('menu_iapos.php');
                exit;
            }
            if ($_SESSION["susuario"] == 'fesalud') {
                $_SESSION["permiso_auditar"] = 'N';
                include('menu_fesalud.php');
                exit;
            }
            
            if ($_SESSION["susuario"] != 'administ' and $_SESSION["susuario"] != 'iapos' and $_SESSION["nivel"] != '1') {
                $_SESSION["permiso_auditar"] = 'N';
                include('menu_usuario.php');
            }
            if ($_SESSION["susuario"] == 'administ' and $_SESSION["susuario"] != 'iapos' and $_SESSION["nivel"] == '1') {
                $_SESSION["permiso_auditar"] = 'S';
                include('menu_admin.php');
            }
            if ($_SESSION["susuario"] != 'administ' and $_SESSION["susuario"] != 'iapos' and $_SESSION["nivel"] == '1') {
                $_SESSION["permiso_auditar"] = 'S';
                include('menu_admin.php');
            }
            
            ?>

        </div>

    </body>
</html>