<?php
include '../connection.php'; // Include database connection file

if (isset($_GET['id'])) {
    $prescription_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the file path before deletion
    $query = "SELECT prescription FROM prescription WHERE prescription_id = '$prescription_id'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = "../upload/patient/" . $row['prescription'];

        // Delete the file from the server if it exists
        if (!empty($row['prescription']) && file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete the record from the database
        $delete_query = "DELETE FROM prescription WHERE prescription_id = '$prescription_id'";
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>
                    alert('Prescription deleted successfully!');
                    window.location.href = 'doctor-prescription.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Failed to delete prescription from database.');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Prescription not found.');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request.');
            window.history.back();
          </script>";
}
?>
