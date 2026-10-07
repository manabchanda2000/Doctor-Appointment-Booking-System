<?php
include '../connection.php';
session_start();

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Collect inputs
$search = trim($_GET['search'] ?? '');
$review_for = $_GET['review_for'] ?? '';
$rating = $_GET['rating'] ?? '';
$likes = $_GET['likes'] ?? '';
$date_sort = $_GET['date_sort'] ?? 'latest';

// Initialize conditions array
$conditions = [];

// Handle search
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(p.name LIKE '%$search%' 
                      OR d.name LIKE '%$search%')";
}

// Handle review_for filter
if (!empty($review_for)) {
    $review_for = mysqli_real_escape_string($conn, $review_for);
    $conditions[] = "r.review_for = '$review_for'";
}

// Handle rating filter
if (!empty($rating)) {
    $rating = (int) $rating;
    $conditions[] = "r.rating > $rating";
}

// Build WHERE clause
$where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

// Handle sorting order
$order = $date_sort === 'oldest' ? 'ASC' : 'DESC';
$order_clause = isset($order_likes) ? $order_likes . ", r.review_date $order" : "r.review_date $order";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Review</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
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

    /* first section----------------------- */
    .first-sec {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
        gap: 20px;
    }

    .heading-part {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .sf-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .search-bar {
        width: 100%;
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

    .filter {
        display: flex;
        align-items: center;
        /* justify-content: space-between; */
        gap: 20px;
        width: 100%;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .filter-group label {
        font-size: 12px;
    }

    .filter-group select,
    input[type="date"] {
        padding: 10px;
        outline: none;
        border: 1px solid #BFDBFE;
        border-radius: 5px;
    }


    #search-btn,
    #filter-btn,
    #add-btn {
        text-decoration: none;
        background-color: #2563EB;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }

    /* second section------------------- */
    .second-sec {
        margin: 20px 0;
    }

    .review-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        gap: 10px;
    }

    .review-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        background-color: #fdfdfd;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #BFDBFE;
        width: 100%;
    }

    .box-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 20px;
    }

    .box-top .patient {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .patient img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: fill;
    }

    .patient #review-for {
        font-size: 14px;
        color: #4B5563;
    }

    #view {
        color: #2563eb;
        margin-right: 5px;
    }

    #delete {
        color: #dc2626;
        margin-right: 5px;
    }

    #id {
        color: #4B5563;
        font-size: 14px;
        padding-right: 5px;
        border-right: 2px solid #4B5563;
    }

    .box-middle p {
        width: 100%;
        color: #4B5563;
        text-align: justify;
        font-size: 14px;
        line-height: 1.5;
    }

    .box-bottom {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-top: 10px;
    }

    .box-bottom small,
    #ratings {
        color: #4B5563;
        font-size: 12px;
    }

    .box-bottom #like {
        border: none;
        background-color: transparent;
        color: #2563EB;
        font-size: 16px;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <?php include 'admin-header.php'; ?>

    <main>
        <section class="first-sec">
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar" placeholder="Search by Patient, Doctor, id...."
                        value="<?= $_GET['search'] ?? '' ?>">
                    <button type="submit" id="search-btn">Search</button>
                </div>
                <div class="filter">
                    <div class="filter-group">
                        <select name="review_for">
                            <option value="">ALL reviews</option>
                            <option value="doctor" <?= ($_GET['review_for'] ?? '') === 'doctor' ? 'selected' : '' ?>>
                                Doctor</option>
                            <option value="website" <?= ($_GET['review_for'] ?? '') === 'website' ? 'selected' : '' ?>>
                                Website</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="rating">
                            <option value="">All Ratings</option>
                            <option value="1" <?= ($_GET['rating'] ?? '') === '1' ? 'selected' : '' ?>>1+</option>
                            <option value="2" <?= ($_GET['rating'] ?? '') === '2' ? 'selected' : '' ?>>2+</option>
                            <option value="3" <?= ($_GET['rating'] ?? '') === '3' ? 'selected' : '' ?>>3+</option>
                            <option value="4" <?= ($_GET['rating'] ?? '') === '4' ? 'selected' : '' ?>>4+</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="date_sort">
                            <option value="latest" <?= ($_GET['date_sort'] ?? '') === 'latest' ? 'selected' : '' ?>>
                                Latest First</option>
                            <option value="oldest" <?= ($_GET['date_sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>
                                Oldest First</option>
                        </select>
                    </div>
                    <button type="submit" id="filter-btn">Apply Filter</button>
                </div>
            </form>
        </section>

        <section class="second-sec">
            <div class="review-part">
                <?php
                  // Final query
                  $query = "SELECT r.*, p.name AS patient_name, p.profile_img, d.name AS doctor_name 
                  FROM review r
                  LEFT JOIN patient p ON r.patient_id = p.patient_id
                  LEFT JOIN doctor d ON r.doctor_id = d.doctor_id
                  $where_clause
                  ORDER BY $order_clause";

                  $result = mysqli_query($conn, $query);

                  if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                      $reviewId = $row['review_id'];
                      $patientName = $row['patient_name'] ?? 'Anonymous';
                      $doctorName = $row['doctor_name'] ?? 'N/A';
                      $reviewFor = $row['review_for'];
                      $reviewText = $row['review_text'];
                      $rating = $row['rating'];
                      $date = date('F j, Y, h:i A', strtotime($row['review_date']));
                      $reviewTo = $reviewFor === 'doctor' ? "Review to . $doctorName" : "Review to Website";
                      $img = $row['profile_img'];
                ?>

                <article class="review-box">
                    <div class="box-top">
                        <span class="patient">
                            <img src="../upload/patient/<?= $img?>" alt="man">
                            <strong><?= htmlspecialchars($patientName) ?> </strong>
                            <span id="review-for">|| <?= $reviewTo ?></span>
                        </span>
                        <span class="action-btn">
                            <a href="admin-deleteReview.php?id=<?= $reviewId ?>" id="delete"
                                onclick="return confirm('Are you sure to delete this review?');"><i
                                    class="fa-solid fa-trash" title="delete"></i></a>
                        </span>
                    </div>
                    <div class="box-middle">
                        <p><?= nl2br(htmlspecialchars($reviewText)) ?></p>
                    </div>
                    <div class="box-bottom">
                        <span id="ratings"><?= $rating ?> ⭐</span>
                        <small><?= $date ?></small>
                    </div>
                </article>

                <?php 
          }
        } else {
          echo "<p style='text-align:center;'>No reviews found.</p>";
        }
      ?>
            </div>
        </section>
    </main>

    <?php include 'admin-footer.php'; ?>
</body>

</html>