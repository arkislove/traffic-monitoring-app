<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Monitoring App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "traffic";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

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
            <video id=video-license  controls loop>
                <source src=video.webm type=video/webm>
                <source src=video.ogg type=video/ogg>
                <source src=video.mp4 type=video/mp4>
            </video>
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
                    <?php
                        $sql = "SELECT * FROM locations";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
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
