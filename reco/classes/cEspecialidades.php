<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/cUtiles.php');
//implementamos la clase empleado
class cEspecialidades{
 //constructor
 var $id_especialidad;
 var $descripcion;
 var $ni_activo;
 var $sql;
 var $res;

 function cEspecialidades(){
     $this->utiles = new cUtiles;
 }

 // inserta tupla
 function crear($id_especialidad, $descripcion, $ni_activo){
     $query = "INSERT INTO especialidad (id_especialidad, descripcion, ni_activo) VALUES
              ($id_especialidad, '$descripcion', $ni_activo)";
     $this->sql = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
 }

 // borra tupla
 function borrar($id_especialidad){
     $query = "DELETE FROM especialidad WHERE id_especialidad = $id_especialidad";
     $this->sql = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
 }

 // actualiza tupla
 function actualizar($id_especialidad, $descripcion, $ni_activo){
     $query = "UPDATE especialidad SET descripcion = '$descripcion', ni_activo = $ni_activo WHERE id_especialidad = $id_especialidad";
     $this->sql = $query;
     $result = mysql_query($query);
     if (!$result)
	   return false;
     else
       return true;
 }

 function getObject($id_especialidad) {
    $nf = false;
    $query = "SELECT * FROM especialidad WHERE id_especialidad = $id_especialidad";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->id_especialidad = $fila['id_especialidad'];
        $this->descripcion     = $fila['descripcion'];
        $this->ni_activo       = $fila['ni_activo'];
        $nf              = true;
    }
    return $nf;
 }

 function getId_especialidad(){
     return $this->id_especialidad;
 }

 function getDescripcion(){
     return $this->descripcion;
 }

 function getNi_activo(){
     return $this->ni_activo;
 }

 function getListaEspecialidades($filtro, $RegistrosAEmpezar, $RegistrosAMostrar) {
   if ($filtro == '' or $filtro == 'undefined') {
     $query = "SELECT * FROM especialidad ORDER BY descripcion LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
   } else {
     $query = "SELECT * FROM especialidad WHERE descripcion LIKE '$filtro%' ORDER BY descripcion LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
   }
   
   $this->res=mysql_query($query);
   return $this->res;
 }
 
 function getEspecialidades() {
   $query = "SELECT * FROM especialidad ORDER BY descripcion";
   $this->res=mysql_query($query);
   return $this->res;
 }

 function getSQL() {
     return $this->sql;
 }

}

?>

