<?php
include '../connection.php';
session_start();

$doctor_id = $_SESSION['doctor_id'] ?? 0; // logged-in doctor

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

// Build WHERE clause
$where = "r.doctor_id = '$doctor_id' AND r.review_for = 'doctor'";
if (!empty($search)) {
    $where .= " AND (
        p.name LIKE '%$search%'
    )";
}


// Fetch doctor's rating and total reviews
$doctor_query = "SELECT rating, total_reviews FROM doctor WHERE doctor_id = '$doctor_id'";
$doctor_result = mysqli_query($conn, $doctor_query);
$doctor = mysqli_fetch_assoc($doctor_result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews & Ratings</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    main {
        background: linear-gradient(135deg, #f2f8fc, #f0f8ff);
        padding: 80px 20px 20px 280px;
    }

    /* first section------------------------- */
    .first-sec {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 20px;
        flex-wrap: wrap;

    }

    .sf-part {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .search-bar {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #fdfdfd;
        padding: 10px;
        border-radius: 5px;
        font-size: 14px;
        border: 1px solid #BFDBFE;
    }

    .search-bar #search-bar {
        flex: 1;
        padding: 5px;
        outline: none;
        border: none;
        background-color: transparent;
    }

    .filter-group select {
        padding: 10px;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
        outline: none;
    }

    #search-btn,
    #filter-btn {
        background-color: #3B82F6;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
    }


    .rating-summary h2 {
        font-size: 60px;
        color: #f39c12;
    }

    .rating-summary p {
        font-size: 18px;
        color: #2e4450;
    }

    /* second section------------------- */

    .reviews-container {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin: 20px 0;
        flex-wrap: wrap;
        padding: 0 20px;
    }

    .review-card {
        background: #fdfdfd;
        border: 1px solid #BFDBFE;
        border-left: 5px solid #1976d2;
        border-radius: 12px;
        padding: 20px;
        transition: 0.3s;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
    }

    .review-card:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .review-card img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: fill;
    }

    .review-data {
        width: 100%;
    }

    .data-top {
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .data-top h3 {
        font-size: 16px;
    }

    #date {
        font-size: 12px;
        color: #6B7280;
        float: right;
        margin-top: 8px;
    }

    .data-bottom p {
        font-size: 14px;
        color: #6B7280;
        text-align: justify;
        line-height: 1.4;
        margin: 5px 0;
    }

    .heading {
        padding: 20px;
        font-size: 20px;
    }
    </style>
</head>

<body>
    <?php include 'doctor-header.php'?>

    <main>
        <section class="first-sec">
            <div class="count-part">
                <div class="rating-summary">
                    <h2><?= htmlspecialchars($doctor['rating']) ?> ⭐</h2>
                    <p>Based on <?= (int)$doctor['total_reviews'] ?> reviews</p>
                </div>
            </div>

            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar" placeholder="Search by Patient....."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter-group">
                    <select name="sort">
                        <option value="latest"
                            <?= (!isset($_GET['sort']) || $_GET['sort'] !== 'oldest') ? 'selected' : '' ?>>Latest
                        </option>
                        <option value="oldest"
                            <?= (isset($_GET['sort']) && $_GET['sort'] === 'oldest') ? 'selected' : '' ?>>Oldest
                        </option>
                    </select>
                </div>
                <button type="submit" id="filter-btn">Apply Filter</button>
            </form>

        </section>

        <section class="second-sec">
            <h2 class="heading">My Reviews</h2>
            <div class="reviews-container">
                <?php 
                $review_query = "SELECT r.*, p.name AS patient_name, p.profile_img 
                 FROM review r
                 JOIN patient p ON r.patient_id = p.patient_id
                 WHERE  $where
                 ORDER BY r.review_date $sort";

                $review_result = mysqli_query($conn, $review_query);
                ?>
                <?php if (mysqli_num_rows($review_result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($review_result)): ?>
                <div class="review-card">
                    <img src="../upload/patient/<?= htmlspecialchars($row['profile_img']) ?>"
                        alt="<?= htmlspecialchars($row['patient_name']) ?>">
                    <div class="review-data">
                        <div class="data-top">
                            <h3><?= htmlspecialchars($row['patient_name']) ?></h3>

                        </div>
                        <div class="data-bottom">
                            <div class="review-stars">
                                <?php
                                $fullStars = floor($row['rating']);
                                $halfStar = ($row['rating'] - $fullStars >= 0.5);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $fullStars) echo '⭐';
                                    elseif ($halfStar && $i == $fullStars + 1) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                    else echo '<i class="fa-solid fa-star"></i>';
                                }
                                ?>
                            </div>
                            <p><?= htmlspecialchars($row['review_text']) ?></p>

                        </div>
                        <span id="date"><?= date("F j, Y, h:i A", strtotime($row['review_date']))?></span>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p style="text-align:center;">No reviews yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include 'doctor-footer.php'?>

</body>

</html>