<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['selected_location_id'])) {
    $_SESSION['selected_location_id'] = $data['selected_location_id'];
    echo "Session updated with location ID: " . $_SESSION['selected_location_id'];
} else {
    echo "No location ID provided.";
}
?>
