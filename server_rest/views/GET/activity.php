<?php

require 'vendor/autoload.php';
require 'class/DB.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

function verifyToken($token) {
    $secret_key = "il potere logora chi non ce l'ha";
    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        return null;
    }
}

header('Content-Type: application/json; charset=utf-8');

if(isset($_COOKIE['zToken'])){
    $decodedToken = verifyToken($_COOKIE['zToken']);
    if($decodedToken !== null){
        $teacher_email = $_GET['teacher_id'];
        
        // Connect to the database
        $db = new DB();
        $conn = $db->getConnection();
        
        // Query to get the teacher ID based on the email
        $stmt = $conn->prepare("SELECT id FROM teacher JOIN users ON teacher.user_id = users.email WHERE users.email = :email");
        $stmt->execute(array(':email' => $teacher_email));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result !== false) {
            $teacher_id = $result['id'];
            
            // Insert the activity into the database
            $stmt = $conn->prepare("INSERT INTO activity (name, description, public, teacher_id) VALUES (?, ?, ?, ?)");
            $stmt->execute(array($_GET['name'], $_GET['description'], $_GET['public'], $teacher_id));
            if ($stmt->rowCount() > 0) {
                http_response_code(201);
                $response = array("message" => "Activity added successfully");
                echo json_encode($response);
            } else {
                http_response_code(501);
                $response = array("message" => "Failed to add activity to the database");
                echo json_encode($response);
            }
        } else {
            http_response_code(404);
            $response = array("message" => "Teacher not found");
            echo json_encode($response);
        }
    } else {
        http_response_code(498);
        $response = array("message" => "Token expired/invalid");
        echo json_encode($response);
    }
} else {
    http_response_code(400);
    $response = array("message" => "Missing zToken in cookies");
    echo json_encode($response);
}