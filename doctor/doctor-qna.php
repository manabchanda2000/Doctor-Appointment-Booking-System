<?php
session_start();
include '../connection.php';

$doctor_id = $_SESSION['doctor_id'] ?? null;

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}
$conditions = [];
$params = [];

// Category filter
if (!empty($_GET['category'])) {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $conditions[] = "specialty = '$category'";
}

// Status filter
if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $conditions[] = "status = '$status'";
}

// Order filter
$order_by = "ORDER BY question_time DESC"; // Default to latest
if (!empty($_GET['order']) && $_GET['order'] === 'asc') {
    $order_by = "ORDER BY question_time ASC";
}

$where_clause = "";
if (count($conditions) > 0) {
    $where_clause = "WHERE " . implode(" AND ", $conditions);
}
$count_query = "SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN status = 'Answered' THEN 1 ELSE 0 END) AS answered
    FROM question";


$count_result = mysqli_query($conn, $count_query);
if ($count_result && mysqli_num_rows($count_result) > 0) {
    $counts = mysqli_fetch_assoc($count_result);
    $pending_count = $counts['pending'];
    $answered_count = $counts['answered'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ask a Question</title>
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

    /* first section-------------------- */
    .first-sec {
        display: flex;
        align-items: center;
        gap: 20px;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .count-part {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .count-box {
        flex: 0 0 200px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
        border-radius: 5px;
        padding: 20px;
        background: linear-gradient(135deg, #EFF6FF -4%, #DBEAFE 100%);
    }

    .sf-part {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;

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

    /* second section---------------------- */
    .second-sec {
        margin: 20px 0;
    }

    .second-sec h2 {
        font-size: 20px;
        margin-bottom: 30px;
    }

    .qna-part {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .question-box {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
        background: #fdfdfd;
        border: 1px solid #BFDBFE;
        border-left: 5px solid #1976d2;
        border-radius: 10px;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.13);
        width: 100%;
        padding: 20px;
        margin-bottom: 20px;
    }

    .box-top {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        width: 100%;
    }

    #p-ques {
        flex: 1;
    }

    #p-ques h3 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    #p-ques span {
        color: #4B5563;
        font-size: 12px;
    }

    .box-top #status-back {
        padding: 5px 8px;
        border-radius: 20px;
        background-color: #cdf7cd;
        color: #106610;
        font-size: 14px;
    }

    .box-top #time {
        color: #4B5563;
        font-size: 12px;
    }

    .box-middle p {
        color: #4B5563;
        font-size: 14px;
        line-height: 1.4;
        text-align: justify;
    }

    .box-bottom {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .box-bottom #cat-back {
        padding: 4px 6px;
        background-color: #c1d6f7;
        color: #1c52a9;
        border-radius: 20px;
        font-size: 14px;
    }

    .box-bottom #view-btn {
        border: 1px solid #3B82F6;
        background-color: white;
        padding: 6px 12px;
        color: #3B82F6;
        text-decoration: none;
        border-radius: 5px;
    }
    </style>
</head>

<body>

    <!-- Include header and sidebar -->
    <?php include 'doctor-header.php'; ?>

    <main>
        <section class="first-sec">
            <div class="count-part">
                <div class="count-box">
                    <strong><?= $pending_count ?? 0 ?></strong>
                    <p>Questions Pending</p>
                </div>
                <div class="count-box">
                    <strong><?= $answered_count ?? 0 ?></strong>
                    <p>Questions Answered</p>
                </div>
            </div>
            <form method="GET" class="sf-part">
                <div class="filter-group">
                    <select name="category">
                        <option value="" <?= empty($_GET['category']) ? 'selected' : '' ?>>All Category</option>
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
                    <select name="status">
                        <option value="" <?= empty($_GET['status']) ? 'selected' : '' ?>>All Question</option>
                        <option value="Pending" <?= ($_GET['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending
                        </option>
                        <option value="Answered" <?= ($_GET['status'] ?? '') === 'Answered' ? 'selected' : '' ?>>
                            Answered</option>
                    </select>
                </div>

                <div class="filter-group">
                    <select name="order">
                        <option value="desc" <?= ($_GET['order'] ?? 'desc') === 'desc' ? 'selected' : '' ?>>Latest
                        </option>
                        <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Oldest</option>
                    </select>
                </div>

                <button type="submit" id="filter-btn">Apply Filter</button>
            </form>
        </section>

        <section class="second-sec">
            <h2>Patient's Questions</h2>
            <div class="qna-part">
                <?php
                $query = "SELECT * FROM question $where_clause $order_by";
                $result = mysqli_query($conn, $query);
                ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($q = mysqli_fetch_assoc($result)): ?>
                <article class="question-box">
                    <div class="box-top">
                        <span id="p-ques">
                            <h3><?= htmlspecialchars($q['question']) ?>?</h3>
                            <span>Patient Id: <?= htmlspecialchars($q['patient_id']) ?></span>
                        </span>
                        <span id="status-back"><data><?= htmlspecialchars($q['status']) ?></data></span>
                        <span id="time"><?= date("F d, Y, h:i A", strtotime($q['question_time'])) ?></span>
                    </div>
                    <div class="box-middle">
                        <p><?= nl2br(htmlspecialchars($q['description'])) ?></p>
                    </div>
                    <div class="box-bottom">
                        <span id="cat-back"><data><data><?= htmlspecialchars($q['specialty']) ?></data></span>
                        <a href="doctor-viewAnswer.php?question_id=<?= $q['question_id'] ?>" id="view-btn">View & Reply</a>

                    </div>
                </article>
                <?php endwhile; ?>
                <?php else: ?>
                <div
                    style="padding: 20px; background: #f9f9f9; border-radius: 8px; color: #555; text-align: center; width: 100%; border: 1px solid #555;">
                    <strong>No questions found.</strong><br>
                    You haven’t asked any questions yet.
                </div>
                <?php endif; ?>
            </div>

        </section>
    </main>

    <?php include 'doctor-footer.php'; ?>
    <!-- <script>
        function submitQuestion(event) {
            event.preventDefault();
            const title = document.getElementById("title").value;
            const category = document.getElementById("category").value;
            const description = document.getElementById("description").value;

            // You can send data to server here using AJAX or fetch
            alert("Your question has been submitted!\n\nTitle: " + title + "\nCategory: " + category + "\nDescription: " + description);

            // Reset form
            document.getElementById("questionForm").reset();
        }
    </script> -->

</body>

</html>