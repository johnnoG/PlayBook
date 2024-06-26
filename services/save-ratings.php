<?php
require_once 'db.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fieldID = $_POST['fieldID'];
    $playerEmail = $_POST['playerEmail'];
    $fieldRating = $_POST['fieldRating'];
    $playerRating = $_POST['playerRating'];
    $playedOn = $_POST['playedOn'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update field rating
        $query = "UPDATE field SET Rating = ? WHERE fieldID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $fieldRating, $fieldID);
        $stmt->execute();

        // Update player field contact with the rating received
        $query = "INSERT INTO PlayerFieldContact (PlayerEmail, fieldID, PlayedOn, RatingReceived) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE RatingReceived = VALUES(RatingReceived)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sisi", $playerEmail, $fieldID, $playedOn, $playerRating);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Ratings saved successfully.']);
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Failed to save ratings: ' . $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
