<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cEfector.php');
//implementamos la clase empleado
class CUsuarios{
 //constructor
 var $usuario;
 var $pass;
 var $nivel;
 var $observacion;
 var $efector;
 var $obsocial;

 function CUsuarios(){
     $this->efector = new cEfector;
 } 
 
 // inserta tupla
 function crear($usuario, $pass, $nivel, $observacion, $efector){
     $query = "INSERT INTO usuarios (usuario, pass, nivel, observacion, efector) VALUES ('$usuario', '$pass', $nivel, '$observacion', '$efector')";
     $result = mysql_query($query);
     //echo $query;
     if (!$result)
	   return false;
     else
       return true;   
 }

 // borra tupla
 function borrar($id){
     $query = "DELETE FROM usuarios WHERE id = $id";
     $result = mysql_query($query);     
     if (!$result)
	   return false;
     else
       return true;
 }

 function getObject($id) {
    $found = false;
    $query = "SELECT * FROM usuarios WHERE id = $id";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->usuario     = $fila['usuario'];
        $this->pass        = $fila['pass'];
        $this->nivel       = $fila['nivel'];
        $this->observacion = $fila['observacion'];
        $this->efector     = $fila['efector'];
        $this->obsocial    = $fila['obsocial'];
        $found = true;
    }   
    if($found) {
        return true;
    } else {
        return null;
    }

 }

 function getUsuario(){
     return $this->usuario;
 }

 function getPass(){
     return $this->pass;
 }

 function getNivel(){
     return $this->nivel;
 }

 function getObservacion(){
     return $this->observacion;
 }
 
 function getEfector(){
     return $this->efector;
 }
 
 function getObsocial(){
     return $this->obsocial;
 }

 function getUsuarios() {
    $query = "SELECT * FROM usuarios";
    $this->res=mysql_query($query);
    return $this->res;
 }
 
 function verificarUsuario($us, $pa) {
    $found = false;
    $query = "SELECT usuario, pass, nivel, efector, obsocial FROM usuarios WHERE usuario = '$us' AND pass = '$pa'";
    //echo $query;
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->usuario  = $fila['usuario'];
        $this->pass     = $fila['pass'];
        $this->nivel    = $fila['nivel'];
        $this->efector  = $fila['efector'];
        $this->obsocial = $fila['obsocial'];
        $found = true;        
    }       
    return $found;
 }

  function findUsuario($us) {
    $found = false;
    $query = "SELECT usuario, nivel, pass, efector, obsocial FROM usuarios WHERE usuario = '$us'";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->usuario     = $fila['usuario'];
        $this->pass        = $fila['pass'];
        $this->nivel       = $fila['nivel'];        
        $this->efector     = $fila['efector'];
        $this->obsocial    = $fila['obsocial'];
        $found = true;
    }
    return $found;
 }

 function cambiarpass($us, $pa) {
     $query = "UPDATE usuarios SET pass = '$pa' WHERE usuario = '$us'";
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
 }
 
}

?>
