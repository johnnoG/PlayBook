<?php
require_once 'db.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $email = $_GET['email']; // Assuming email is passed as a query parameter

    // Prepare and execute the query
    $query = "SELECT Email, FullName, Birthday, StrongFoot, PreferredPosition, Nickname, City, Rating, Picture, Phone FROM Players WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $userData['Age'] = calculateAge($userData['Birthday']); // Add Age calculation

        echo json_encode(['success' => true, 'data' => $userData]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Helper function to calculate age
function calculateAge($birthday)
{
    $birthDate = new DateTime($birthday);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;
    return $age;
}
