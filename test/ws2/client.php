<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$echo = $_GET['input'];

print "<h2>Echo Web Service</h2>";
print "<form action='client.php' method='GET'/>";
print "<input name='input' value='$echo'/><br/>";
print "<input type='Submit' name='submit' value='GO'/>";
print "</form>";

if($echo != ''){
    $client = new SoapClient(null, array(
      'location' => "http://localhost/test/ws/server.php",
      'uri'      => "http://localhost/test/ws"));

    $result = $client->
        __soapCall("echoo",array($echo));

    print $result;
}
?>
