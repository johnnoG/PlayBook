<?php
require_once 'db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

if (!isset($_GET['email'])) {
    echo json_encode(['success' => false, 'message' => 'Email parameter is missing.']);
    exit();
}

$email = $_GET['email'];

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
