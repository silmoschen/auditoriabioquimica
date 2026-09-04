<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/conexion.php');

class cTransaccionesCoseguroIapos {
 
 var $ID;
 var $nroauditoria;
 var $transaccion;
 var $tarea;
 var $fecha;
 var $transiapos;
 var $mensaje;
 var $ref1;
 var $ref2;
 var $ref3;
 var $ref4;

 function cTransaccionesCoseguroIapos(){}

 // inserta tupla
 function crear($id, $nroauditoria, $transaccion, $tarea, $fecha, $transiapos, $mensaje, $ref1, $ref2, $ref3, $ref4){
     $query = "INSERT INTO transacciones_coseguro_iapos (id, nroauditoria, transaccion, tarea, fecha, transiapos, mensaje, ref1, ref2, ref3, ref4) VALUES "
             . "('$id', '$nroauditoria', '$transaccion', '$tarea', '$fecha', '$transiapos', '$mensaje', '$ref1', '$ref2', '$ref3', '$ref4')";
     $result = mysql_query($query);
     //echo $query;
     if (!$result)
       return false;
     else
       return true;
 }

 // borra tupla
 function borrar($id){
     $query = "DELETE FROM transacciones_coseguro_iapos WHERE id = '$id'";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return true;
 }

 function getObject($id) {
    $query = "SELECT * FROM transacciones_coseguro_iapos WHERE id = '$id'";    
    $resultado = mysql_query($query);
    while($fila = mysql_fetch_array($resultado)){
        $this->id           = $fila['id'];
        $this->nroauditoria = $fila['nroauditoria'];
        $this->transaccion  = $fila['transaccion'];
        $this->tarea        = $fila['tarea'];
        $this->fecha        = $fila['fecha'];
        $this->transiapos   = $fila['transiapos'];
        $this->mensaje      = $fila['mensaje'];
        $this->ref1         = $fila['ref1'];
        $this->ref2         = $fila['ref2'];
        $this->ref3         = $fila['ref3'];
        $this->ref4         = $fila['ref4'];
    }    
 } 
 
 function getUltimaTx($nroauditoria) {
    $query = "SELECT * FROM transacciones_coseguro_iapos WHERE nroauditoria = '$nroauditoria' ORDER BY fecha DESC limit 0, 1";    
    //echo $query;
    $resultado = mysql_query($query);
    while($fila = mysql_fetch_array($resultado)){
        $this->id           = $fila['id'];
        $this->nroauditoria = $fila['nroauditoria'];
        $this->transaccion  = $fila['transaccion'];
        $this->tarea        = $fila['tarea'];
        $this->fecha        = $fila['fecha'];
        $this->transiapos   = $fila['transiapos'];
        $this->mensaje      = $fila['mensaje'];
        $this->ref1         = $fila['ref1'];
        $this->ref2         = $fila['ref2'];
        $this->ref3         = $fila['ref3'];
        $this->ref4         = $fila['ref4'];
    }    
 } 


}

?>
