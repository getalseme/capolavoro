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
        if($decodedToken->admin){
            try{
                $stmt = $conn->prepare("DELETE FROM activity WHERE name = ?");
                $stmt->execute([$_POST['name']]);
                http_response_code(200);
                $response = array("message" => "Deleted succesfully");
                echo json_encode($response);
            }catch{
                http_response_code(501);
                $response = array("message" => "Delete unsuccesful");
                echo json_encode($response);
            }
        }else if($decodedToken->teacher){
            try{
                $stmt = $conn->prepare("DELETE FROM activity WHERE name = ? AND teacher_id = ?");
                $stmt->execute([$_POST['name'], $_POST['teacher_id']]);
                http_response_code(200);
                $response = array("message" => "Deleted succesfully");
                echo json_encode($response);
            }catch{
                http_response_code(501);
                $response = array("message" => "Delete unsuccesful");
                echo json_encode($response);
            }
        }else{
            http_response_code(469);
            $response = array("message" => "Unauthorized user");
            echo json_encode($response);
        }

        $db = new DB();
        $conn = $db->getConnection();
        
        if ($teacher_id !== null) {
            
            $stmt = $conn->prepare("INSERT INTO activity (name, description, public, teacher_id) VALUES (:name, :description, :public, :id)");
            $stmt->execute(array(':name' => $_POST['name'], ':description' => $_POST['description'],':public' => (int)$_POST['public'], ':id' => $teacher_id));
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