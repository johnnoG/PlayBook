<?php
require_once '../services/db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$db = Database::getInstance();

function handleRequest($db, $method, $path)
{
    $response = ['error' => 'Not Found'];
    $status = 404;

    switch ($path[0]) {
        case 'verifyUser':
            if ($method == 'GET') {
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
            } else {
                $response = ['error' => 'Method Not Allowed'];
                $status = 405;
            }
            break;
        case 'insertData':
            if ($method == 'POST' || $method == 'PUT') {
                $input = json_decode(file_get_contents('php://input'), true);
                $table = $input['table'];
                $data = $input['data'];
                $result = $db->insertData($table, $data);
                $response = ['success' => $result];
                $status = 200;
                if ($result) {
                    $response = ['success' => $result];
                    $status = 200;
                } else {
                    $response = ['error' => "Failed to insert data to db"];
                    $status = 500;
                }
            } else {
                $response = ['error' => 'Method Not Allowed'];
                $status = 405;
            }
            break;
        default:
            $response = ['error' => 'Not Found'];
            $status = 404;
            break;
    }

    return [$response, $status];
}

list($response, $status) = handleRequest($db, $method, $path);
http_response_code($status);
echo json_encode($response);
