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


header('Content-Type: application/json; charset=utf-8');

if(isset($_GET['zToken'])){
    $decoded_array = (array) verifyToken($_GET['zToken']);
    if($decoded_array !== null){
        $email = $decoded_array['email'];
        $db = new DB();
        $conn = $db->getConnection();
        $s = "SELECT users.name, users.surname, users.email FROM users WHERE users.email = '$email'";
        $result = $conn->query($s);
        echo json_encode($result->fetchAll());
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
