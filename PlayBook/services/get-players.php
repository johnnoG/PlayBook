<?php
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$fieldID = $data['fieldID'];
$gameTime = $data['gameTime'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

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

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'players' => $players]);
?>
