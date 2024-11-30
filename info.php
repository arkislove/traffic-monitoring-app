<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="grid-container">
        <div class="grid-item main-detection">
            <h2>Main Detection Stream (1080P)</h2>
            <!-- Add your video stream here -->
        </div>
        <div class="grid-item license-plate-detection">
            <h2>License Plate Detection Stream (4K)</h2>
            <!-- Add your video stream here -->
        </div>
        <div class="grid-item license-plate-dashboard">
            <h2>License Plate Dashboard</h2>
            <ul>
                <?php
                $licensePlates = [
                    ['plate' => '7WTJ930', 'time' => '03/11/2024 3:30 PM', 'status' => 'valid'],
                    ['plate' => '6JVG393', 'time' => '03/11/2024 5:30 PM', 'status' => 'invalid'],
                    ['plate' => '6JVG393', 'time' => '03/11/2024 6:30 PM', 'status' => 'valid']
                ];
                foreach ($licensePlates as $plate) {
                    $statusClass = $plate['status'] === 'valid' ? 'green' : 'red';
                    echo "<li class='$statusClass'>{$plate['plate']} detected on {$plate['time']}</li>";
                }
                ?>
            </ul>
        </div>
        <div class="grid-item admin-control-panel">
            <h2>Admin Control Panel</h2>
            <form>
                <label for="location">Location:</label>
                <select id="location" name="location">
                    <option value="entrance">Entrance and Exit of Robinsons Dumaguete</option>
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
                    <li>SUV: 12</li>
                    <li>Sedan: 20</li>
                    <li>Motorcycle: 35</li>
                    <li>PUV: 135</li>
                    <li>Truck: 100</li>
                </ul>
                <br>
                <button type="button">Start Detection</button>
                <button type="button">Stop Detection</button>
            </form>
        </div>
    </div>
</body>
</html>
