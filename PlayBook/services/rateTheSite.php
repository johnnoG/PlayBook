
<html>
<head>
<link rel="stylesheet" href="../styles/rateTheSite.css" />
</head>
<body>
  <nav>
        <div class="container">
          <div>
            <a href="../pages/main-page.html" onclick="rate()"><span>Home</span></a>
          </div>
          <div>
            <a href="../services/player-profile.php" onclick="navigateToPage('../services/player-profile.php')">Profile</a>
          </div>
          <div>
            <a href="../services/my-games.php" onclick="navigateToPage('../services/my-games.php')">My games</a>
          </div>
          <div><a href="../services/player-profile.php" onclick="openForm()">Log In</a></div>
          <div><a href="../services/log-out.php">Log Out</a></div>
        </div>
      </nav>
<div id="mainDiv">
<?php
const QUEST = "What is your opinion about PlayBook's ";
$ratingTypes = array("site", "css", "design");
$ratings = array_fill_keys($ratingTypes, 0);

function displayForm($ratingTypes) {
    echo "<h2>Rate PlayBook</h2>";
    echo "<form action='' method='post'>";
    foreach ($ratingTypes as $type) {
        echo "<p>" . QUEST . $type . ": <input type='number' name='" . $type . "' min='1' max='10'></p>";
    }
    echo "<input type='submit' name='submit' value='Submit'>";
    echo "</form>";
}

function processForm($ratingTypes) {
    global $ratings; // Use global to access the ratings array
    $totalRating = 0;
    foreach ($ratingTypes as $type) {
        $rating = (int)$_POST[$type];
        if (!is_numeric($rating) || $rating < 1 || $rating > 10) {
            $ratings[$type] = "Invalid rating";
            continue;
        }
        $ratings[$type] = $rating;
        $totalRating += $rating;
    }
    $averageRating = $totalRating > 0 ? round($totalRating / count($ratings), 1) : 0;
    return $averageRating;
}

if (isset($_POST['submit'])) {
    $averageRating = processForm($ratingTypes);
} else {
    displayForm($ratingTypes);
}
?>

<?php
if (isset($_POST['submit'])):  // Check if form was submitted
?>
    <h2>Results</h2>
    <ul>
        <?php foreach ($ratings as $type => $rating): ?>
            <li>
                <b><?php echo ucfirst($type); ?>:</b>  <?php echo $rating; ?>  
            </li>
        <?php endforeach; ?>
    </ul>
    <p>
        <b>Average Rating:</b> <?php echo $averageRating; ?>  
    </p>
<?php endif; ?>

</div>
</body>
</html>



