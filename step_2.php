<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"/>

</head>
<body>


<?php
    include_once "mapmyfitness-php-sdk/MMF.php";
    include_once("mapmyfitness-php-sdk/MMF_OAuth.php");

    // For Testing
//    define("ACCESS_TOKEN", "Your accessToken here");
//    define("ACCESS_TOKEN_SECRET", "Your accessTokenSecret here");
//    $accessToken = ACCESS_TOKEN;
//    $accessTokenSecret = ACCESS_TOKEN_SECRET;

    // The callback returns the accessToken and accessToken Secret
    $credentials = MMF_OAuth::getAccessToken($_GET["oauth_token"], $_GET["oauth_verifier"]);
    $accessToken = $credentials['access_token'];
    $accessTokenSecret = $credentials['access_token_secret'];

    // Grab your personal MapMyFitness ID
    $myself = MMF::getAuthenticatedUser($accessToken, $accessTokenSecret);

//**********  GET WORKOUTS FOR USER ***************************************************************

    // Grab users workout data and store in myWorkouts variable.
    $workouts = MMF::getWorkoutsForUser($accessToken, $accessTokenSecret, $myself['id']);

    //** Displays all the data from getWorkoutsForUser() */
//    echo '<pre>';
//    print_r($myWorkouts);
//    echo '</pre>';

?>
<div style="background-color: #737373; padding: 50px 0;">
<div class="container">
    <div>
        <h1> Data From getWorkoutsForUser()</h1>
    </div>

    <?php
    // Run for each recent workout returned from API
    foreach ($workouts as $workout) {
        $activityType = MMF::getActivityType($accessToken, $accessTokenSecret, $workout['_links']['activity_type'][0]['id']);
        $timeZone = new DateTimeZone('America/Denver');
        $datetime = new DateTime($workout["start_datetime"], $timeZone);
        $workoutName = empty($workout["name"]) ? "No Name" : $workout["name"];
        $distance = $workout["aggregates"]["distance_total"] * 0.000621371;
        if($distance > 100)
            $distance = ceil($distance);
        else
            $distance = round($distance, 2);
        $activityTimeTotal = $workout["aggregates"]["active_time_total"];
        $statDetail = $activityTimeTotal;
        $hours = floor($statDetail/3600);
        $minutes = $statDetail - $hours * 3600;
        $minutes = floor($minutes/60);
        $seconds = $statDetail - $hours * 3600 - $minutes * 60;
        if($minutes < 10)
            $minutes = "0" . $minutes;
        if($seconds < 10)
            $seconds = "0" . $seconds;
        $statDetail = $hours . ":" . $minutes . ":" . $seconds;
        $activityTimeTotal = $statDetail;
        $caloriesBurned = isset($workout["aggregates"]["metabolic_energy_total"]) ? $workout["aggregates"]["metabolic_energy_total"] / 4184 : 0;

    ?>

        <div class="recent-workout">
            <div class="well" style="margin:20px;">
                <div class="activity-image">
                    <a href="http://www.mapmyfitness.com/workout/<?php echo $workout['_links']['self'][0]['id'] ?>" target="_new">
                        <img alt="<?php $workout['name']; ?> " src="<?php echo $activityType['_links']['icon_url'][0]['href']; ?>">
                    </a>
                </div>
                <div class="activity-details">
                    <div class="workout-description">
                        <a href="http://www.mapmyfitness.com/workout/<?php echo $workout['_links']['self'][0]['id'] ?>" target="_new">
                            <?php print $workoutName; ?>
                        </a>
                    </div>
                    <div class="workout-data">
                        <div class="workout-data-point">
                            <h4>Distance</h4>
                            <p><span class="medium-number"> <?php print $distance; ?></span> mi</p>
                        </div>
                        <div class="workout-data-point">
                            <h4>Duration</h4>
                            <p><span class="medium-numver"> <?php print $activityTimeTotal; ?></span> </p>
                        </div>
                        <div class="workout-data-point">
                            <h4>Calories Burned</h4>
                            <p><span class="medium-number"> <?php print $caloriesBurned; ?> </span> kCal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<?php
}
?>
</div>
</div>


<div style="background-color: whitesmoke; padding: 50px 0;">
    <div class="container">
     Other End points would display data here
    </div>
</div>


</body>
</html>