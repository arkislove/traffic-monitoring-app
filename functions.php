<?php
function connectToDB() {
    $conn = new mysqli(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function calculateAndPrintTimeDifference($created_at){
    date_default_timezone_set('Asia/Manila');
    $current_time = new DateTime();
    $created_at_time = new DateTime($created_at);
    $interval = $current_time->diff($created_at_time);

    $seconds_ago = $interval->s + ($interval->i * 60) + ($interval->h * 3600) + ($interval->days * 86400);
    $minutes_ago = round($seconds_ago / 60);
    $hours_ago = $interval->h + ($interval->days * 24);
    $days_ago = $interval->days;

    if ($seconds_ago < 60) {
        $created_at = $seconds_ago . " seconds ago";
    } elseif ($minutes_ago < 60) {
        $created_at = $minutes_ago . " minutes ago";
    } elseif ($hours_ago < 24) {
        $created_at = $hours_ago . " hours ago";
    } else {
        $created_at = $days_ago . " days ago";
    }
    return $created_at;
}

function getLicensePlates($conn, $location_id) {
    $sql = "SELECT * FROM `vehicles` WHERE `locations_id` = $location_id LIMIT 50";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($vehicleRow = $result->fetch_assoc()) {
            if (empty($vehicleRow['plate_number'])) {
                echo "<li class='red'>" . $vehicleRow['type'] . " - N/A</li>";
            } else {
                echo "<li class='green'>" . $vehicleRow['type'] . " - " . $vehicleRow['plate_number'] . "</li>";
            }
        }
    } else {
        echo "<li>No license plates found for the selected location.</li>";
    }
}

function getViolatorsCount($conn){
    $sql = "SELECT COUNT(*) AS violatorsCount FROM violators";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<p>Total violators: " . $row['violatorsCount'] . "</p>";
                } else {
                    echo "No violators found.";
                }
}
?>

