<?php
include '../connection.php';
session_start();

if (isset($_GET['review_id'])) {
    $review_id = mysqli_real_escape_string($conn, $_GET['review_id']);

    // Get review info
    $getReview = "SELECT * FROM review WHERE review_id = '$review_id'";
    $reviewResult = mysqli_query($conn, $getReview);

    if (mysqli_num_rows($reviewResult) > 0) {
        $review = mysqli_fetch_assoc($reviewResult);

        $doctor_id = $review['doctor_id'];
        $review_for = $review['review_for'];

        // Delete the review
        $deleteQuery = "DELETE FROM review WHERE review_id = '$review_id'";
        if (mysqli_query($conn, $deleteQuery)) {

            // Recalculate rating and review count
            if ($review_for === 'doctor' && $doctor_id !== NULL) {
                $countQuery = "SELECT COUNT(*) AS total_reviews, AVG(rating) AS avg_rating 
                               FROM review 
                               WHERE doctor_id = '$doctor_id' AND review_for = 'doctor'";
                $countResult = mysqli_query($conn, $countQuery);
                $data = mysqli_fetch_assoc($countResult);

                $new_total = $data['total_reviews'] ?? 0;
                $new_avg = round($data['avg_rating'] ?? 0, 2);

                $updateDoctor = "UPDATE doctor SET 
                                    rating = '$new_avg',
                                    total_reviews = '$new_total' 
                                 WHERE doctor_id = '$doctor_id'";
                mysqli_query($conn, $updateDoctor);

            } elseif ($review_for === 'website') {
                $admin_id = 1; // consistent with insert page

                $countQuery = "SELECT COUNT(*) AS total_reviews, AVG(rating) AS avg_rating 
                               FROM review 
                               WHERE review_for = 'website'";
                $countResult = mysqli_query($conn, $countQuery);
                $data = mysqli_fetch_assoc($countResult);

                $new_total = $data['total_reviews'] ?? 0;
                $new_avg = round($data['avg_rating'] ?? 0, 2);

                $updateAdmin = "UPDATE admin SET 
                                    rating = '$new_avg',
                                    total_reviews = '$new_total' 
                                 WHERE admin_id = '$admin_id'";
                mysqli_query($conn, $updateAdmin);
            }

            $_SESSION['success'] = "Review deleted successfully.";
            header("Location: patient-review.php");
            exit();

        } else {
            $_SESSION['error'] = "Failed to delete review.";
            header("Location: patient-review.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "Review not found.";
        header("Location: patient-review.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid Request.";
    header("Location: patient-review.php");
    exit();
}
?>
