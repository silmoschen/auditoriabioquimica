<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$method = $_SERVER['REQUEST_METHOD'];

$arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);

// tendremos que tratar esta variable para obtener el recurso adecuado de nuestro modelo.
$resource = $_SERVER['REQUEST_URI'];

// parametros
$id = $_REQUEST['id'];

// Dependiendo del método de la petición ejecutaremos la acción correspondiente.
switch ($method) {
    case 'GET':
        // código para método GET
        if (strpos($resource, "getAll") > 0) {
            $response = $arr;
            echo json_encode($response);
            break;
        }
        if (strpos($resource, "get?id") > 0) {
            echo $arr[$id];              
            break;
        }        

        break;
    case 'POST':
        $arguments = $_POST;       
        // código para método POST
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $arguments);
        // código para método PUT
        break;
    case 'DELETE':
        // código para método DELETE
        $response = $arr;
        break;
}

//echo json_encode($response, true); // $response será un array con los datos de nuestra respuesta.
//echo json_encode($response);

?>
