<?php
require_once 'db.php';

$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $full_name = $_POST['first_name'] . ' ' . $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $strong_foot = $_POST['strong_foot'];
    $preferred_position = $_POST['position'];
    $nickname = $_POST['nickname'];
    $city = $_POST['city'];
    $rating = $_POST['rate'];

    // Handle file upload
    if (isset($_FILES['myPic']) && $_FILES['myPic']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $upload_file = $upload_dir . basename($_FILES['myPic']['name']);
        if (move_uploaded_file($_FILES['myPic']['tmp_name'], $upload_file)) {
            $picture = $upload_file;
        } else {
            $picture = null;
        }
    } else {
        $picture = null;
    }

    $data = [
        'Email' => $email,
        'FullName' => $full_name,
        'Birthday' => $birthday,
        'Password' => $password,
        'StrongFoot' => $strong_foot,
        'PreferredPosition' => $preferred_position,
        'Nickname' => $nickname,
        'City' => $city,
        'Rating' => $rating,
        'Picture' => $picture
    ];

    $result = $db->insertData('Players', $data);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
