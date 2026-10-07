<?php
session_start();
include '../connection.php';

$patient_id = $_SESSION['patient_id'] ?? null;

if (!$patient_id) {
    header("Location: patient-login.html");
    exit();
}
$conditions = [];
$params = [];
// Category filter
if (!empty($_GET['category'])) {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $conditions[] = "category = '$category'";
}

// Order
$order_by = "ORDER BY created_at DESC";
if (!empty($_GET['order']) && $_GET['order'] === 'asc') {
    $order_by = "ORDER BY created_at ASC";
}

$where_clause = "";
if (count($conditions) > 0) {
    $where_clause = "WHERE " . implode(" AND ", $conditions);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Health Tips</title>
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

    /* first section------------------- */
    .first-sec {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-radius: 10px;
        padding: 15px;
    }

    .sf-part {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 65%;
        flex-wrap: wrap;

    }

    .filter-group label {
        font-size: 12px;
    }

    .filter-group select,
    input[type="date"] {
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

    /* second section-------------------- */
    .second-sec {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin: 20px 0;
    }

    .tips-box {
        flex: 0 0 400px;
        background-color: #fdfdfd;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.13);
    }

    .tips-box img {
        width: 100%;
        aspect-ratio: 3;
        object-fit: fill;
    }

    .box-top {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px;
    }

    .box-top img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: fill;
    }

    .box-top h3 {
        font-size: 15px;
        margin-bottom: 2px;
    }

    .box-top p {
        font-size: 14px;
        color: #6B7280;
    }

    .box-middle {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        padding: 0 15px;
    }

    .box-middle h4 {
        font-size: 16px;
    }

    .box-middle p {
        font-size: 14px;
        color: #6B7280;
        text-align: justify;
        line-height: 1.5;
    }

    .box-buttom {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding: 15px;
    }

    #data {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
    }

    #data data {
        font-size: 12px;
        color: #6B7280;
    }

    #data #back {
        padding: 4px 6px;
        background-color: #c1d6f7;
        color: #1c52a9;
        border-radius: 20px;
        font-size: 14px;
    }



    #readmore {
        text-decoration: none;
        width: 100%;
        background-color: #1B9AF5;
        border: none;
        text-align: center;
        padding: 8px 12px;
        color: white;
        border-radius: 5px;
    }
    </style>
</head>

<body>

    <?php include 'patient-header.php'; ?>
    <main>
        <section class="first-sec">
            <form method="GET" class="sf-part">
                <div class="filter-group">
                    <select name="category">
                        <option value="" <?= empty($_GET['category']) ? 'selected' : '' ?>>All Category</option>
                        <option value="Diet" <?= ($_GET['category'] ?? '') === 'Diet' ? 'selected' : '' ?>>Diet</option>
                        <option value="Skin" <?= ($_GET['category'] ?? '') === 'Skin' ? 'selected' : '' ?>>Skin</option>
                        <option value="Heart" <?= ($_GET['category'] ?? '') === 'Heart' ? 'selected' : '' ?>>Heart
                        </option>
                        <option value="Dental" <?= ($_GET['category'] ?? '') === 'Dental' ? 'selected' : '' ?>>Dental
                        </option>
                        <option value="Diabetes" <?= ($_GET['category'] ?? '') === 'Diabetes' ? 'selected' : '' ?>>
                            Diabetes</option>
                        <option value="Mental Health"
                            <?= ($_GET['category'] ?? '') === 'Mental Health' ? 'selected' : '' ?>>Mental Health
                        </option>
                    </select>
                </div>


                <div class="filter-group">
                    <select name="order">
                        <option value="desc" <?= ($_GET['order'] ?? '') === 'desc' ? 'selected' : '' ?>>Latest First
                        </option>
                        <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Oldest First
                        </option>
                    </select>
                </div>
                <button type="submit" id="filter-btn">Apply Filter</button>
            </form>
        </section>

        <section class="second-sec">
            <?php
    include '../connection.php';

    // Get all tips
    $query = "SELECT t.*, d.name AS doctor_name, d.specialization AS doctor_specialty, d.profile_img AS doctor_image 
              FROM health_tips t 
              JOIN doctor d ON t.doctor_id = d.doctor_id 
              $where_clause $order_by";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
    ?>
            <div class="tips-box">
                <img src="../upload/doctor/<?= htmlspecialchars($row['image']) ?>" alt="img">
                <div class="box-top">
                    <img src="../upload/doctor/<?= htmlspecialchars($row['doctor_image']) ?>" alt="doc">
                    <div id="name">
                        <h3><?= htmlspecialchars($row['doctor_name']) ?></h3>
                        <p><?= htmlspecialchars($row['doctor_specialty']) ?></p>
                    </div>
                </div>
                <div class="box-middle">
                    <h4><?= htmlspecialchars($row['title']) ?></h4>
                    <p>
                        <?= substr(strip_tags($row['description']), 0, 120) ?>...
                    </p>
                </div>
                <div class="box-buttom">
                    <div id="data">
                        <data><?= date("F j, Y, h:i A", strtotime($row['created_at'])) ?></data>
                        <span id="back"><data><?= htmlspecialchars($row['category']) ?></data></span>
                    </div>
                    <a href="patient-tipsDetails.php?tip_id=<?= $row['tip_id'] ?>" id="readmore">Read More</a>
                </div>
            </div>
            <?php endwhile; else: ?>
            <p style="text-align:center; font-weight:500;">No health tips available at the moment.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'patient-footer.php'; ?>


    <script>
    function filterTips() {
        alert("Filter feature is under development! 🔧");
    }
    </script>

</body>

</html>