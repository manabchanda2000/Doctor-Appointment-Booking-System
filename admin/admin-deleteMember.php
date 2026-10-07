<?php
include '../connection.php';

// Retrieve team member ID
$team_id = $_GET['id'] ?? null;

if (!$team_id) {
    die("Team Member ID is required to delete.");
}

// Prepare delete query
$query = "DELETE FROM our_team WHERE team_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $team_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<script>alert('Team member deleted successfully.'); window.location.href='admin-manageTeam.php';</script>";
} else {
    echo "<script>alert('Failed to delete team member. It may not exist.'); window.location.href='team.php';</script>";
}

$stmt->close();
$conn->close();
exit();
?>
