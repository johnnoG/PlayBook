<?php
require_once 'db.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['psw'];

    // Get the connection
    $conn = $db->getConnection();

    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
    }

    // Prepare the SQL query
    $query = "SELECT Password FROM Players WHERE Email = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die(json_encode(['success' => false, 'message' => 'Prepare statement failed: ' . $conn->error]));
    }

    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        die(json_encode(['success' => false, 'message' => 'Execute statement failed: ' . $stmt->error]));
    }

    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Redirect to MainPage.html after successful login
            header("Location: PlayBook/front-end/components/MainPage.html");
            exit(); // Make sure to exit after the header redirection
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
