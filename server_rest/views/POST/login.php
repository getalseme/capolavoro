<?php
require 'class/DB.php';
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

header('Content-Type: application/json; charset=utf-8');

function generateToken($email ,$behaviour) {
    $secret_key = "il potere logora chi non ce l'ha";
    $token_payload = array(
        "email" => $email,
        "student" => $behaviour[0],
        "teacher" => $behaviour[1],
        "admin" => $behaviour[2],
        "exp" => time() + 3600 * 60 // Scadenza del token dopo 1 ora
    );

    $token = JWT::encode($token_payload, $secret_key, 'HS256');
    return $token;
}

$db = new DB();
$connection = $db->getConnection();

$email = $_POST['email'];
$password = hash('SHA256', $_POST['password']);

$query = "SELECT U.password 
          FROM users AS U
          WHERE U.email = :email";



try {
    $statement = $connection->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if email exists in the database
    if ($result) {
        $stored_password = $result['password'];
        // Check if the provided password matches the stored password
        if ($password === $stored_password) {
            // Password is correct

            $queryStud = "SELECT U.email FROM users AS U INNER JOIN student ON student.user_id = U.email WHERE U.email = :email";
            $queryTeach = "SELECT U.email FROM users AS U INNER JOIN teacher ON teacher.user_id = U.email WHERE U.email = :email";
            $queryAdmin = "SELECT U.email FROM users AS U INNER JOIN admin ON admin.user_id = U.email WHERE U.email = :email";

            // Check which authorisetions the account have

                $statement1 = $connection->prepare($queryStud);
                $statement1->bindParam(':email', $email);
                $statement1->execute();
                $resultStud = $statement1->fetchAll(PDO::FETCH_ASSOC);
                
                $statement2 = $connection->prepare($queryTeach);
                $statement2->bindParam(':email', $email);
                $statement2->execute();
                $resultTeach = $statement2->fetchAll(PDO::FETCH_ASSOC);

                $statement3 = $connection->prepare($queryAdmin);
                $statement3->bindParam(':email', $email);
                $statement3->execute();
                $resultAdmin = $statement3->fetchAll(PDO::FETCH_ASSOC);

                $behaviour = array();

                $behaviour[0] = (sizeof($resultStud) > 0) ? true : false;
                $behaviour[1] = (sizeof($resultTeach) > 0) ? true : false;
                $behaviour[2] = (sizeof($resultAdmin) > 0) ? true : false;

                $token = generateToken($email, $behaviour);

                http_response_code(200);
                $response = array("message" => "Password is correct", "email" => "$email", "token" => "$token", "student" => $behaviour[0], "teacher" => $behaviour[1], "admin" => $behaviour[2]);
                //$response = array("message" => "Password is correct");
                echo json_encode($response);         
        } else {
            // Password is incorrect
            http_response_code(401);
            $response = array("message" => "Incorrect password");
            echo json_encode($response);
        }
    } else {
        // Email not found in the database
        http_response_code(404);
        $response = array("message" => "Email not found");
        echo json_encode($response);
    }
} catch(PDOException $e) {
    // Error occurred during query execution
    http_response_code(500);
    $response = array("message" => "Internal server error");
    echo json_encode($response);
}



?>