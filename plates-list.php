<?php
require('config/constants.php');
require('functions.php');
require('fpdf/fpdf.php');
$conn = connectToDB();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Monitoring App</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateForm() {
            var checkboxes = document.getElementsByName('violators[]');
            var isChecked = false;
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    isChecked = true;
                    break;
                }
            }
            if (!isChecked) {
                alert("Please select at least one violator before submitting.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <a href="/">
        <button>Go back to dashboard</button>
    </a>

    <div>
        <?php
        echo "This page lists all violators and you can select which violator to print out";
        ?>
    </div>
    <div>
        <form action="printing.php" method="post" onsubmit="return validateForm()">
            <?php
            connectToDB();

            $data = array();

            $sql = "SELECT * FROM `vehicles`";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border=1>";
                echo "<tr>";
                echo "<th>Select</th>";
                echo "<th>Vehicle ID</th>";
                echo "<th>Vehicle Type</th>";
                echo "<th>Plate Number</th>";
                echo "<th>Location</th>";
                echo "<th>Time</th>";
                echo "</tr>";

                if ($result->num_rows > 0) {
                    while ($vehicleRow = $result->fetch_assoc()) {
                        $locationId = $vehicleRow['locations_id'];
                        $sql2 = "SELECT * FROM `locations` WHERE `id` = $locationId";
                        $result2 = $conn->query($sql2);

                        if ($result2->num_rows > 0) {
                            while ($locationRow = $result2->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='violators[]' value='" . $vehicleRow['id'] . "'></td>";
                                echo "<td title='Vehicle ID: " . $vehicleRow['id'] . "'>" . $vehicleRow['id'] . "</td>";
                                echo "<td title='Plate Number: " . $vehicleRow['plate_number'] . "'>" . $vehicleRow['plate_number'] . "</td>";
                                echo "<td title='Full Address: " . $locationRow['full_address'] . "'>" . $locationRow['name'] . "</td>";

                                $created_at = calculateAndPrintTimeDifference($vehicleRow['created_at']);

                                echo "<td title='Created at: " . $vehicleRow['created_at'] . "'>" . $created_at . "</td>";
                                echo "</tr>";
                            }
                        }
                    }
                }

                echo "</table>";
            } else {
                echo "No license plates found.";
            }
            ?>
        </form>
    </div>

</body>

</html>