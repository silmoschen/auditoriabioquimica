<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
class cEquivalenciaPadrones{
 var $codigo1;
 var $codigo2;
 var $res;

 function cEquivalenciaPadrones(){}

 // inserta tupla
 function crear($codigo1, $codigo2){
     $query = "INSERT INTO obsocial_padron (codigo1, codigo2) VALUES ('$codigo1', '$codigo2')";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
 }

 // borra tupla
 function borrar($codigo1){
     $query = "DELETE FROM obsocial_padron WHERE codigo1 = '$codigo1'";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
 }

 // actualiza tupla
 function actualizar($codigo1, $codigo2){
     $query = "UPDATE obsocial_padron SET codigo2 = '$codigo2' WHERE codigo1 = '$codigo1'";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
 }

 function getObject($codigo1) {
    $found = false;
    $query = "SELECT * FROM obsocial_padron WHERE codigo1 = '$codigo1'";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->codigo1 = $fila['codigo1'];
        $this->codigo2 = $fila['codigo2'];
        $found = true;
    }
    if ($found == false) {
        $this->codigo1 = '';
        $this->codigo2 = '';
    }
    return $found;
 }
 
 function getCodigoInverso($codigo) {
    $found = false;
    $query = "SELECT * FROM obsocial_padron WHERE codigo2 = '$codigo'";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->codigo1 = $fila['codigo1'];
        $this->codigo2 = $fila['codigo2'];
        $found = true;
    }
    if ($found == false) {
        $this->codigo1 = '';
        $this->codigo2 = '';
    }
    return $found;
 }

 function getCodigo1(){
     return $this->codigo1;
 }

function getCodigo2(){
     return $this->codigo2;
}

 function getCodigos() {
    $query = "SELECT * FROM obsocial_padron";
    $this->res=mysql_query($query);
    return $this->res;
 }

}

?>
