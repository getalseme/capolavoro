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
    $decodedToken = verifyToken($_COOKIE['zToken']);
    if($decodedToken !== null){
        if ($decodedToken->teacher){
            $db = new DB();
            $conn = $db->getConnection();
            $s = "  SELECT a.name AS name, a.description AS description, a.public AS public, u.surname AS tSurname, u.name AS tName FROM activity a 
                    JOIN teacher ON teacher.id = a.teacher_id
                    JOIN users u ON u.email = teacher.user_id
                    WHERE teacher.user_id = '$decodedToken->email'";
            $result = $conn->query($s);
            echo json_encode($result->fetchAll());
        }
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

