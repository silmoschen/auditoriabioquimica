<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
$codos = $_REQUEST["codos"];
$_SESSION['parametro1'] = $codos;

?>

<div style="margin:auto;width:550px;text-align:center;">
    <div id="menusist" align="center">        
        <?php
        include_once('__routes.php');
        include('act_padron.php');
        ?>
    </div>
</div>

<?php
session_start();
$archivo = $_FILES["archivito"]["tmp_name"];
$tamanio = $_FILES["archivito"]["size"];
$tipo = $_FILES["archivito"]["type"];
$nombre = $_FILES["archivito"]["name"];

if ($archivo != "none") {

    if (!((strpos($nombre, "txt")))) {
        print '<div style="margin:auto;width:500px;text-align:left;">';
        print utf8_decode("<br/>La Extension o el Tamaño de los Archivos no es Correcta. <br/>
                       Se permiten Archivos .txt. <br>");
        print "<br/><a href='act_patron.php'>Regresar</a><br /><br />";
        print "</div>";        
        exit();
    }

    $target_path = "padrones/" . $codos . '.txt';

    if (move_uploaded_file($_FILES['archivito']['tmp_name'], $target_path)) {
        $imgOK = "El Archivo " . basename($_FILES['archivito']['name']) .
                " ha sido Cargado.";
        echo $imgOK;        
    } else {
        print "There was an error uploading the file, please try again!";
        exit();
    }
}

else
    print "No se ha podido subir el archivo al servidor";
?>