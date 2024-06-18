<?php
require_once 'db.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Prepare and execute the query to join the field and PlayerFieldContact tables
    $query = "
        SELECT 
            f.fieldID, 
            f.FieldType, 
            f.SurfaceType, 
            f.Location, 
            f.NumPlayers, 
            f.Picture, 
            f.Rating AS FieldRating,
            pfc.ContactID, 
            pfc.PlayerEmail, 
            pfc.PlayedOn, 
            pfc.RatingReceived
        FROM 
            field AS f
        LEFT JOIN 
            PlayerFieldContact AS pfc ON f.fieldID = pfc.fieldID
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fields = [];
        while ($row = $result->fetch_assoc()) {
            $fields[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $fields]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
