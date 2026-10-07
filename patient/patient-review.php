<?php
session_start();
include '../connection.php';

$patient_id = $_SESSION['patient_id'] ?? null;

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.php");
    exit();
}
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'ASC' : 'DESC';

// Build WHERE clause
$where = "r.patient_id = '$patient_id'";
if (!empty($search)) {
    $where .= " AND (
        d.name LIKE '%$search%'
    )";
}

// Count doctor reviews
$count_doctor_q = "SELECT COUNT(*) as total FROM review WHERE patient_id = '$patient_id' AND review_for = 'doctor'";
$doctor_review = mysqli_fetch_assoc(mysqli_query($conn, $count_doctor_q))['total'] ?? 0;

// Count website reviews
$count_website_q = "SELECT COUNT(*) as total FROM review WHERE patient_id = '$patient_id' AND review_for = 'website'";
$website_review = mysqli_fetch_assoc(mysqli_query($conn, $count_website_q))['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews & Ratings</title>
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

    /* first section---------------- */
    .first-sec {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 20px;
        flex-wrap: wrap;
    }

    .count-sec {
        flex: 1;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;

    }

    .count-box {
        flex: 0 0 200px;
        background: linear-gradient(135deg, #EFF6FF -4%, #DBEAFE 100%);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        border-radius: 10px;
        padding: 20px;
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

    #add-ques {
        text-decoration: none;
        background-color: #3B82F6;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
    }

    /* second section-------------------------- */
    .second-sec {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        margin-top: 20px;
    }

    .top-part {
        padding: 20px;
    }

    .top-part h2 {
        font-size: 20px;
    }

    .bottom-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 0 20px;
        width: 100%;
    }

    .review-box {
        background: #fdfdfd;
        border: 1px solid #BFDBFE;
        border-left: 5px solid #1976d2;
        width: 100%;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        transition: all .3s ease;
    }

    .review-box:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
    }

    .review-box img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: fill;
    }

    .review-data {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;

    }

    .review-data h3 {
        font-size: 16px;
    }

    .review-data p {
        font-size: 14px;
        color: #374151;
    }

    .rating {
        color: #FACC15;
    }

    /* modal box------------------------ */
    .modal {
        display: none;
        position: fixed;
        z-index: 100;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background: #fff;
        margin: auto;
        padding: 30px;
        border-radius: 12px;
        max-width: 500px;
        position: relative;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: zoomIn 0.3s ease;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.7);
        }

        to {
            transform: scale(1);
        }
    }

    .close {
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 24px;
        color: #333;
        cursor: pointer;
    }

    .modal-content h2 {
        margin-bottom: 20px;
        color: #0d47a1;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .radio-group {
        display: flex;
        gap: 15px;
    }

    .rating-stars span {
        font-size: 24px;
        color: #bbb;
        cursor: pointer;
        transition: 0.3s;
    }

    .rating-stars span.active {
        color: #ffc107;
    }

    .review-btn {
        background-color: #0d47a1;
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: 0.2s;
    }

    .review-btn:hover {
        background-color: #08306b;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .action-button #action-btn a {
        color: #1B9AF5;
        margin-right: 10px;
        font-size: 16px;
        text-decoration: none;
    }

    .action-button span {
        color: #374151;
        font-size: 12px;
    }

    /* Responsive adjustments */
    /* @media (max-width: 768px) {
            body {
                margin-left: 0;
                padding-top: 100px;
            }

            .container {
                flex-direction: column;
                width: 90%;
                margin: 0 auto;
            }
        } */
    </style>

</head>

<body>
    <?php include 'patient-header.php'; ?>
    <main>
        <section class="first-sec">
            <div class="count-sec">
                <div class="count-box">
                    <h3><?= $doctor_review ?></h3>
                    <p>Reviews to Doctor</p>
                </div>
                <div class="count-box">
                    <h3><?= $website_review ?></h3>
                    <p>Reviews to Website</p>
                </div>
            </div>

            <!-- Filter Form -->
            <form method="GET" class="sf-part">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" id="search-bar" placeholder="Search by Doctor....."
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
                <a href="#" id="add-ques" onclick="openModal()">Add Review</a>
            </form>
        </section>


        <section class="second-sec">
            <div class="top-part">
                <h2>My Submitted Reviews</h2>
            </div>
            <div class="bottom-part">
                <?php
                    $query = "SELECT r.*, d.name AS doctor_name, d.specialization, d.profile_img 
                            FROM review r 
                            JOIN doctor d ON r.doctor_id = d.doctor_id 
                            WHERE $where 
                            ORDER BY r.review_date $sort";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $doctor_img = !empty($row['profile_img']) ? $row['profile_img'] : 'user.jpg';
                            $formatted_date = date("F j, Y, h:i A", strtotime($row['review_date']));
                            $rating = (int)$row['rating'];
                            $half_star = ($row['rating'] - $rating) >= 0.5;

                            echo '<div class="review-box">
                                    <img src="../upload/doctor/' . $doctor_img . '" alt="doc">
                                    <div class="review-data">
                                        <h3>' . htmlspecialchars($row['doctor_name']) . '</h3>
                                        <p>' . htmlspecialchars($row['specialization']) . '</p>
                                        <div class="rating">';
                            
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<i class="fas fa-star"></i>';
                                } elseif ($i == $rating + 1 && $half_star) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }

                            echo '</div>
                                        <p>' . nl2br(htmlspecialchars($row['review_text'])) . '</p>
                                    </div>
                                    <div class="action-button">
                                        <span id="action-btn">
                                            <a href="patient-deleteReview.php?review_id=' . $row['review_id'] . '" title="Delete" onclick="return confirm(\'Are you sure?\')"><i class="fa-solid fa-trash"></i></a>
                                        </span>
                                        <span>' . $formatted_date . '</span>
                                    </div>
                                </div>';
                        }
                    } else {
                        echo "<p style='text-align:center; padding: 20px;'>You haven't posted any reviews yet.</p>";
                    }
                    ?>
            </div>

        </section>
        <!-- Modal Overlay -->
        <div id="reviewModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Add Your Review</h2>
                <form id="reviewForm" method="POST" action="patient-review-submit.php">
                    <div class="form-group">
                        <label>Review For:</label>
                        <div class="radio-group">
                            <label><input type="radio" name="review_for" value="doctor" required> Doctor</label>
                            <label><input type="radio" name="review_for" value="website" required> Website</label>
                        </div>
                    </div>

                    <div class="form-group" id="doctorSelect">
                        <label for="doctor">Select Doctor:</label>
                        <select name="doctor_id">
                            <option value="">-- Select Doctor --</option>
                            <?php
                    $doc_q = mysqli_query($conn, "SELECT doctor_id, name FROM doctor");
                    while ($doc = mysqli_fetch_assoc($doc_q)) {
                        echo "<option value='{$doc['doctor_id']}'>{$doc['name']}</option>";
                    }
                    ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Rating:</label>
                        <div class="rating-stars" id="ratingStars">
                            <span data-value="1">☆</span>
                            <span data-value="2">☆</span>
                            <span data-value="3">☆</span>
                            <span data-value="4">☆</span>
                            <span data-value="5">☆</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" required>
                    </div>

                    <div class="form-group">
                        <textarea name="review_text" rows="4" placeholder="Write your review here..."
                            required></textarea>
                    </div>

                    <button type="submit" class="review-btn">Submit Review</button>
                </form>
            </div>
        </div>
    </main>
    <?php include 'patient-footer.php'; ?>

    <script>
    function openModal() {
        document.getElementById("reviewModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("reviewModal").style.display = "none";
    }
    window.onclick = function(event) {
        const modal = document.getElementById("reviewModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
    // Rating Stars Logic
    const stars = document.querySelectorAll('#ratingStars span');
    const ratingInput = document.getElementById('ratingValue');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const val = star.getAttribute('data-value');
            ratingInput.value = val;

            stars.forEach(s => {
                s.classList.remove('active');
            });

            for (let i = 0; i < val; i++) {
                stars[i].classList.add('active');
            }
        });
    });
    </script>

</body>

</html>