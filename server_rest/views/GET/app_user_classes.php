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
    $decodedToken = verifyToken($_GET['zToken']);
    if($decodedToken !== null){
        $db = new DB();
        $conn = $db->getConnection();
        $email = $decodedToken->email;
        if($decodedToken->student === true){
            $classId = "SELECT class.name FROM class 
                        JOIN student_in_class ON student_in_class.class_id = class.id
                        JOIN student ON student.id = student_in_class.student_id 
                        WHERE student.user_id = '$email'";
            $result = $conn->query($classId);
        }else if($decodedToken['teacher'] === true){
            $classId = "SELECT DISTINCT class FROM view_schedule 
                        WHERE teacher = '$email'";
            $result = $conn->query($classId);
        }
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

