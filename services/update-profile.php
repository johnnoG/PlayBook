<?php
require_once 'db.php';

session_start(); // Start or resume a session
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_verified']) || !$_SESSION['user_verified']) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$email = $_SESSION['user_email']; // Get email from session

$db = Database::getInstance();
$conn = $db->getConnection();

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

$fields = ['FullName', 'Birthday', 'Age', 'City', 'PreferredPosition', 'Email', 'Phone', 'StrongFoot', 'Gender', 'Picture', 'Rating'];
$updates = [];
$params = [];
$types = '';

foreach ($fields as $field) {
    if (isset($input[$field])) {
        $updates[] = "$field = ?";
        $params[] = $input[$field];
        $types .= 's'; // Assuming all fields are strings. Adjust the types accordingly.
    }
}

if (empty($updates)) {
    echo json_encode(['success' => false, 'message' => 'No fields to update.']);
    exit();
}

$query = "UPDATE Players SET " . implode(', ', $updates) . " WHERE Email = ?";
$params[] = $email;
$types .= 's';

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param($types, ...$params);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute statement failed: ' . $stmt->error]);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);

$stmt->close();
$conn->close();
