<!DOCTYPE html>
<?php
require('config/constants.php');
require('functions.php');
$conn = connectToDB();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Monitoring App</title>
    <link rel="stylesheet" type="text/css" href="styles.css?v=1">
</head>

<body>
    <div class="grid-container">
        <div class="grid-item main-detection">
            <h2>Main Detection Stream (1080P)</h2>
            <canvas id=canvas-main></canvas>
            <video id=video-main controls loop>
                <source src=video.webm type=video/webm>
                <source src=video.ogg type=video/ogg>
                <source src=video.mp4 type=video/mp4>
            </video>
        </div>
        <div class="grid-item license-plate-detection">
            <h2>License Plate Detection Stream (4K)</h2>
            <canvas id=canvas-license></canvas>
            <video id=video-license controls loop>
                <source src=video.webm type=video/webm>
                <source src=video.ogg type=video/ogg>
                <source src=video.mp4 type=video/mp4>
            </video>
        </div>
        <div class="grid-item license-plate-dashboard">
            <h2>License Plate Dashboard</h2>
            <ul class="license-plate-list">
                <?php
                $sql = "SELECT * FROM `vehicles`";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($vehicleRow = $result->fetch_assoc()) {
                        if ($vehicleRow['plate_number'] == '') {
                            echo "<li class='red'>" . $vehicleRow['vehicle_type'] . " - N/A</li>";
                        } else {
                            echo "<li class='green'>" . $vehicleRow['vehicle_type'] . " - " . $vehicleRow['plate_number'] . "</li>";
                        }
                    }
                } else {
                    echo "No license plates found.";
                }
                ?>
            </ul>
        </div>
        <div class="grid-item admin-control-panel">
            <h2>Admin Control Panel</h2>
            <div>
                <label for="location">Location:</label>
                <select id="location" name="location">
                    <?php
                    $sql = "SELECT * FROM locations";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'> {$row['name']}</option>";
                        }
                    } else {
                        echo "<option>N/A</option>";
                    }
                    ?>
                </select>

                <br>
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="2024-11-10">
                <br>
                <label for="time">Time:</label>
                <input type="time" id="time" name="time" value="06:00">
                <br>
                <br>
                <h3><b>Helmet Detection</b></h3>
                <?php
                $sql = "SELECT COUNT(*) AS violatorsCount FROM violators";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo "<p>Total violators: " . $row['violatorsCount'] . "</p>";
                } else {
                    echo "No violators found.";
                }
                ?>
                <p href="<?php echo SITEURL; ?>violators-list.php">
                    <button>List of Violator(s)</button>
                </p>
                <br>
                <h3><b>Vehicle Count</b></h3>
                <ul>
                    <?php
                    $sql = "SELECT * FROM vehicles";
                    $result = $conn->query($sql);

                    $car = 0;
                    $motorcycle = 0;
                    $pedicab = 0;
                    $truck = 0;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            match ($row['vehicle_type']) {
                                'car' => $car++,
                                'pedicab' => $pedicab++,
                                'motorcycle' => $motorcycle++,
                                'truck' => $truck++,
                                default => null,
                            };
                        }
                    } else {
                        echo "<li>No vehicles found.</li>";
                    }

                    echo "<li>Car: {$car}";
                    echo "<li>Pedicab: {$pedicab}";
                    echo "<li>Motorcycle: {$motorcycle}";
                    echo "<li>Truck: {$truck}";
                    ?>
                </ul>
                <br>
            </div>
        </div>
    </div>
</body>

</html>