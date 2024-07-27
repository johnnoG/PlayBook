<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_verified']) && $_SESSION['user_verified'] === true) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}
