<?php
require_once 'db.php';

session_start(); // Start or resume a session
header('Content-Type: application/json');
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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input.']);
    exit();
}

// Validate and sanitize input
$fullName = $input['FullName'] ?? null;
$birthday = $input['Birthday'] ?? null;
$age = $input['Age'] ?? null;
$city = $input['City'] ?? null;
$preferredPosition = $input['PreferredPosition'] ?? null;
$phone = $input['Phone'] ?? null;
$strongFoot = $input['StrongFoot'] ?? null;
$gender = $input['Gender'] ?? null;
$picture = $input['Picture'] ?? null;
$rating = $input['Rating'] ?? null;

// Ensure all required fields are present
if (!$fullName || !$birthday || !$age || !$city || !$preferredPosition || !$phone || !$strongFoot || !$gender || !$picture || !$rating) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit();
}

// Update user profile
$query = "UPDATE Players SET FullName=?, Birthday=?, Age=?, City=?, PreferredPosition=?, Phone=?, StrongFoot=?, Gender=?, Picture=?, Rating=? WHERE Email=?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ssissssssss", $fullName, $birthday, $age, $city, $preferredPosition, $phone, $strongFoot, $gender, $picture, $rating, $email);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute statement failed: ' . $stmt->error]);
    exit();
}

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No changes made to profile.']);
}

$stmt->close();
$conn->close();
