<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
//implementamos la clase empleado
class cTramos{
 //constructor
 var $codos;
 var $id;
 var $descrip;
 var $tope;
 var $cantbonos;
 var $sql;
 var $res;

 function cNBU(){
 } 
 
 // inserta tupla
 function crear($codos, $id, $descrip, $tope, $cantbonos){
     $query = "INSERT INTO tramos (codos, id, descrip, tope, cantbonos) VALUES ('$codos', $id, '$descrip', $tope, $cantbonos)";
     $this->sql = $query ;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;   
 }

 // borra tupla
 function borrar($codos, $id){
     $query = "DELETE FROM tramos WHERE codos = '$codos' AND id = '$id'";
     $this->sql   = $query;
     $result = mysql_query($query);     
     if (!$result)
	   return false;
     else
       return true;
 }

 // actualiza tupla
 function actualizar($codos, $id, $descrip, $tope, $cantbonos){
     $query = "UPDATE tramos SET descrip = '$descrip', tope = $tope, cantbonos = $cantbonos WHERE codos = '$codos' AND id = '$id'";
     $this->sql   = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;    
 }

 function getObject($codos, $id) {
    $found = false;
    $query = "SELECT * FROM tramos WHERE codos = '$codos' AND id = '$id'";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->codos      = $fila['codos'];
        $this->id         = $fila['id'];
        $this->descrip    = $fila['descrip'];
        $this->tope       = $fila['tope'];
        $this->cantbonos  = $fila['cantbonos'];
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

 function getId(){
     return $this->id;
 }

 function getDescrip(){
     return $this->descrip;
 }

 function getTope(){
     return $this->tope;
 }

 function getCantbonos(){
     return $this->cantbonos;
 }

  function getListaTramos() {
    $query = "SELECT * FROM tramos ORDER BY descrip";
    $this->res=mysql_query($query);
    return $this->res;
 }

function getTramos($codos, $filtro, $RegistrosAEmpezar, $RegistrosAMostrar) {
   if ($filtro == '' or $filtro == 'undefined') {
     $query = "SELECT * FROM tramos WHERE codos = '$codos' ORDER BY descrip LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
   } else {
     $query = "SELECT * FROM tramos WHERE codos = '$codos' AND descrip LIKE '$filtro%' ORDER BY descrip LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
   }
   $this->res=mysql_query($query);
   return $this->res;
 }


 function getSQL(){
     return $this->sql;
 }

}

?>
