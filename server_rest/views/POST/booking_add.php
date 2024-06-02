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
    $stmt = $conn->prepare("SELECT id FROM teacher WHERE user_id = (SELECT email FROM users WHERE email = ?)");
    $stmt->execute([$email]);
    return $stmt->fetchColumn();
}

// Function to get or create a date_time entry and return its ID
function getDateTimeId($conn, $date, $hour) {
    $stmt = $conn->prepare("SELECT id FROM day_time WHERE date = ? AND hour = ?");
    $stmt->execute([$date, $hour]); // Use array of parameters
    $id = $stmt->fetchColumn(); // Fetch the single column directly
    if ($id) {
        return $id; // Return the ID
    } else {
        $stmt = $conn->prepare("INSERT INTO day_time (date, hour) VALUES (?, ?)");
        $stmt->execute([$date, $hour]); // Use array of parameters
        return $conn->lastInsertId(); // Return the last inserted ID
    }
}

header('Content-Type: application/json; charset=utf-8');

if (isset($_COOKIE['zToken'])) {
    $decodedToken = verifyToken($_COOKIE['zToken']);
    if ($decodedToken !== null) {
        $db = new DB();
        $conn = $db->getConnection();
        $email = $decodedToken['email'];
        
        if ($decodedToken['teacher'] === true || $decodedToken['admin'] === true) {
            $teacherID = null;

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

            $date_timeID = getDateTimeId($conn, $_POST['day'], $_POST['hour']);
            if (isset($_POST['activity'])) {
                if ($_POST['activity'] == 'NULL'){
                    $_POST['activity'] = NULL;
                }
                $stmt = $conn->prepare("INSERT INTO booking (activity_id, teacher_id, room_id, day_time_id, reason) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['activity'], $teacherID, $_POST['room'], $date_timeID, $_POST['reason']]); // Use array of parameters
            } else {
                $stmt = $conn->prepare("INSERT INTO booking (teacher_id, room_id, day_time_id, reason) VALUES (?, ?, ?, ?)");
                $stmt->execute([$teacherID, $_POST['room'], $date_timeID, $_POST['reason']]); // Use array of parameters
            }
            http_response_code(200);
            $response = array("message" => "Booking added successfully");
            echo json_encode($response);
        } else {
            http_response_code(401);
            $response = array("message" => "Unauthorized user");
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
