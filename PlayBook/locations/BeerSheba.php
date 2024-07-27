<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Soccer Fields Information</title>
    <style>
        /* Add your CSS styles here */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: Roboto;
            font-color:green;

        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-family: Roboto;
            font-color:green;
        }
        img {
            max-width: 100px;
            max-height: 100px;
        }


    .containerNav {
    display: flex;
    justify-content: space-around;
    width: 100%;
    padding: 10px 0;
    /* Add some padding for better spacing */
    background-color: #ffffff;
    /* Optional: Add a background color to make it more visible */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Optional: Add some shadow for better visibility */
    font-family: Roboto;
}

    </style>
</head>
<body>
        <div class="containerNav">
            <div><a href="../pages/main-page.html"><span>Home</span></a></div>
            <div><a href="../services/player-profile.php" onclick="profile()">Profile</a></div>
            <div><a href="../services/my-games.php">My games</a></div>
            <div><a href="../pages/game-location.html">Book a game</a></div>
            <div><a href="../services/log-out.php">Log Out</a></div>
        </div>
    <div class="container">
        <h1>Soccer Fields Information</h1>

        <table>
            <thead>
                <tr>
                    <th>Field ID</th>
                    <th>Surface Type</th>
                    <th>Location</th>
                    <th>Number of Players</th>
                    <th>Rating</th>
                    <th>City</th>
                    <th>Take me to field</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Array of URLs for each field
                $fieldUrls = [
                    "Beer_Sheba_Hagefen_43" => "Book_Game_BerrSheba/Beer_Sheba_Hagefen_43.php",
                    "Beer_Sheba_Hatena_54" => "Book_Game_BerrSheba/Beer_Sheba_Hatena_54.php",
                    "Beer_Sheba_Hazit_13" => "Book_Game_BerrSheba/Beer_Sheba_Hazit_13.php"
                    // Add more as needed
                ];

                // Database connection parameters
                $servername = "sql305.byethost9.com";
                $username = "b9_36704350";
                $password = "@ToharYO123";
                $dbname = "b9_36704350_PlayBook";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare SQL statement
                $sql = "SELECT * FROM field WHERE city=?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Prepare statement failed: " . $conn->error);
                }

                // Bind parameter for city
                $city = "Beer-sheba";
                $stmt->bind_param("s", $city);

                // Execute statement
                if (!$stmt->execute()) {
                    die("Execute statement failed: " . $stmt->error);
                }

                // Get result set
                $result = $stmt->get_result();

                // Display data
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["fieldID"] . "</td>";
                        echo "<td>" . $row["SurfaceType"] . "</td>";
                        echo "<td>" . $row["Location"] . "</td>";
                        echo "<td>" . $row["NumPlayers"] . "</td>";
                        echo "<td>" . $row["Rating"] . "</td>";
                        echo "<td>" . $row["city"] . "</td>";

                        // Check if there's a predefined URL for this field
                        $fieldKey = str_replace(" ", "_", $row["Location"]); // Convert location to match array key
                        if (isset($fieldUrls[$fieldKey])) {
                            echo "<td><a href='" . $fieldUrls[$fieldKey] . "' target='_blank'>Take me to field</a></td>";
                        } else {
                            echo "<td>No website link available</td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No soccer fields found in Beer Sheba</td></tr>";
                }

                // Close statement and connection
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
