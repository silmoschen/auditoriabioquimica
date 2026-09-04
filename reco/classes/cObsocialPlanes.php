<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
//implementamos la clase empleado
class cObsocialPlanes{
 //constructor 
 var $id;
 var $descrip;
 var $codos;
 var $cantafiliados;
 var $codigo1;
 var $codigo2;
 var $sql;
 var $res;

 function cObsocialPlanes(){
 } 
 
 // inserta tupla
 function crear($codos, $descrip, $codigo1, $codigo2){
     $query = "INSERT INTO obsocial_planes (descrip, fk_codos, codigo1, codigo2) VALUES ('$descrip', '$codos', '$codigo1', '$codigo2')";
     $this->sql = $query ;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;   
 }

 // borra tupla
 function borrar($id){
     $query = "DELETE FROM obsocial_planes WHERE id = $id";
     $this->sql   = $query;
     $result = mysql_query($query);     
     if (!$result)
	   return false;
     else
       return true;
 }

 // actualiza tupla
 function actualizar($id, $descrip, $codos, $codigo1, $codigo2){
     $query = "UPDATE obsocial_planes SET descrip = '$descrip', fk_codos = '$codos', codigo1 = '$codigo1', codigo2 = '$codigo2' WHERE id = $id";
     $this->sql   = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;    
 }

 function getObject($id) {
    $found = false;
    $query = "SELECT * FROM obsocial_planes WHERE id = $id";   
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->codos         = $fila['fk_codos'];
        $this->id            = $fila['id'];
        $this->descrip       = $fila['descrip'];
        $this->cantafiliados = $fila['cant_afiliados'];
        $this->codigo1       = $fila['codigo1'];
        $this->codigo2       = $fila['codigo2'];
        $found = true;
    }   
    if($found) {
        return true;
    } else {
        return null;
    }

 }
 
 function getList($codos) {
    $query = "SELECT id, descrip, cant_afiliados, codigo1, codigo2, fk_codos FROM obsocial_planes WHERE fk_codos = '$codos' ORDER BY descrip";
    //echo $query;
    $this->res=mysql_query($query);
    return $this->res;
 }

 function getSQL(){
     return $this->sql;
 }

}

?>
