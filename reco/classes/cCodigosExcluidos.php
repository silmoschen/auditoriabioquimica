<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
//implementamos la clase empleado
class cCodigosExcluidos{
 //constructor
 var $codigo1;
 var $codigo2;
 var $sql;
 var $res;

 function cCodigosExcluidos(){
 } 
 
 // inserta tupla
 function crear($codigo1, $codigo2){
     $query = "INSERT INTO codigosexcluidos (codigo1, codigo2) VALUES ('$codigo1', '$codigo2')";
     $this->sql = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;   
 }

 // borra tupla
 function borrar($codigo1, $codigo2){
     $query = "DELETE FROM codigosexcluidos WHERE codigo1 = '$codigo1' AND codigo2 = '$codigo2'";
     $this->sql = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
 }
 
 function getCodigos($codigo) {
    $query = "SELECT * FROM codigosexcluidos WHERE codigo1 = '$codigo'";
    $this->res=mysql_query($query);
    return $this->res;
 }

 function getSQL() {
     return $this->sql;
 }

 function getCodigoIncluido($codigo1, $codigo2) {
    $cod = '';
    $query = "SELECT * FROM codigosexcluidos WHERE codigo1 = '$codigo2' AND codigo2 = '$codigo1'";
    //echo$query;
    $result = mysql_query($query);
    while($fila=mysql_fetch_array($result)){
      $cod = $fila['codigo1'];
    }
    return $cod;
 }

}

?>
