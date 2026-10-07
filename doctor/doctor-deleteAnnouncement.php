<?php
// Include database connection file
include '../connection.php';

// Check if announcement_id is passed
if (isset($_GET['announcement_id'])) {
    $announcement_id = intval($_GET['announcement_id']);

    // Prepare the delete query
    $delete_query = "DELETE FROM announcement WHERE announcement_id = ?";
    
    // Use prepared statements to avoid SQL injection
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param('i', $announcement_id);

        if ($stmt->execute()) {
            // Redirect back with success message
            header("Location: doctor-announcements.php");
            exit;
        } else {
            // Handle query error
            echo "Error deleting announcement.";
        }
    } else {
        echo "Error preparing the query.";
    }
} else {
    echo "Invalid request.";
}
?>
