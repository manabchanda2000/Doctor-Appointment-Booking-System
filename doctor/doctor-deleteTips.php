<?php
// Include database connection
include '../connection.php';

// Check if tip_id is passed as a parameter
if (isset($_GET['tip_id'])) {
    $tip_id = intval($_GET['tip_id']); // Ensure tip_id is an integer
    
    // Prepare the delete query
    $delete_query = "DELETE FROM health_tips WHERE tip_id = ?";
    
    // Use a prepared statement to prevent SQL injection
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param('i', $tip_id);

        if ($stmt->execute()) {
            // Redirect back with success message
            echo "<script>alert('Successfully Deleted!'); window.history.back();</script>";
            exit;
        } else {
            echo "Error: Could not delete the health tip.";
        }
    } else {
        echo "Error: Failed to prepare the delete query.";
    }
} else {
    echo "Invalid request. No tip ID specified.";
}
?>
