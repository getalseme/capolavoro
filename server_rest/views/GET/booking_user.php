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

if (isset($_COOKIE['zToken'])) {
    $decodedToken = verifyToken($_COOKIE['zToken']);
    if ($decodedToken !== null) {
        if ($decodedToken->teacher) {
            $db = new DB();
            $conn = $db->getConnection();
            $teacher_email = $decodedToken->email;
            $query = 
                'SELECT 
                    b.teacher_id AS teacher_id,
                    b.room_id AS room_id,
                    b.day_time_id,
                    b.day AS day, 
                    b.hour AS hour, 
                    b.room_name AS room_name, 
                    b.activity_name AS activity_name, 
                    b.reason AS reason
                FROM 
                    view_booking b
                JOIN 
                    teacher t ON t.id = b.teacher_id
                WHERE 
                    t.user_id = :email
            ';
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $teacher_email, PDO::PARAM_STR);
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($bookings);
        } else {
            http_response_code(403);
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
