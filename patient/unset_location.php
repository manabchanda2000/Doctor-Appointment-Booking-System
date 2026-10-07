<?php
session_start();
unset($_SESSION['latitude']);
unset($_SESSION['longitude']);
echo "Location removed";
?>
