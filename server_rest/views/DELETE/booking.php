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

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Utilizza $_GET per ottenere i parametri dalla query string
    if (isset($_COOKIE['zToken'])) {
        $decodedToken = verifyToken($_COOKIE['zToken']);
        if ($decodedToken !== null) {
            $db = new DB();
            $conn = $db->getConnection();
            $email = $decodedToken['email'];
            if ($decodedToken['teacher'] === true || $decodedToken['admin'] === true) {
                if (isset($_GET['day_time_id']) && isset($_GET['room_id'])) {
                    $day_time_id = $_GET['day_time_id'];
                    $room_id = $_GET['room_id'];

                    $stmt = $conn->prepare("DELETE FROM booking WHERE day_time_id = ? AND room_id = ?");
                    if ($stmt->execute([$day_time_id, $room_id])) {
                        http_response_code(200);
                        $response = array("message" => "Booking deleted successfully");
                        echo json_encode($response);
                    } else {
                        http_response_code(500);
                        $response = array("message" => "Failed to delete booking");
                        echo json_encode($response);
                    }
                } else {
                    http_response_code(400);
                    $response = array("message" => "Missing parameters day_time_id or room_id");
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
