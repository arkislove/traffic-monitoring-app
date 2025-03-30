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
    <link rel="stylesheet" type="text/css" href="styles.css?v=2">
    <style>
        #video-container {
            text-align: center;
            margin: 20px;
        }

        canvas {
            border: 1px solid black;
        }
    </style>
    <script>
        function startStreaming() {
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');
            var video = document.createElement('video');
            var hideButton = document.getElementById('hideButton');

            navigator.mediaDevices.getDisplayMedia({ video: true }).then(function (stream) {
                video.srcObject = stream;
                video.play();
                drawFrame();
            });

            function drawFrame() {
                if (!video.hidden) {
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                }
                requestAnimationFrame(drawFrame);
            }

            hideButton.addEventListener('click', function () {
                video.hidden = !video.hidden;
                hideButton.textContent = video.hidden ? 'Show Video' : 'Hide Video';
            });
        }
    </script>
</head>

<body onload="startStreaming()">
    <div class="grid-container">
        <div class="grid-item license-plate-detection">
            <h2>Live Video Feed</h2>
            <div id="video-container">
                <button id="hideButton">Hide Video</button>
                <canvas id="canvas" width="640" height="480"></canvas>
            </div>
        </div>
        <div class="grid-item license-plate-dashboard">
            <h2>License Plate Dashboard</h2>
            <ul class="license-plate-list">
                <?php
                $sql = "SELECT * FROM `vehicles` LIMIT 50";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($vehicleRow = $result->fetch_assoc()) {
                        if ($vehicleRow['plate_number'] == '') {
                            echo "<li class='red'>" . $vehicleRow['type'] . " - N/A</li>";
                        } else {
                            echo "<li class='green'>" . $vehicleRow['type'] . " - " . $vehicleRow['plate_number'] . "</li>";
                        }
                    }
                } else {
                    echo "No license plates found.";
                }
                ?>
            </ul>
            <a href="plates-list.php">
                <button>List of Recorded Plate Number(s)</button>
            </a>
        </div>
        <div class="grid-item admin-control-panel">
            <h2>Admin Control Panel</h2>
            <div>
                -wip-
                <br>
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
                -wip-
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
                <a href="violators-list.php">
                    <button>List of Violator(s)</button>
                </a>
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
                            match ($row['type']) {
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

                    if ($car > 0)
                        echo "<li>Car: {$car}";
                    if ($pedicab > 0)
                        echo "<li>Pedicab: {$pedicab}";
                    if ($motorcycle > 0)
                        echo "<li>Motorcycle: {$motorcycle}";
                    if ($truck > 0)
                        echo "<li>Truck: {$truck}";
                    ?>
                </ul>
                <br>
            </div>
        </div>
    </div>
</body>

</html>