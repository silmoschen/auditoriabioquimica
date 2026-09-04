<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');
class cTipoDoc{
 //constructor
 var $id_tipo_doc;
 var $descripcion;
 var $nroint;
 var $res;

 function cTipoDoc(){}

 // inserta tupla
 function crear($id_tipo_doc, $descripcion, $nroint){
     $query = "INSERT INTO tipo_doc (id_tipo_doc, descripcion, ni_doc_pami) VALUES ('$id_tipo_doc', '$descripcion', '$nroint')";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
 }

 // borra tupla
 function borrar($id_tipo_doc){
     $query = "DELETE FROM tipo_doc WHERE id_tipo_doc = '$id_tipo_doc'";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
 }

 // actualiza tupla
 function actualizar($id_tipo_doc, $descripcion, $nroint){
     $query = "UPDATE tipo_doc SET descripcion = '$descripcion', ni_doc_pami = '$nroint' WHERE id_tipo_doc = $id_tipo_doc";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
 }

 function getObject($id_tipo_doc) {
    $query = "SELECT * FROM tipo_doc WHERE id_tipo_doc = '$id_tipo_doc'";    
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->id_tipo_doc = $fila['id_tipo_doc'];
        $this->descripcion = $fila['descripcion'];
        $this->nroint      = $fila['ni_doc_pami'];
    }
    return null;
 }

 function getObjectNroInt($id_tipo_doc) {
    $query = "SELECT * FROM tipo_doc WHERE ni_doc_pami = '$id_tipo_doc'";
    $resultado=mysql_query($query);
    while($fila=mysql_fetch_array($resultado)){
        $this->id_tipo_doc = $fila['id_tipo_doc'];
        $this->descripcion = $fila['descripcion'];
        $this->nroint      = $fila['ni_doc_pami'];
    }
    return null;
 }


 function getId_tipo_doc(){
     return $this->id_tipo_doc;
 }

 function getDescripcion(){
     return $this->descripcion;
 }

 function getNroint(){
     return $this->nroint;
 }

 function getTiposDoc() {
    $query = "SELECT * FROM tipo_doc ORDER by descripcion";
    $this->res=mysql_query($query);
    return $this->res;
 }

}

?>
