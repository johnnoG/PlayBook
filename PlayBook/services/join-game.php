<?php
session_start();

if (!isset($_SESSION['user_verified']) || !$_SESSION['user_verified']) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

if (!isset($_SESSION['user_email'])) {
    echo json_encode(['success' => false, 'message' => 'User email not found in session.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['gameTime'], $data['fieldID'], $data['location'], $data['playerEmail'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data.']);
    exit();
}

$servername = "sql305.byethost9.com";
$username = "b9_36704350";
$password = "@ToharYO123";
$dbname = "b9_36704350_PlayBook";

// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if the player is already registered for the game
$check_query = "SELECT COUNT(*) as count FROM PlayerFieldContact WHERE fieldID = ? AND gameTime = ? AND PlayerEmail = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("sss", $data['fieldID'], $data['gameTime'], $data['playerEmail']);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$check_row = $check_result->fetch_assoc();
$current_registration = $check_row['count'];
$check_stmt->close();

if ($current_registration > 0) {
    echo json_encode(['success' => false, 'message' => 'You are already registered for this game.']);
    exit();
}

// Check the current number of registrants for the game
$registrant_query = "SELECT COUNT(*) as count FROM PlayerFieldContact WHERE fieldID = ? AND gameTime = ?";
$registrant_stmt = $conn->prepare($registrant_query);
$registrant_stmt->bind_param("ss", $data['fieldID'], $data['gameTime']);
$registrant_stmt->execute();
$registrant_result = $registrant_stmt->get_result();
$registrant_row = $registrant_result->fetch_assoc();
$current_registrants = $registrant_row['count'];
$registrant_stmt->close();

// Get the maximum number of players
$max_players = $data['players'];

if ($current_registrants >= $max_players) {
    echo json_encode(['success' => false, 'message' => 'The game is already full.']);
    exit();
}

// Insert the new registrant
$query = "INSERT INTO PlayerFieldContact (PlayerEmail, fieldID, FieldLocation, gameTime) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ssss", $data['playerEmail'], $data['fieldID'], $data['location'], $data['gameTime']);

if ($stmt->execute()) {
    // Get the updated number of registrants
    $new_count_query = "SELECT COUNT(*) as count FROM PlayerFieldContact WHERE fieldID = ? AND gameTime = ?";
    $new_count_stmt = $conn->prepare($new_count_query);
    $new_count_stmt->bind_param("ss", $data['fieldID'], $data['gameTime']);
    $new_count_stmt->execute();
    $new_count_result = $new_count_stmt->get_result();
    $new_count_row = $new_count_result->fetch_assoc();
    $new_registrants = $new_count_row['count'];
    $new_count_stmt->close();

    echo json_encode(['success' => true, 'message' => 'Successfully joined the game!', 'registrants' => $new_registrants]);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute statement failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
