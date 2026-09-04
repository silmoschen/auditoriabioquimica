<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
//implementamos la clase empleado
class cCodigosRestringidos{
 //constructor
 var $codos;
 var $codigo;
 var $sql;
 var $res;

 function cCodigosRestringidos(){
 } 
 
 // inserta tupla
 function crear($codos, $codigo){
     $query = "INSERT INTO codigosrestringidos (codos, codigo) VALUES ('$codos', '$codigo')";
     $this->sql = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;   
 }

 // borra tupla
 function borrar($codos, $codigo){
     $query = "DELETE FROM codigosrestringidos WHERE codos = '$codos' AND codigo = '$codigo'";
     $this->sql = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
 }
 
 function getCodigos($codos) {
    $query = "SELECT * FROM codigosrestringidos WHERE codos = '$codos'";
    $this->res=mysql_query($query);
    return $this->res;
 }

 function getSQL() {
     return $this->sql;
 }

 function getCodigoRestringido($codos, $codigo) {
    $cod = false;
    $query = "SELECT codigo FROM codigosrestringidos WHERE codos = '$codos' AND codigo = '$codigo'";            
    $result = mysql_query($query);    
     $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
      $cod = true;
    }
    
    return $cod;
 }

}

?>
