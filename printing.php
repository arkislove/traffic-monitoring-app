<?php
require('fpdf/fpdf.php');
require('config/constants.php');
require('functions.php');

$conn = connectToDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['violators'])) {
        $violators = $_POST['violators'];

        class PDF extends FPDF
        {
            function Header()
            {
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(0, 10, 'Traffic Violators Report', 0, 1, 'C');
                $this->Ln(10);
            }

            function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
            }
        }

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        foreach ($violators as $violatorId) {
            $sql = "SELECT * FROM `vehicles` WHERE `id` = $violatorId";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // If your image path comes from the database, for example:
                    $imagePath = $row['image_path'];
                    // Otherwise, set a static path:

                    // Insert the image at x=10, y=current position, with a width of 30.
                    // The height is automatically calculated to maintain the aspect ratio,
                    // but you can specify it as a 4th parameter if needed.
                    $pdf->Image($imagePath, 10, $pdf->GetY(), 30);
                    
                    // Adjust vertical spacing based on the image height (here, 30 plus some margin)
                    $pdf->Ln(35);

                    $locationId = $row['locations_id'];
                    $sql2 = "SELECT * FROM `locations` WHERE `id` = $locationId";
                    $result2 = $conn->query($sql2);

                    if ($result2->num_rows > 0) {
                        while ($locationRow = $result2->fetch_assoc()) {
                            $pdf->Cell(0, 10, 'Vehicle ID: ' . $violatorId, 0, 1);
                            $pdf->Cell(0, 10, 'Vehicle Type: ' . $row['type'], 0, 1);
                            $pdf->Cell(0, 10, 'Plate Number: ' . $row['plate_number'], 0, 1);
                            $pdf->Cell(0, 10, 'Location: ' . $locationRow['name'] . ' (' . $locationRow['full_address'] . ')', 0, 1);
                            $pdf->Ln(10);
                        }
                    }
                }
            }
        }

        $current_time = date("Y-m-d_H-i-s");
        $file_name = "violators_report_$current_time.pdf";
        $pdf->Output('D', $file_name);
    } else {
        echo "No violators selected.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
