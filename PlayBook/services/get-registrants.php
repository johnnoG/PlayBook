<?php
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$fieldID = $data['fieldID'];

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

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch registrant counts and player details for each game time
$gameTimes = [
    '16:00 - 18:00',
    '18:00 - 20:00',
    '20:00 - 22:00',
    '22:00 - 00:00'
];

$registrants = [];

foreach ($gameTimes as $gameTime) {
    $sql = "SELECT Players.Nickname, Players.Rating FROM PlayerFieldContact 
            INNER JOIN Players ON PlayerFieldContact.PlayerEmail = Players.Email
            WHERE PlayerFieldContact.fieldID = ? AND PlayerFieldContact.gameTime = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]));
    }
    $stmt->bind_param("is", $fieldID, $gameTime);
    $stmt->execute();
    $result = $stmt->get_result();

    $players = [];
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }

    $registrants[] = [
        'gameTime' => $gameTime,
        'count' => count($players),
        'players' => $players
    ];

    $stmt->close();
}

$conn->close();

echo json_encode(['success' => true, 'registrants' => $registrants]);
?>
