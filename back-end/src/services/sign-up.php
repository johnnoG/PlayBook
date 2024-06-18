<?php
require_once 'Database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = Database::getInstance();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];
    $rate = $_POST['rate'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $strong_foot = $_POST['strong_foot'];
    $preferred_position = $_POST['position'];
    $nickname = $_POST['nickname'];
    $city = $_POST['city'];

    // Handle file upload
    $picture = null;
    if (isset($_FILES['myPic']) && $_FILES['myPic']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $upload_file = $upload_dir . basename($_FILES['myPic']['name']);
        if (move_uploaded_file($_FILES['myPic']['tmp_name'], $upload_file)) {
            $picture = $upload_file;
        }
    }

    $data = [
        'Email' => $email,
        'FullName' => $first_name . ' ' . $last_name,
        'Birthday' => $birthday,
        'Password' => $password,
        'StrongFoot' => $strong_foot,
        'PreferredPosition' => $preferred_position,
        'Nickname' => $nickname,
        'City' => $city,
        'Rating' => $rate,
        'Picture' => $picture,
        'Phone' => $phone
    ];

    // Prepare the SQL query
    $query = "INSERT INTO Players (Email, FullName, Birthday, Password, StrongFoot, PreferredPosition, Nickname, City, Rating, Picture, Phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: (' . $conn->errno . ') ' . $conn->error]);
        exit;
    }

    $stmt->bind_param(
        "sssssssssss",
        $data['Email'],
        $data['FullName'],
        $data['Birthday'],
        $data['Password'],
        $data['StrongFoot'],
        $data['PreferredPosition'],
        $data['Nickname'],
        $data['City'],
        $data['Rating'],
        $data['Picture'],
        $data['Phone']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Execute failed: (' . $stmt->errno . ') ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
