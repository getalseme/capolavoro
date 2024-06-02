<?php

require 'vendor/autoload.php';
require 'class/DB.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Funzione per verificare e decodificare un token JWT
function verifyToken($token) {
    $secret_key = "il potere logora chi non ce l'ha";
    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return null; // Token non valido o scaduto
    }
}

function getDayOfWeek($date) {
    $dateTime = new DateTime($date);
    $dayOfWeek = (int) $dateTime->format('N');
    return $dayOfWeek - 1;
}

header('Content-Type: application/json; charset=utf-8');

if(isset($_COOKIE['zToken'])){
    $decodedToken = verifyToken($_COOKIE['zToken']);
    if($decodedToken !== null){
        $hour = $_GET['hour'];
        $day = $_GET['day'];
        $dayOfWeek = getDayOfWeek($day);
        $db = new DB();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("CALL get_free_rooms( ? , ? )");
        $stmt->execute([$dayOfWeek, $hour]);
        $fetchedResult = $stmt->fetchAll();
        echo json_encode($fetchedResult);
    }else{
        http_response_code(498);
        $response = array("message" => "Token expired/invalid");
        echo json_encode($response);
    }
}else{
    http_response_code(400);
    $response = array("message" => "Missing zToken in cookies");
    echo json_encode($response);
}
