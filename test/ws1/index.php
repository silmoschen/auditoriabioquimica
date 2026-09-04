<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$method = "GETALL";
echo $_SERVER['REQUEST_URI'][3];
switch ($method) {
    case 'PUT':
        //$this->create_contact($name);
        break;

    case 'DELETE':
        //$this->delete_contact($name);
        break;

    case 'GET':
        //$this->display_contact($name);
        break;
    case 'GETALL':
        header("Content-Type:application/json");
        $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
        echo json_encode($arr);
        return json_encode($arr);
        break;

    default:
        header('HTTP/1.1 405 Method Not Allowed');
        header('Allow: GET, PUT, DELETE');
        break;
}
?>
