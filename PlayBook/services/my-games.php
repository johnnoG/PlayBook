<?php
session_start();

if (!isset($_SESSION['user_verified']) || !$_SESSION['user_verified']) {
    die("User not logged in.");
}

if (!isset($_SESSION['user_email'])) {
    die("User email not found in session.");
}

$email = $_SESSION['user_email']; // Get email from session


$servername = "sql305.byethost9.com";
$username = "b9_36704350";
$password = "@ToharYO123";
$dbname = "b9_36704350_PlayBook";

// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT ContactID, PlayerEmail, fieldID, fieldLocation, gameTime FROM PlayerFieldContact WHERE PlayerEmail = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    die("Execute statement failed: " . $stmt->error);
}

$result = $stmt->get_result();
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Games</title>
    <style type="text/css">
        table {
            margin: 8px;
            border-collapse: collapse;
        }

        h1 {
            font-size: 70px;
            font-weight: 600;
            color: #fdfdfe;
            text-shadow: 0px 0px 5px #b393d3, 0px 0px 10px #b393d3, 0px 0px 10px #b393d3, 0px 0px 20px #b393d3;
        }

        th {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 30px;
            background: #666;
            color: #FFF;
            padding: 2px 6px;
            border: 1px solid #000;
        }

        td {
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 30px;
            border: 1px solid #DDD;
            padding: 15px;
        }

        body {
            background: #00467F;
            background: -webkit-linear-gradient(to left, #A5CC82, #00467F);
            background: linear-gradient(to left, #A5CC82, #00467F);
        }

        nav {
            background: #333;
            padding: 10px;
        }

        nav a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
        }

        .containerNav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <nav>
        <div class="containerNav">
            <div><a href="../pages/main-page.html">Home</a></div>
            <div><a href="../services/player-profile.php">Profile</a></div>
            <div><a href="#">My games</a></div>
            <div><a href="../pages/game-location.html">Book a game</a></div>
            <div><a href="../services/log-out.php">Log Out</a></div>
        </div>
    </nav>
    <center>
        <h1>Welcome to your games!</h1>
    </center>
    <?php
    if ($result->num_rows > 0) {
        echo "<center> <table><tr><th>ID</th><th>Player Email</th><th>Field Location</th><th>Game Time</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["ContactID"]) . "</td><td>" . htmlspecialchars($row["PlayerEmail"]) . "</td><td>" . htmlspecialchars($row["fieldLocation"]) . "</td><td>" . htmlspecialchars($row["gameTime"]) . "</td></tr>";
        }
        echo "</table></center>";
    } else {
        echo "<center><p>No results found.</p></center>";
    }
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>