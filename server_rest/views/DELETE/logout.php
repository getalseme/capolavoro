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

if(isset($_COOKIE['zToken'])){
    unset($_COOKIE['zToken']); 
    setcookie('zToken', '', -1, '/'); 
    http_response_code(200);
    $response = array("message" => "Logout succesful");
    echo json_encode($response);
}else{
    http_response_code(400);
    $response = array("message" => "Missing zToken in cookies");
    echo json_encode($response);
}