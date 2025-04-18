<!DOCTYPE html>
<?php
require('config/constants.php');
require('functions.php');
$conn = connectToDB();

if (isset($_SESSION['selected_location_id'])) {
    $selected_location_id = $_SESSION['selected_location_id'];
} else {
    $selected_location_id = 1;
    $_SESSION['selected_location_id'] = $selected_location_id;
}
echo "Selected Location ID: " . $selected_location_id;
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Monitoring App</title>
    <link rel="stylesheet" type="text/css" href="styles.css?v=2">
    <script src="script.js" defer></script>
</head>

<body>
    <div class="grid-container">
        <div class="grid-item license-plate-detection">
            <h2>Live Video Feed</h2>
            <div id="video-container">
                <button id="startStreamingButton" onclick="startStreaming()">Start Streaming</button>
                <button id="hideButton">Hide Video</button>
                <canvas id="canvas" width="640" height="480"></canvas>
            </div>
        </div>
        <div class="grid-item license-plate-dashboard">
            <h2>License Plate Dashboard</h2>
            <ul class="license-plate-list">
                <?php getLicensePlates($conn, $selected_location_id)?>
            </ul>
            <a href="plates-list.php">
                <button>Review List of Recorded Plate Number(s)</button>
            </a>
        </div>
        <div class="grid-item admin-control-panel">
            <h2>Admin Control Panel</h2>
            <div>
                <br>
                <label for="location">Location:</label>
                <select id="location" name="location" onchange="setSelectedLocationId(this)">
                    <?php
                    $sql = "SELECT * FROM locations";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($row['id'] == $selected_location_id) ? 'selected' : '';
                            echo "<option value='{$row['id']}' {$selected}>{$row['name']}</option>";
                        }
                    } else {
                        echo "<option value=''>N/A</option>";
                    }
                    ?>
                </select>

                <ul id="license-plate-list"></ul>

                
                <h3><b>Helmet Detection</b></h3>
                <?php
                getViolatorsCount($conn);
                ?>
                <a href="violators-list.php">
                    <button>List of Violator(s)</button>
                </a>
                <br>
                <h3><b>Vehicle Count</b></h3>
                <ul>
                    <?php
                    $sql = "SELECT * FROM vehicles WHERE locations_id = $selected_location_id";
                    $result = $conn->query($sql);

                    $car = 0;
                    $motorcycle = 0;
                    $pedicab = 0;
                    $truck = 0;
                    $bus = 0;
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $type = strtolower($row['type']);
                    
                            if (strpos($type, 'car') !== false) {
                                $car++;
                            } elseif (strpos($type, 'pedicab') !== false) {
                                $pedicab++;
                            } elseif (strpos($type, 'motorcycle') !== false) {
                                $motorcycle++;
                            } elseif (strpos($type, 'truck') !== false) {
                                $truck++;
                            } elseif (strpos($type, 'bus') !== false) {
                                $bus++;
                            }
                        }            
                    } else {
                        echo "<li>No vehicles found.</li>";
                    }

                    if ($car > 0)
                        echo "<li>Car: {$car}</li>";
                    if ($pedicab > 0)
                        echo "<li>Pedicab: {$pedicab}</li>";
                    if ($motorcycle > 0)
                        echo "<li>Motorcycle: {$motorcycle}</li>";
                    if ($truck > 0)
                        echo "<li>Truck: {$truck}</li>";
                    if ($bus > 0)
                        echo "<li>Bus: {$bus}</li>";
                    ?>
                </ul>
                <br>
            </div>
        </div>
    </div>
</body>

</html>
