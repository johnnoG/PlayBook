<?php
session_start();

if (!isset($_SESSION['user_verified']) || !$_SESSION['user_verified']) {
    die("User not logged in.");
}

if (!isset($_SESSION['user_email'])) {
    die("User email not found in session.");
}

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

$email = $_SESSION['user_email']; // Get email from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['FullName'] ?? '';
    $birthday = $_POST['Birthday'] ?? '';
    $city = $_POST['City'] ?? '';
    $preferredPosition = $_POST['PreferredPosition'] ?? '';
    $emailField = $_POST['Email'] ?? '';
    $phone = $_POST['Phone'] ?? '';
    $strongFoot = $_POST['StrongFoot'] ?? '';
    $picture = $_POST['Picture'] ?? '';

    $query = "UPDATE Players SET FullName=?, Birthday=?, City=?, PreferredPosition=?, Email=?, Phone=?, StrongFoot=?, Picture=? WHERE Email=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssss", $fullName, $birthday, $city, $preferredPosition, $emailField, $phone, $strongFoot, $picture, $email);

    if (!$stmt->execute()) {
        die("Execute statement failed: " . $stmt->error);
    }

    if ($stmt->affected_rows > 0) {
        $message = 'Profile updated successfully.';
    } else {
        $message = 'No changes made to profile.';
    }

    $stmt->close();
}

$sql = "SELECT FullName, Birthday, City, PreferredPosition, Email, Phone, StrongFoot, Picture, Rating FROM Players WHERE Email = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}
$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    die("Execute statement failed: " . $stmt->error);
}
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();

if (!$user) {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>about-me</title>
    <link rel="stylesheet" href="../styles/about-me.css" />
</head>

<body>
    <nav>
        <div class="containerNav">
            <div>
                <a href="../pages/main-page.html"><span>Home</span></a>
            </div>
            <div><a href="../services/player-profile.php" )">Profile</a></div>
            <div><a href="./my-games.php">My games</a></div>
            <div><a href="../pages/game-location.html">Book a game</a></div>
            <div><a href="../services/log-out.php">Log Out</a></div>
        </div>
    </nav>
    <section class="section about-section gray-bg" id="about">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6">
                    <div class="about-text go-to">
                        <h3 class="dark-color">Profile</h3>
                        <h6 class="theme-color lead">
                            Soccer player in position <span id="position"><?php echo htmlspecialchars($user['PreferredPosition'] ?? ''); ?></span>
                        </h6>
                        <form method="POST" action="">
                            <p class="data" id="about">
                                <label>Full Name</label>
                            <div class="view-mode"><?php echo htmlspecialchars($user['FullName'] ?? ''); ?></div>
                            <input type="text" name="FullName" value="<?php echo htmlspecialchars($user['FullName'] ?? ''); ?>" class="edit-mode" style="display: none;">
                            </p>
                            <div class="row about-list">
                                <div class="col-md-6">
                                    <div class="media">
                                        <label>Birthday</label>
                                        <div class="view-mode"><?php echo htmlspecialchars($user['Birthday'] ?? ''); ?></div>
                                        <input type="date" name="Birthday" value="<?php echo htmlspecialchars($user['Birthday'] ?? ''); ?>" class="edit-mode" style="display: none;">
                                    </div>
                                    <div class="media">
                                        <label>City</label>
                                        <div class="view-mode"><?php echo htmlspecialchars($user['City'] ?? ''); ?></div>
                                        <input type="text" name="City" value="<?php echo htmlspecialchars($user['City'] ?? ''); ?>" class="edit-mode" style="display: none;">
                                    </div>
                                    <div class="media">
                                        <label>Position</label>
                                        <div class="view-mode"><?php echo htmlspecialchars($user['PreferredPosition'] ?? ''); ?></div>
                                        <input type="text" name="PreferredPosition" value="<?php echo htmlspecialchars($user['PreferredPosition'] ?? ''); ?>" class="edit-mode" style="display: none;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="media">
                                        <label>E-mail</label>
                                        <div class="view-mode"><?php echo htmlspecialchars($user['Email'] ?? ''); ?></div>
                                        <input type="email" name="Email" value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>" class="edit-mode" style="display: none;">
                                    </div>
                                    <div class="media">
                                        <label>Phone</label>
                                        <div class="view-mode"><?php echo htmlspecialchars($user['Phone'] ?? ''); ?></div>
                                        <input type="text" name="Phone" value="<?php echo htmlspecialchars($user['Phone'] ?? ''); ?>" class="edit-mode" style="display: none;">
                                    </div>
                                    <div class="media">
                                        <label>Foot</label>
                                        <div class="view-mode"><?php echo htmlspecialchars($user['StrongFoot'] ?? ''); ?></div>
                                        <input type="text" name="StrongFoot" value="<?php echo htmlspecialchars($user['StrongFoot'] ?? ''); ?>" class="edit-mode" style="display: none;">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" id="saveChanges" style="display: none;">Save Changes</button>
                        </form>
                        <?php if (isset($message)) {
                            echo "<p>$message</p>";
                        } ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-avatar">
                        <img id="profile-picture" src="<?php echo htmlspecialchars($user['Picture'] ?? 'https://bootdey.com/img/Content/avatar/avatar7.png'); ?>" title="" alt="Profile Picture" />
                    </div>
                </div>
            </div>
            <div class="counter">
                <div class="row">
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" id="played">data base</h6>
                            <p class="m-0px font-w-600">Played</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" id="rating"><?php echo htmlspecialchars($user['Rating'] ?? ''); ?></h6>
                            <p class="m-0px font-w-600">Rating</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" id="fields">data base</h6>
                            <p class="m-0px font-w-600">Fields</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" id="likes">data base</h6>
                            <p class="m-0px font-w-600">Likes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button id="edit" onclick="editProfile()">Edit profile</button>
    </section>

    <script>
        function editProfile() {
            document.querySelectorAll('.view-mode').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelectorAll('.edit-mode').forEach(el => {
                el.style.display = 'block';
            });
            document.getElementById('edit').style.display = 'none';
            document.getElementById('saveChanges').style.display = 'inline-block';
        }
    </script>
</body>

</html>