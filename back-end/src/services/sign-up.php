<?php
require_once 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = Database::getInstance();
$conn = $db->getConnection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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

    // // Handle file upload
    // $picture = null;
    // if (isset($_FILES['myPic']) && $_FILES['myPic']['error'] === UPLOAD_ERR_OK) {
    //     $upload_dir = 'uploads/';
    //     if (!is_dir($upload_dir)) {
    //         mkdir($upload_dir, 0777, true);
    //     }
    //     $upload_file = $upload_dir . basename($_FILES['myPic']['name']);
    //     if (move_uploaded_file($_FILES['myPic']['tmp_name'], $upload_file)) {
    //         $picture = $upload_file;
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
    //         exit;
    //     }
    // } else {
    //     $upload_error = $_FILES['myPic']['error'];
    //     $upload_error_message = match ($upload_error) {
    //         UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
    //         UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
    //         UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
    //         UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
    //         UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
    //         UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
    //         UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
    //         default => 'Unknown upload error.'
    //     };
    //     echo json_encode(['success' => false, 'message' => 'File upload error: ' . $upload_error_message]);
    //     exit;
    // }

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
        'Picture' => "",
        'Phone' => $phone
    ];

    // Prepare the SQL query
    $query = "INSERT INTO `Players` (Email, FullName, Birthday, Password, StrongFoot, PreferredPosition, Nickname, City, Rating, Picture, Phone) VALUES ({$data['Email']},{$data['FullName']},{$data['Birthday']}, {$data['Password']} ,{$data['StrongFoot']}, {$data['PreferredPosition']}, {$data['Nickname']}, {$data['City']}, {$data['Rating']}, {$data['Picture']}, {$data['Phone']})";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
