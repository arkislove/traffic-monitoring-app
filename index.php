<!DOCTYPE html>

<?php
include('config/constants.php');
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Monitoring App</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    // Create connection
    $conn = new mysqli(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
    ?>
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
            <div class="license-plate-list">
                <ul>
                    <?php
                    $sql = "SELECT * FROM `violators`";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {
                            $violatorId = $row['vehicles_id'];
                            $sql2 = "SELECT * FROM `vehicles` WHERE `id` = $violatorId";
                            $result2 = $conn->query($sql2);

                            if ($result2->num_rows > 0) {
                                while ($vehicleRow = $result2->fetch_assoc()) {
                                    echo "<li class='red'> Violator #" . $violatorId . " [" . $vehicleRow['plate_number'] . "] </li>";
                                }

                            }
                        }
                    } else {
                        echo "No license plates found.";
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="grid-item admin-control-panel">
            <h2>Admin Control Panel</h2>
            <form>
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
                <label for="helmet-detection">Helmet Detection:</label>
                <p>Total Violators: 30</p>
                <p>Total Non-Violators: 221</p>
                <br>
                <label for="vehicle-count">Vehicle Count:</label>
                <ul>
                    <?php
                    $sql = "SELECT * FROM vehicles";
                    $result = $conn->query($sql);

                    $car = 0;
                    $truck = 0;
                    $motorcycle = 0;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            match ($row['vehicle_type']) {
                                'car' => $car += 1,
                                'truck' => $truck += 1,
                                'motorcycle' => $motorcycle += 1,
                                default => null,
                            };
                        }
                    } else {
                        echo "<li>No vehicles found.</li>";
                    }

                    echo "<li>Car: {$car}";
                    echo "<li>Truck: {$truck}";
                    echo "<li>Motorcycle: {$motorcycle}";
                    ?>
                </ul>
                <br>

            </form>
            <a href="<?php echo SITEURL; ?>print-violators.php">
                <button>Print Violator(s)</button>
            </a>
        </div>
    </div>
</body>

</html>