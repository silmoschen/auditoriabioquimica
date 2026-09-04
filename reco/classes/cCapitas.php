<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
//implementamos la clase empleado
class cCapitas{
 //constructor
 var $codos;
 var $periodo;
 var $capita;
 var $capita2;
 var $capita3;
 var $sql;
 var $res;

 function cCapitas(){
 } 
 
 // inserta tupla
 function crear($codos, $periodo, $capita, $capita2, $capita3){
     $per = substr($periodo, 3, 4) . substr($periodo, 0, 2);
     $query = "INSERT INTO capitas (codos, periodo, capita, capita2, capita3) VALUES ('$codos', '$per', $capita, $capita2, $capita3)";
     $this->sql = $query ;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;   
 }

 // borra tupla
 function borrar($codos, $periodo){
     $per = substr($periodo, 3, 4) . substr($periodo, 0, 2);
     $query = "DELETE FROM capitas WHERE codos = '$codos' AND periodo = '$per'";
     $this->sql   = $query;
     $result = mysql_query($query);     
     if (!$result)
	   return false;
     else
       return true;
 }
 
 function getObject($codos, $periodo) {
    $per = substr($periodo, 3, 4) . substr($periodo, 0, 2);
    $found = false;
    $query = "SELECT * FROM capitas WHERE codos = '$codos' AND periodo = '$per'";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->codos      = $fila['codos'];
        $this->periodo    = $periodo;
        $this->capita     = $fila['capita'];
        $this->capita2    = $fila['capita2'];
        $this->capita3    = $fila['capita3'];
        $found = true;
    }   
    if($found) {
        return true;
    } else {
        return null;
    }

 }

 function getCodos(){
     return $this->codos;
 }

 function getPeriodo(){
     return $this->periodo;
 }

 function getCapita(){
     return $this->capita;
 }
  
 function getCapita2(){
     return $this->capita2;
 }
 
 function getCapita3(){
     return $this->capita3;
 }

 function getCapitas($codos, $RegistrosAEmpezar, $RegistrosAMostrar) {
   $query = "SELECT * FROM capitas WHERE codos = '$codos' ORDER BY codos, periodo DESC LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
   $this->res=mysql_query($query);
   return $this->res;
 }

 function getMontoCapita($codos, $periodo) {
   $monto = 0;
   $per = substr($periodo, 3, 4) . substr($periodo, 0, 2);
   $query = "SELECT * FROM capitas WHERE codos = '$codos' ORDER BY codos, periodo";
   $resultado=mysql_query($query);
   while($fila=mysql_fetch_array($resultado)){
       $monto = $fila['capita'];
       $this->capita2 = $fila['capita2'];
       $this->capita3 = $fila['capita3'];
       if ($fila['periodo'] >= $per) { break; }
   }
   return $monto;
 }
 
 function getSQL(){
     return $this->sql;
 }

}

?>
