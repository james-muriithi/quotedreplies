<?php
header('Access-Control-Allow-Origin: http://localhost/fyp_ms/');
header('Access-Control-Allow-Methods: POST');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once 'config/database.php';
include_once 'classes/Tweet.php';

$conn = Database::getInstance();
if ($_SERVER['REQUEST_METHOD'] === 'POST' ){
    if (empty($_POST)) {
        $_POST = json_decode(file_get_contents('php://input'), true) ? : [];
    }

    $data = $_POST;

    if(isset($data['username'],$data['in_reply_to_link'],$data['link'])){
        $tweet = new Tweet($conn);
        $conn->beginTransaction();
        if ($tweet->saveTweet($data['username'],$data['in_reply_to_link'],$data['link'])){
            $conn->commit();
            // set the header to make sure cache is forced
            header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
            // treat this as json
            header('Content-Type: application/json');
            echo json_encode(['success'=>true,'message'=> 'saved successfully']);
        }else{
            $conn->rollBack();
            // set the header to make sure cache is forced
            header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
            // treat this as json
            header('Content-Type: application/json');
            echo json_encode(['success'=>false,'message'=> 'not saved']);
        }
    }else{
        echo json_encode(['success'=>false,'message'=> 'provide all the details']);
    }
}elseif ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if (empty($_GET)) {
        $_GET = json_decode(file_get_contents('php://input'), true) ? : [];
    }

    $data = $_GET;

    if (isset($data['get-all']) || isset($data['read-all'])){
        $tweet = new Tweet($conn);
        // set the header to make sure cache is forced
        header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
        // treat this as json
        header('Content-Type: application/json');

        echo json_encode($tweet->getTweets());
    }elseif (isset($data['top-users'])){
        $tweet = new Tweet($conn);
        // set the header to make sure cache is forced
        header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
        // treat this as json
        header('Content-Type: application/json');
        echo json_encode($tweet->getTopUsers());
    }
}else{
    // set the header to make sure cache is forced
    header('Cache-Control: no-transform,public,max-age=300,s-maxage=900');
    // treat this as json
    header('Content-Type: application/json');
    echo json_encode(['success'=>false,'message'=> 'method not supported']);
}