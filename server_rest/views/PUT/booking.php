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
        return (array) $decoded; // Cast to array
    } catch (Exception $e) {
        return null; // Token non valido o scaduto
    }
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);

    // Log $_PUT data for debugging
    file_put_contents('php://stderr', "PUT Data: " . print_r($_PUT, true));

    if (isset($_COOKIE['zToken'])) {
        $decodedToken = verifyToken($_COOKIE['zToken']);
        if ($decodedToken !== null) {
            $db = new DB();
            $conn = $db->getConnection();
            $email = $decodedToken['email'];
            if ($decodedToken['teacher'] === true || $decodedToken['admin'] === true) {
                if (isset($_PUT['day_time_id']) && isset($_PUT['room_id']) && isset($_PUT['teacher_id']) && isset($_PUT['activity_id']) && isset($_PUT['reason'])) {
                    $day_time_id = $_PUT['day_time_id'];
                    $room_id = $_PUT['room_id'];
                    $teacher_id = $_PUT['teacher_id'];
                    $activity_id = $_PUT['activity_id'];
                    $reason = $_PUT['reason'];

                    // Log received parameters
                    file_put_contents('php://stderr', "Received parameters: day_time_id=$day_time_id, room_id=$room_id, teacher_id=$teacher_id, activity_id=$activity_id, reason=$reason\n", FILE_APPEND);

                    try {
                        // Assicurati che activity_id faccia riferimento alla colonna id di activity
                        $stmt = $conn->prepare("UPDATE booking SET activity_id = ?, reason = ? WHERE day_time_id = ? AND room_id = ?");
                        if ($stmt->execute([$activity_id, $reason, $day_time_id, $room_id])) {
                            http_response_code(200);
                            $response = array("message" => "Booking updated successfully");
                            echo json_encode($response);
                        } else {
                            throw new Exception("Failed to execute statement");
                        }
                    } catch (Exception $e) {
                        http_response_code(500);
                        $response = array("message" => "Failed to update booking", "error" => $e->getMessage());
                        echo json_encode($response);
                    }
                } else {
                    http_response_code(400);
                    $response = array("message" => "Missing parameters day_time_id, room_id, teacher_id, activity_id, or reason");
                    echo json_encode($response);
                }
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
} else {
    http_response_code(405);
    $response = array("message" => "Method not allowed");
    echo json_encode($response);
}
?>
