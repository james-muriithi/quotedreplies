<?php
header('Access-Control-Allow-Origin: http://localhost/fyp_ms/');
header('Access-Control-Allow-Methods: POST');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// set the header to make sure cache is forced
header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
// treat this as json
header('Content-Type: application/json');

include_once 'config/database.php';
include_once 'classes/DM.php';

$conn = Database::getInstance();
if ($_SERVER['REQUEST_METHOD'] === 'POST' ){
    if (empty($_POST)) {
        $_POST = json_decode(file_get_contents('php://input'), true) ? : [];
    }

    $data = $_POST;

    // print_r($data);

    if(isset($data['username'],$data['message'],$data['response'])){
        $dm = new DM($conn);
        $conn->beginTransaction();
        if ($dm->saveDM($data['username'],$data['message'],$data['response'])){
            $conn->commit();
            echo json_encode(['success'=>true,'message'=> 'saved successfully']);
        }else{
            $conn->rollBack();
            echo json_encode(['success'=>false,'message'=> 'not saved']);
        }
    }else{
        echo json_response(400, 'provide all the details', true);

    }
}elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if (empty($_GET)) {
        $_GET = json_decode(file_get_contents('php://input'), true) ? : [];
    }

    $data = $_GET;
    $dm = new DM($conn);

    if (isset($data['get-all']) || isset($data['read-all'])){
        echo json_encode($dm->getDMs());
    }elseif (isset($data['history'], $data['username'])){
        echo json_encode($dm->getHistory($data['username']));
    }
}else{
    echo json_response(400, 'method not supported', true);
}

function json_response($code = 200, $message = null, $error = false)
{
    // clear the old headers
    header_remove();
    // set the actual code
    http_response_code($code);
    // set the header to make sure cache is forced
    header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
    // treat this as json
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        201 => '201 Created',
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        404 => '404 Not Found',
        409 => '409 Conflict',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
    );
    // ok, validation error, or failure
    header('Status: '.$status[$code]);
    // return the encoded json
    if ($error){
        return json_encode(array(
            'status' => $status[$code] === 200,
            'error' => array('errorCode'=>0,'message' => $message)
        ));
    }
    return json_encode(array(
        'success' => array('message' => $message)
    ));
}