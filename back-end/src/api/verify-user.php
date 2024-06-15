<?php
require_once "../services/db.php";

function verifyUser($data)
{
    $db = Database::getInstance();
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'];
    $password = $input['password'];
    $result = $db->verifyUser($email, $password);
    if ($result) {
        $response = ['success' => $result];
        $status = 200;
    } else {
        $response = ['error' => 'User not found'];
        $status = 404;
    }
    echo json_encode($response);
}
