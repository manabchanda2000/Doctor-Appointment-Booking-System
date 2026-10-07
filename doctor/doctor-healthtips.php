<?php
session_start();
include '../connection.php';

$doctor_id = $_SESSION['doctor_id'] ?? null;

if (!$doctor_id) {
    header("Location: doctor-login.php");
    exit();
}
$conditions = ["doctor_id = '$doctor_id'"];

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

$where_clause = implode(" AND ", $conditions);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    .tips-sec {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
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

    .box-top {
        display: flex;
        align-items: flex-start;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 10px;
        width: 100%;
    }

    .box-top h3 {
        font-size: 18px;
    }

    .box-top p {
        font-size: 14px;
        color: #4B5563;
        text-align: justify;
        line-height: 1.5;
    }

    .box-bottom {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .box-bottom #like {
        background-color: transparent;
        border: none;
        font-size: 14px;
        color: #1B9AF5;
    }

    .box-bottom #back {
        padding: 4px 6px;
        background-color: #c1d6f7;
        color: #1c52a9;
        border-radius: 20px;
        font-size: 14px;

    }

    .box-bottom #time {
        font-size: 12px;
        color: #4B5563;
    }

    .no-tips {
        text-align: center;
        margin-top: 80px;
        color: #888;
        font-size: 18px;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
    }

    .modal-content {
        background: #fff;
        margin: 5% auto;
        padding: 30px;
        border-radius: 12px;
        max-width: 500px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
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

    .close-btn {
        position: absolute;
        top: 12px;
        right: 16px;
        font-size: 24px;
        color: #888;
        cursor: pointer;
    }

    .question-form label {
        font-weight: 600;
        display: block;
        margin-top: 12px;
        color: #444;
    }

    .question-form input[type="text"],
    .question-form input[type="file"],
    .question-form select,
    .question-form textarea {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    .question-form button {
        margin-top: 20px;
        padding: 12px 20px;
        background: #3B82F6;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: 0.2s;
    }

    .question-form button:hover {
        background: #2563eb;
    }

    #readmore {
        text-decoration: none;
        color: #1B9AF5;
        font-weight: bold;

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

                <div class="add">
                    <a href="#" id="add-btn" onclick="openModal()">Add Tips</a>
                </div>
            </form>
        </section>


        <section class="third-sec">
            <div class="tips-sec">
                <?php
            $query = "SELECT * FROM health_tips WHERE $where_clause $order_by";
            $result = mysqli_query($conn, $query);
            ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($tip = mysqli_fetch_assoc($result)): ?>
                <article class="artical-box">
                    <div class="box-top">
                        <h3><?= htmlspecialchars($tip['title']) ?></h3>
                        <p><?= substr(strip_tags($tip['description']), 0, 250) ?>...<a
                                href="../patient/patient-tipsDetails.php?tip_id=<?= $tip['tip_id'] ?>"
                                id="readmore">Read More</a></p>
                    </div>
                    <div class="box-bottom">
                        <span id="back"><data><?= htmlspecialchars($tip['category']) ?></data></span>
                        <span id="time"><?= date("F d, Y, h:i A", strtotime($tip['created_at'])) ?></span>
                        <?php 
                        // Show delete option only if the notice is for doctors
                        if ($tip['doctor_id'] == $doctor_id) { 
                        ?>
                        <a href="doctor-deleteTips.php?tip_id=<?= $tip['tip_id'] ?>"
                            style="color: red;"
                            onclick="return confirm('Are you sure you want to delete this Health Tips?');">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                        <?php 
                        } 
                        ?>
                    </div>
                </article>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="no-tips">
                    <p>No health tips found. You haven't added any tips yet.</p>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <!-- Add Tips Modal -->
        <div id="tipsModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Add Health Tips</h2>

                <form method="POST" action="doctor-tips-submit.php" enctype="multipart/form-data" class="question-form">
                    <input type="hidden" name="doctor_id" value="<?= $_SESSION['doctor_id'] ?>">

                    <label for="category">Category:</label>
                    <select name="category" required>
                        <option value="">-- Choose Category --</option>
                        <option value="Diet">Diet</option>
                        <option value="Fitness">Fitness</option>
                        <option value="Mental Health">Mental Health</option>
                        <option value="Lifestyle">Lifestyle</option>
                        <option value="Others">Others</option>
                    </select>

                    <label for="title">Tips Title:</label>
                    <input type="text" name="title" required placeholder="Type the headline of your tip">

                    <label for="description">Tips Description:</label>
                    <textarea name="description" rows="4" required
                        placeholder="Explain the health tip in detail..."></textarea>

                    <label for="image">Upload Image (optional):</label>
                    <input type="file" name="image" accept="image/*">

                    <button type="submit">Submit Tip</button>
                </form>
            </div>
        </div>
    </main>
    <script>
    function openModal() {
        document.getElementById("tipsModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("tipsModal").style.display = "none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById("tipsModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
    </script>

    <?php include 'doctor-footer.php'; ?>
</body>

</html>