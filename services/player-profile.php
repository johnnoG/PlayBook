<?php
require_once 'db.php';

session_start();
header('Content-Type: application/json');

function log_error($message)
{
    error_log($message, 3, '/path/to/your/logs/error.log'); // Change the path to your log file
}

// Check if the user is logged in
if (!isset($_SESSION['user_verified']) || !$_SESSION['user_verified']) {
    $error_message = 'User not logged in.';
    log_error($error_message);
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
}

$email = $_SESSION['user_email']; // Get email from session

$db = Database::getInstance();
$conn = $db->getConnection();

if ($conn->connect_error) {
    $error_message = 'Database connection failed: ' . $conn->connect_error;
    log_error($error_message);
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
}

$query = "SELECT FullName, Birthday, Age, City, PreferredPosition, Email, Phone, StrongFoot, Gender, Picture, Rating FROM Players WHERE Email = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    $error_message = 'Prepare statement failed: ' . $conn->error;
    log_error($error_message);
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    $error_message = 'Execute statement failed: ' . $stmt->error;
    log_error($error_message);
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit();
}

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $user]);
} else {
    $error_message = 'User not found.';
    log_error($error_message);
    echo json_encode(['success' => false, 'message' => $error_message]);
}

$stmt->close();
$conn->close();
