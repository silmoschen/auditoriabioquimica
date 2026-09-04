<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Medicos</title>

        <script src="../jscript/funcionajax.js"></script>
        <script></script>

        <?php
        /*
         * To change this template, choose Tools | Templates
         * and open the template in the editor.
         */

        session_start();
        $_SESSION['name'] = "auditoria";
        $_SESSION['susuarios'] = "";
        $_SESSION['susuario'] = "";
        $_SESSION['spass'] = "";
        $_SESSION['snombre'] = "";
        $_SESSION['sdireccion'] = "";
        $_SESSION['stelefono'] = "";


        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = 'VcOuPCuAdm5r'; // NOTA: Reemplace password por el password de su cuenta de hosting

        $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Ocurrio un error al conectarse al servidor mysql');

        $dbname = 'x020vm10_auditrqta';
        mysql_select_db($dbname);

        $query = "SELECT * FROM obsocial";
        $resultado = mysql_query($query);
        while ($fila = mysql_fetch_array($resultado)) {
            echo $fila['codos'];
        }

        include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/cObSocial.php");

        $os = new cObsocial();
        $os->getObject('121005');
        echo '<hr/>Nombre: ' . $os->nombre;

        echo '<hr/>';
        ?>


        <?php include('operaciones/entidad.php') ?>

        < hr / >
        <?php include('operaciones/lista_osociales_medicos.php') ?>


    </head>
</html>

