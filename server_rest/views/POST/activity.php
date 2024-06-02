<?php

require 'vendor/autoload.php';
require 'class/DB.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

function verifyToken($token) {
    $secret_key = "il potere logora chi non ce l'ha";
    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return (array) $decoded; // Cast to array
    } catch (Exception $e) {
        return null; // Invalid or expired token
    }
}

// Function to get teacher ID from email
function getTeacherIdByEmail($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM teacher WHERE user_id = ?");
    $stmt->execute([$email]);
    return $stmt->fetchColumn();
}

header('Content-Type: application/json; charset=utf-8');

if (isset($_COOKIE['zToken'])) {
    $decodedToken = verifyToken($_COOKIE['zToken']);
    if ($decodedToken !== null) {

        $teacherID = null;

        // Connect to the database
        $db = new DB();
        $conn = $db->getConnection();

        // Check if teacher_email is provided and get the teacher ID
        if (isset($_POST['teacher_email'])) {
            $teacherID = getTeacherIdByEmail($conn, $_POST['teacher_email']);
            if (!$teacherID) {
                http_response_code(404);
                $response = array("message" => "Teacher not found");
                echo json_encode($response);
                exit();
            }
        } else {
            $teacherID = $_POST['teacher_id'];
        }

        // Insert the activity into the database
        $stmt = $conn->prepare("INSERT INTO activity (name, description, public, teacher_id) VALUES (:name, :description, :public, :id)");
        $stmt->execute(array(':name' => $_POST['name'], ':description' => $_POST['description'], ':public' => (int)$_POST['public'], ':id' => $teacherID));
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
        http_response_code(498);
        $response = array("message" => "Token expired/invalid");
        echo json_encode($response);
    }
} else {
    http_response_code(400);
    $response = array("message" => "Missing zToken in cookies");
    echo json_encode($response);
}
?>
