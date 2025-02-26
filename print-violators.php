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
    <a href="<?php echo SITEURL; ?>">
        <button>Go back to dashboard</button>
    </a>

    <div>
        <?php
        echo "this page lists all violators and you can select which violator to print out";
        ?>
    </div>
    <div>
        <?php
        // Create connection
        $conn = new mysqli(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $data = array();

        $sql = "SELECT * FROM `violators`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table border=1>";
            echo "<tr>";
            echo "<th>Vehicle ID</th>";
            echo "<th>Vehicle Type</th>";
            echo "<th>Plate Number</th>";
            echo "<th>Location</th>";
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                $violatorId = $row['vehicles_id'];
                $sql2 = "SELECT * FROM `vehicles` WHERE `id` = $violatorId";
                $result2 = $conn->query($sql2);

                if ($result2->num_rows > 0) {
                    while ($vehicleRow = $result2->fetch_assoc()) {
                        $locationId = $vehicleRow['locations_id'];
                        $sql3 = "SELECT * FROM `locations` WHERE `id` = $locationId";
                        $result3 = $conn->query($sql3);

                        if ($result3->num_rows > 0) {
                            while ($locationRow = $result3->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td title='Vehicle ID: " . $violatorId . "'>" . $violatorId . "</td>";
                                echo "<td title='Model: " . $vehicleRow['model'] . "'>" . $vehicleRow['vehicle_type'] . "</td>";
                                echo "<td title='Plate Number: " . $vehicleRow['plate_number'] . "'>" . $vehicleRow['plate_number'] . "</td>";
                                echo "<td title='Full Address: " . $locationRow['full_address'] . "'>" . $locationRow['name'] . "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                }
            }
            echo "</table>";
        } else {
            echo "No license plates found.";
        }

        ?>
    </div>

</body>

</html>