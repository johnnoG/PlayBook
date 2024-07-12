<?php
require_once 'db.php';

session_start(); // Start or resume a session
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the user is logged in
if (!isset($_SESSION['user_verified']) || !$_SESSION['user_verified']) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$email = $_SESSION['user_email']; // Get email from session

$db = Database::getInstance();
$conn = $db->getConnection();

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

$query = "SELECT FullName, Birthday, Age, City, PreferredPosition, Email, Phone, StrongFoot, Gender, Picture, Rating FROM Players WHERE Email = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute statement failed: ' . $stmt->error]);
    exit();
}

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
}

$stmt->close();
$conn->close();
