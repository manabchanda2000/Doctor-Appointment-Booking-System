<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['latitude'], $_POST['longitude'])) {
        $_SESSION['latitude'] = $_POST['latitude'];
        $_SESSION['longitude'] = $_POST['longitude'];
        echo "Location saved in session";
    } else {
        echo "Invalid data";
    }
} else {
    echo "Only POST allowed";
}
?>
