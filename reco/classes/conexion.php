<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

  $host = "localhost";
  $username = 'root';
  $passw = "bross2005";
  //$dbdatos = 'audit_sj';
  $dbdatos = 'audit_reco_1';
  
  /*
  $host = "localhost";
  $username = 'x020vm10';
  $passw = "VcOuPCuAdm5r";
  $dbdatos = 'x020vm10_auditrqta';
   * 
   */
  
  $con = mysql_connect($host, $username, $passw);
  mysql_select_db($dbdatos, $con);

?>
