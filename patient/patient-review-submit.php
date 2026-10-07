<?php
include '../connection.php'; // DB connection

session_start();
$patient_id = $_SESSION['patient_id'] ?? 0; // patient id session theke

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $review_for = mysqli_real_escape_string($conn, $_POST['review_for']); // doctor or website
    $doctor_id = !empty($_POST['doctor_id']) ? (int)$_POST['doctor_id'] : NULL;
    $rating = (int)$_POST['rating'];
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    // Insert review into `review` table
    $insert_query = "INSERT INTO review (patient_id, doctor_id, review_for, rating, review_text) 
                     VALUES ('$patient_id', " . ($doctor_id ? "'$doctor_id'" : "NULL") . ", '$review_for', '$rating', '$review_text')";
    
    if (mysqli_query($conn, $insert_query)) {
        
        // Now update average rating and total reviews
        if ($review_for == 'doctor' && $doctor_id) {
            // For Doctor
            $count_query = "SELECT COUNT(*) as total, AVG(rating) as average FROM review WHERE review_for='doctor' AND doctor_id='$doctor_id'";
            $count_result = mysqli_query($conn, $count_query);
            $count_row = mysqli_fetch_assoc($count_result);

            $total_reviews = $count_row['total'] ?? 0;
            $average_rating = round($count_row['average'], 2);

            // Update Doctor Table
            $update_doctor = "UPDATE doctor SET 
                                rating = '$average_rating', 
                                total_reviews = '$total_reviews' 
                              WHERE doctor_id = '$doctor_id'";
            mysqli_query($conn, $update_doctor);

        } elseif ($review_for == 'website') {
            // For Website (assuming admin id = 1)
            $admin_id = 1; // if fixed admin id

            $count_query = "SELECT COUNT(*) as total, AVG(rating) as average FROM review WHERE review_for='website'";
            $count_result = mysqli_query($conn, $count_query);
            $count_row = mysqli_fetch_assoc($count_result);

            $total_reviews = $count_row['total'] ?? 0;
            $average_rating = round($count_row['average'], 2);

            // Update Admin Table
            $update_admin = "UPDATE admin SET 
                                rating = '$average_rating', 
                                total_reviews = '$total_reviews' 
                              WHERE admin_id = '$admin_id'";
            mysqli_query($conn, $update_admin);
        }

        echo "<script>alert('Review Submitted Successfully!'); window.location.href='patient-review.php';</script>";
    } else {
        echo "<script>alert('Failed to submit review.'); window.history.back();</script>";
    }
} else {
    echo "Invalid Request!";
}
?>
