<?php
include '../connection.php';

session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'] ?? 0; // Doctor login session ID

$conditions = ["(posted_for IN ('doctor', 'all') OR doctor_id = '$doctor_id')"];

// Category filter
if (!empty($_GET['category']) && $_GET['category'] !== 'All Categories') {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $conditions[] = "category = '$category'";
}

// Posted By filter
if (!empty($_GET['posted_by']) && $_GET['posted_by'] !== 'All Announcement') {
    $posted_by = mysqli_real_escape_string($conn, $_GET['posted_by']);
    if ($posted_by === 'My') {
        $conditions[] = "doctor_id = '$doctor_id'";
    } else {
        $conditions[] = "posted_by = '" . strtolower($posted_by) . "'";
    }
}

// Build WHERE clause
$where_clause = implode(" AND ", $conditions);

// Sorting
$order_by = "ORDER BY created_at DESC";
if (!empty($_GET['sort']) && $_GET['sort'] === 'oldest') {
    $order_by = "ORDER BY created_at ASC";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements & Updates</title>
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

    /* second section--------------------- */

    .sf-part {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
        margin-bottom: 20px;

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

    #add-btn {
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 5px;
        background-color: #3B82F6;
        color: white;
    }

    /* third section--------------- */

    .notice-sec {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        margin: 20px 0;
    }

    .artical-box {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        border-radius: 8px;
        background: #fdfdfd;
        border: 1px solid #BFDBFE;
        border-left: 5px solid #1976d2;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .artical-top #notice-for {
        color: #991B1B;
        background-color: #FEE2E2;
        border-radius: 5px;
        padding: 5px;
    }

    .artical-top span {
        color: #6B7280;
        margin-right: 5px;
        font-size: 12px;
    }

    .artical-middle {
        padding-right: 30px;
        margin-bottom: 15px;
    }

    .artical-middle h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .artical-middle p {
        font-size: 15px;
        color: #4B5563;
    }

    .artical-buttom a {
        text-decoration: none;
        color: #007BFF;
        margin-right: 5px;
        transition: all .3s ease;
    }

    .artical-buttom a:hover {
        color: #003268;
    }

    /* modal box------------------ */

    .modal {
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 10px;
        width: 40%;
        position: relative;
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
        color: #aaa;
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 8px 10px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .submit-btn {
        background-color: #007BFF;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: .3s ease;
    }

    .submit-btn:hover {
        background-color: rgb(1, 89, 184);
    }
    </style>
</head>

<body>
    <?php include 'doctor-header.php'; ?>
    <main>
        <section class="second-sec">
            <form method="GET" class="sf-part">

                <div class="filter-group">
                    <select name="category">
                        <option value="" <?= empty($_GET['category']) ? 'selected' : '' ?>>All Categories</option>
                        <option value="Emergency Alerts"
                            <?= (isset($_GET['category']) && $_GET['category'] == 'Emergency Alerts') ? 'selected' : '' ?>>
                            Emergency Alerts</option>
                        <option value="Healthcare Updates"
                            <?= (isset($_GET['category']) && $_GET['category'] == 'Healthcare Updates') ? 'selected' : '' ?>>
                            Healthcare Updates</option>
                        <option value="Maintenance"
                            <?= (isset($_GET['category']) && $_GET['category'] == 'Maintenance') ? 'selected' : '' ?>>
                            Maintenance</option>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="posted_by">
                        <option value="" <?= empty($_GET['posted_by']) ? 'selected' : '' ?>>All Announcement</option>
                        <option value="Admin"
                            <?= (isset($_GET['posted_by']) && $_GET['posted_by'] == 'Admin') ? 'selected' : '' ?>>Admin
                        </option>
                        <option value="Doctor"
                            <?= (isset($_GET['posted_by']) && $_GET['posted_by'] == 'Doctor') ? 'selected' : '' ?>>
                            Doctor</option>
                        <option value="My"
                            <?= (isset($_GET['posted_by']) && $_GET['posted_by'] == 'My') ? 'selected' : '' ?>>My
                        </option>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="sort">
                        <option value="latest"
                            <?= (!isset($_GET['sort']) || $_GET['sort'] !== 'oldest') ? 'selected' : '' ?>>Latest First
                        </option>
                        <option value="oldest"
                            <?= (isset($_GET['sort']) && $_GET['sort'] === 'oldest') ? 'selected' : '' ?>>Oldest First
                        </option>
                    </select>
                </div>

                <button type="submit" id="filter-btn">Apply Filter</button>

                <div class="add">
                    <a href="#" id="add-btn" onclick="openAnnouncementModal()">Add Announcement</a>
                </div>

            </form>

        </section>

        <section class="third-sec">
            <div class="notice-sec">
                <?php
        // Fetch announcements for doctors
        $announcement_query = "SELECT * FROM announcement WHERE $where_clause $order_by";
        $announcement_result = mysqli_query($conn, $announcement_query);
        
        if (mysqli_num_rows($announcement_result) > 0) {
            while ($announcement = mysqli_fetch_assoc($announcement_result)) {
        ?>
                <article class="artical-box">
                    <div class="artical-top">
                        <span id="notice-for"><?= htmlspecialchars($announcement['category']) ?></span>
                        <span><?= date('M d, Y, h:i A', strtotime($announcement['created_at'])) ?></span>
                        <span>•</span>
                        <span>Posted by <?= ucfirst($announcement['posted_by']) ?></span>
                    </div>
                    <div class="artical-middle">
                        <h3><?= htmlspecialchars($announcement['title']) ?></h3>
                        <p><?= substr(strip_tags($announcement['description']), 0, 250) ?>...</p>
                    </div>
                    <div class="artical-buttom">
                        <a href="#">Read More</a>
                        <?php 
                        // Show delete option only if the notice is for doctors
                        if ($announcement['doctor_id'] == $doctor_id) { 
                        ?>
                        <a href="doctor-deleteAnnouncement.php?announcement_id=<?= $announcement['announcement_id'] ?>"
                            style="color: red;"
                            onclick="return confirm('Are you sure you want to delete this announcement?');">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                        <?php 
                        } 
                        ?>
                    </div>
                </article>
                <?php
            }
        } else {
            echo "<p>No announcements available.</p>";
        }
        ?>
            </div>
        </section>


        <div id="announcementModal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" onclick="closeAnnouncementModal()">&times;</span>
                <h2 style=" margin-bottom: 10px;">Add Announcement</h2>
                <form id="announcementForm" method="POST" action="doctor-addAnnouncement-submit.php"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="category">Category <span style="color:red">*</span></label>
                        <select id="category" name="category">
                            <option>All Categories</option>
                            <option>Emergency Alerts</option>
                            <option>Healthcare Updates</option>
                            <option>Maintenance</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Title <span style="color:red">*</span></label>
                        <input type="text" id="title" name="title" placeholder="Enter Title" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description <span style="color:red">*</span></label>
                        <textarea id="description" name="description" rows="4" placeholder="Enter Description"
                            required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="file">Attach File (Optional)</label>
                        <input type="file" id="file" name="file">
                    </div>

                    <div class="form-group">
                        <label for="image">Upload Image (Optional)</label>
                        <input type="file" id="image" name="image">
                    </div>

                    <div class="form-group">
                        <label for="posted_for">Posted For <span style="color:red">*</span></label>
                        <select id="posted_for" name="posted_for" required>
                            <option value="">Select</option>
                            <option value="Patient">Patient</option>
                            <option value="doctor">Doctor</option>
                            <option value="all">All</option>
                        </select>
                    </div>

                    <button type="submit" class="submit-btn">Post Announcement</button>
                </form>
            </div>
        </div>
    </main>
    <script>
    function openAnnouncementModal() {
        document.getElementById('announcementModal').style.display = 'block';
    }

    function closeAnnouncementModal() {
        document.getElementById('announcementModal').style.display = 'none';
    }

    document.getElementById('add-btn').addEventListener('click', function(e) {
        e.preventDefault();
        openAnnouncementModal();
    });

    window.onclick = function(event) {
        const modal = document.getElementById("announcementModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
    </script>
    <?php include 'doctor-footer.php'; ?>
</body>

</html>