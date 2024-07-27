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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT fieldID, SurfaceType, city, Location, NumPlayers, Rating ,Picture FROM field WHERE Location = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare statement failed: " . $conn->error);
}
$location = "Jerusalem Hertzog 54";
$stmt->bind_param("s", $location);
if (!$stmt->execute()) {
    die("Execute statement failed: " . $stmt->error);
}

$result = $stmt->get_result();

// Fetching data and storing in PHP variables
if ($row = $result->fetch_assoc()) {
    $fieldID = $row['fieldID'];
    $surfaceType = $row['SurfaceType'];
    $city = $row['city'];
    $location = $row['Location'];
    $Picture = isset($row['Picture']) ? $row['Picture'] :'';
    $numPlayers = $row['NumPlayers'];
    $rating = $row['Rating'];
} else {
    die("No field found for the user's location.");
}

$imagePath = "../../resources/";
$imageSrc = $imagePath. $Picture;

$stmt->close();

// Fetch registered players for each game time
$gameTimes = [
    '16:00 - 18:00',
    '18:00 - 20:00',
    '20:00 - 22:00',
    '22:00 - 00:00'
];

$registeredPlayers = [];
foreach ($gameTimes as $gameTime) {
    $sql = "SELECT Players.Nickname, Players.Rating FROM PlayerFieldContact 
            INNER JOIN Players ON PlayerFieldContact.PlayerEmail = Players.Email
            WHERE PlayerFieldContact.fieldID = ? AND PlayerFieldContact.gameTime = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("is", $fieldID, $gameTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $players = [];
        while ($row = $result->fetch_assoc()) {
            $players[] = $row;
        }
        $registeredPlayers[$gameTime] = $players;
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beer Sheba Hagefen 43</title>
    <link rel="stylesheet" href="../../styles/field-times.css">
    <style>
        .game-cube {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            background-color: #4CAF50;
            margin: 10px;
            border-radius: 10px;
            color: white;
        }
        .players-list {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: left;
            width: 100%;
        }
        .join-button {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav>
        <div class="containerNav">
            <div><a href="../../pages/main-page.html"><span>Home</span></a></div>
            <div><a href="../../services/player-profile.php" onclick="profile()">Profile</a></div>
            <div><a href="../../services/my-games.php">My games</a></div>
            <div><a href="../../pages/game-location.html">Book a game</a></div>
            <div><a href="../../services/log-out.php">Log Out</a></div>
        </div>
    </nav>

    <div id="lot-details">
        <img id="lot-image" src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Lot Image">
        <div id="lot-details-info">
            <div id="address">Address: <?php echo htmlspecialchars($location); ?></div>
            <div id="surface">Surface: <?php echo htmlspecialchars($surfaceType); ?></div>
            <div id="players">Players: <?php echo htmlspecialchars($numPlayers); ?></div>
            <div id="ranking">Ranking: <?php echo htmlspecialchars($rating); ?></div>
        </div>
    </div>

    <h1>Choose your Game:</h1>
    <h2>Available Hours</h2>
    <div class="game-cubes">
        <?php
        foreach ($gameTimes as $gameTime) {
            echo "
                <div class='game-cube'>
                    <div class='game-info'>Game at $gameTime</div>
                    <div class='players-list' data-game-time='$gameTime'>";
            if (isset($registeredPlayers[$gameTime])) {
                foreach ($registeredPlayers[$gameTime] as $player) {
                    echo "<div>" . htmlspecialchars($player['Nickname']) . " (Rating: " . htmlspecialchars($player['Rating']) . ")</div>";
                }
            }
            echo "</div>
                    <div class='registrants'>Registrants: <span class='registrant-count' data-game-time='$gameTime'>" . count($registeredPlayers[$gameTime] ?? []) . "</span></div>
                    <button class='join-button' data-game-time='$gameTime'>Join Game</button>
                </div>";
        }
        ?>
    </div>

    <script>
        document.querySelectorAll('.join-button').forEach(button => {
    button.addEventListener('click', async function() {
        const gameTime = this.getAttribute('data-game-time');
        const fieldID = <?php echo json_encode($fieldID); ?>;
        const location = <?php echo json_encode($location); ?>;
        const playerEmail = <?php echo json_encode($email); ?>;
        const players = <?php echo json_encode($numPlayers); ?>;

        try {
            const response = await fetch('../../services/join-game.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    gameTime: gameTime,
                    fieldID: fieldID,
                    location: location,
                    playerEmail: playerEmail,
                    players: players
                })
            });
            const data = await response.json();
            console.log('Join response:', data);
            if (data.success) {
                alert('Successfully joined the game!');
                await updateRegistrantCounts();
            } else {
                alert('Failed to join the game: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

async function updateRegistrantCounts() {
    try {
        const response = await fetch('../../services/get-registrants.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                fieldID: <?php echo json_encode($fieldID); ?>
            })
        });
        const data = await response.json();
        console.log('Fetch response:', data);
        if (data.success) {
            for (let i = 0; i < data.registrants.length; i++) {
                const registrant = data.registrants[i];
                const registrantCountSpan = document.querySelector(`.registrant-count[data-game-time="${registrant.gameTime}"]`);
                if (registrantCountSpan) {
                    registrantCountSpan.textContent = registrant.count;
                }
                const playersList = document.querySelector(`.players-list[data-game-time="${registrant.gameTime}"]`);
                if (playersList) {
                    playersList.innerHTML = '';
                    for (let j = 0; j < registrant.players.length; j++) {
                        const player = registrant.players[j];
                        playersList.innerHTML += `<div>${player.Nickname} (Rating: ${player.Rating})</div>`;
                    }
                }
            }
        } else {
            console.error('Failed to fetch registrant counts:', data.message);
        }
    } catch (error) {
        console.error('Error fetching registrant counts:', error);
    }
}

document.addEventListener('DOMContentLoaded', updateRegistrantCounts);


        function updateGameDetails(gameTime, registrants, players) {
            const registrantCountSpan = document.querySelector(`.registrant-count[data-game-time="${gameTime}"]`);
            if (registrantCountSpan) {
                registrantCountSpan.textContent = registrants;
            }
            const playersList = document.querySelector(`.players-list[data-game-time="${gameTime}"]`);
            if (playersList) {
                playersList.innerHTML = '';
                players.forEach(player => {
                    playersList.innerHTML += `<div>${player.Nickname} (Rating: ${player.Rating})</div>`;
                });
            }
        }
    </script>
</body>
</html>