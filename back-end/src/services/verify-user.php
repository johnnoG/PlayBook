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
        $stmt->bind_result($stored_password);
        $stmt->fetch();

        // Debugging: Check if stored_password is fetched correctly
        error_log("Stored Password from DB: " . $stored_password);

        if ($password === $stored_password) {
            // Debugging: Check if password verification succeeds
            error_log("Password verification succeeded");

            // Redirect to MainPage.html after successful login
            header("Location: http://toharhermon959.byethost9.com/PlayBook/front-end/components/MainPage.html");
            exit();
        } else {
            // Debugging: Check if password verification fails
            error_log("Password verification failed");
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
