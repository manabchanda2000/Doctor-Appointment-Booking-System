<?php
include '../connection.php';

// Retrieve review ID from URL parameter
$review_id = $_GET['id'] ?? null;

if (!$review_id) {
    die("Review ID is required to delete the review.");
}

// Prepare delete query
$query = "DELETE FROM review WHERE review_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $review_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<script>alert('Review deleted successfully.'); window.location.href='admin-manageReview.php';</script>";
} else {
    echo "<script>alert('Failed to delete review. It may not exist.'); window.location.href='reviews.php';</script>";
}

$stmt->close();
$conn->close();

exit();
?>
